<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('ip');
            $table->integer('port')->default(25565);
            $table->longText('description');
            $table->string('website_url')->nullable();
            $table->unsignedBigInteger('owned_by_id');
            $table->unsignedBigInteger('banner_id')->nullable();
            $table->unsignedBigInteger('logo_id')->nullable();
            $table->unsignedBigInteger('version_id');
            $table->smallInteger('playerCount')->default(0);
            $table->smallInteger('maxPlayers')->default(0);
            $table->boolean('online')->default(true);
            $table->timestamps();
        });

        Schema::create('server_vote_site', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('vote_site_id');
            $table->unsignedBigInteger('server_id');
            $table->string('server_id_on_site')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servers');
        Schema::dropIfExists('server_vote_site');
    }
}
