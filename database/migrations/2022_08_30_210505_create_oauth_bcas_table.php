<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthBcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_bcas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('access_token');
            $table->string('token_type');
            $table->integer('expires_in');
            $table->timestamp('expired_at');
            $table->string('scope');
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
        Schema::drop('oauth_bcas');
    }
}
