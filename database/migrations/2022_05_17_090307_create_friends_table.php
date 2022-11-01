<?php

use App\Enums\FriendRequestsStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('friends', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->unsignedBigInteger('friend_id');
            $table->enum('status', array_column(FriendRequestsStatus::cases(), 'value'))->default(FriendRequestsStatus::PENDING->value);
            $table->integer('requests_count')->default(1);
            $table->timestamps();

            $table->foreign('friend_id')->references('id')->on('users');
            $table->primary(['user_id', 'friend_id'], 'friends_user_id_friend_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('friends');
    }
};
