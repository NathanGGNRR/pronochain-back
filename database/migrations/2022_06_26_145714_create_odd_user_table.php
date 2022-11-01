<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('odd_user', function (Blueprint $table) {
            $table->foreignId('odd_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();

            $table->primary(['odd_id', 'user_id'], 'odd_user_odd_id_user_id_primary');
            $table->unique(['odd_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('odd_user');
    }
};
