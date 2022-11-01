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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('logo')->nullable();
            $table->foreignId('country_id')->constrained();
            $table->foreignId('sport_id')->constrained();
            $table->timestamps();

            $table->unique(['code', 'sport_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
