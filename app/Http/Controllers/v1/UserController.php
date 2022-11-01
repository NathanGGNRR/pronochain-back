<?php

namespace App\Http\Controllers\v1;

use App\Enums\FriendRequestsStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\AnswerFriendRequest;
use App\Http\Requests\FriendRequest;
use App\Http\Requests\PredictRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Odd;
use App\Models\User;
use App\Services\FriendManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private FriendManager $friendManager;

    public function __construct()
    {
        $this->friendManager = new FriendManager();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): UserCollection
    {
        return new UserCollection(User::filter(request())->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Specified resource ask for a friend.
     */
    public function requestFriend(User $user, FriendRequest $request): JsonResponse
    {
        $this->friendManager->userRequestsFriend($user, User::find($request->requested_user_id));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Specified resource answer for a friend request.
     */
    public function answerFriendRequest(User $user, AnswerFriendRequest $request): JsonResponse
    {
        $this->friendManager->answerFriendRequest($user, User::find($request->requesting_user_id), FriendRequestsStatus::tryFrom($request->status));

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function predict(User $user, PredictRequest $request)
    {
        $user->odds()->attach($request->odd_id);
    }

    public function cancelPrediction(User $user, Odd $odd)
    {
        $user->odds()->detach($odd->id);
    }
}
