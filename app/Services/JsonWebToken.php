<?php

namespace App\Services;

use App\Contracts\Signature;
use App\Contracts\Token as TokenContract;
use App\Exceptions\InvalidRefreshTokenException;
use App\Exceptions\NotParsableTokenException;
use App\Models\RefreshToken;
use App\Models\User;
use Carbon\Carbon;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class JsonWebToken implements TokenContract
{
    private Configuration $configuration;

    private string $refresh_token;

    private int $refresh_token_expires_in;

    private Signature $signature;

    public function __construct(Signature $signature)
    {
        $this->signature = $signature;
        $this->configureToken();
    }

    /**
     * @throws ValidationException
     */
    public function generate(array $credentials): string
    {
        $user = $this->getUserFromCredentials(config('jwt.credentials'), $credentials);
        $this->deleteExistingTokens($user->id);
        $this->saveRefreshTokenToModel($user);

        return $this->createJsonWebTokenFromModel($user)->toString();
    }

    /**
     * @throws InvalidRefreshTokenException
     */
    public function refresh(string $refresh_token): string
    {
        $refresh_token = RefreshToken::where('token', $refresh_token)->where('is_revoked', false)->first();
        if (null === $refresh_token) {
            throw new InvalidRefreshTokenException();
        }

        if ($refresh_token->expired_datetime < Carbon::now()) {
            throw new InvalidRefreshTokenException();
        }

        $this->refresh_token = $refresh_token->token;
        $this->refresh_token_expires_in = Carbon::now()->diffInSeconds($refresh_token->expired_datetime);

        return $this->createJsonWebTokenFromModel(
            $refresh_token->model
        )->toString();
    }

    /**
     * @throws NotParsableTokenException
     */
    public function parse(string $token): Token
    {
        try {
            return $this->configuration->parser()->parse($token);
        } catch (Exception $exception) {
            throw new NotParsableTokenException($exception);
        }
    }

    /**
     * @throws NotParsableTokenException
     */
    public function validateBase64(string $bearerToken): bool
    {
        $token = $this->parse($bearerToken);

        return $this->validate($token);
    }

    public function validate($token): bool
    {
        $constraints = $this->configuration->validationConstraints();

        try {
            $this->configuration->validator()->assert($token, ...$constraints);

            return true;
        } catch (RequiredConstraintsViolated $e) {
            return false;
        }
    }

    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    public function getRefreshTokenExpiresIn(): int
    {
        return $this->refresh_token_expires_in;
    }

    /**
     * Get user instance.
     *
     * @throws NotParsableTokenException
     */
    public function getAuthenticatable(string $bearerToken): Model
    {
        $token = $this->parse($bearerToken);
        $model = $token->claims()->get('model');

        return (new $model())->find($token->claims()->get('sub'));
    }

    public function signatureIsValid($credentials): bool
    {
        return $this->signature->verify(
            $credentials['signature'],
            $credentials['address'],
            $credentials['message']
        );
    }

    private function configureToken(): void
    {
        $this->configuration = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(config('jwt.secret'))
        );

        $this->configuration->setValidationConstraints(
            new IssuedBy(url('/')),
            new PermittedFor(url('/')),
            new SignedWith($this->configuration->signer(), $this->configuration->signingKey()),
            new StrictValidAt(SystemClock::fromUTC())
        );
    }

    private function deleteExistingTokens(int $user_id)
    {
        RefreshToken::where([
            'model_type' => User::class,
            'model_id' => $user_id,
        ])->delete();
    }

    /**
     * @throws ValidationException
     */
    private function getUserFromCredentials(array $needed_credentials, array $credentials): mixed
    {
        $this->validateCredentials($needed_credentials, $credentials);

        return $this->getUser($credentials);
    }

    /**
     * @throws ValidationException
     */
    private function validateCredentials(array $needed_credentials, array $credentials): void
    {
        $validation_array = array_fill_keys(array_keys($needed_credentials), 'required');
        $validator = Validator::make($credentials, $validation_array);

        $validator->validate();
    }

    private function getUser(array $credentials): mixed
    {
        $address_exists = User::where('eth_address', $credentials['address'])->exists();
        if ($address_exists && $this->signatureIsValid($credentials)) {
            $user = User::where('eth_address', $credentials['address'])->first();
        }

        if (!isset($user)) {
            throw new ModelNotFoundException('User not found');
        }

        return $user;
    }

    private function saveRefreshTokenToModel(Model $model_instance): void
    {
        $this->refresh_token = Str::uuid();
        $refresh_token_expiration_date = Carbon::now()->addSeconds(config('jwt.time_to_live_refresh'));

        RefreshToken::create([
            'token' => $this->refresh_token,
            'issued_datetime' => Carbon::now(),
            'expired_datetime' => $refresh_token_expiration_date,
            'model_type' => get_class($model_instance),
            'model_id' => $model_instance->id,
        ]);
    }

    private function createJsonWebTokenFromModel(Model $model): Token
    {
        $now = new DateTimeImmutable();
        $builder = $this->configuration->builder()
            ->issuedBy(url('/'))
            ->permittedFor(url('/'))
            ->identifiedBy(md5($now->format('YmdHisu').$model->id))
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->modify('+'.config('jwt.time_to_live_access').' seconds'))
            ->relatedTo($model->id)
            ->withClaim('model', get_class($model))
        ;

        $this->addCustomClaimsFromConfig($builder, $model);

        return $builder->getToken($this->configuration->signer(), $this->configuration->signingKey());
    }

    private function addCustomClaimsFromConfig(Builder $builder, Model $model): void
    {
        foreach (config('jwt.with_claims') as $key => $claim) {
            if (is_array($claim)) {
                $claim_to_add_in_jwt = [];
                foreach ($claim as $sub_claim_key => $sub_claim) {
                    $temp = $model;

                    foreach (explode('.', $sub_claim) as $iValue) {
                        $temp = $temp->{$iValue};
                    }

                    $claim_to_add_in_jwt[$sub_claim_key] = $temp;
                }

                $builder->withClaim($key, $claim_to_add_in_jwt);

                continue;
            }

            $temp = $model;
            foreach (explode('.', $claim) as $iValue) {
                $temp = $temp->{$iValue};
            }

            $builder->withClaim($key, $temp);
        }
    }
}
