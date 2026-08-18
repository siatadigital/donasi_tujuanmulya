<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password', 60);
            $table->string('avatar')->nullable();
            $table->string('cover')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender')->nullable();
            $table->longText('bio');
            $table->string('province');
            $table->string('city');
            $table->string('address');
            $table->string('phone');
            $table->text('quotes');
            $table->boolean('is_artist')->default(0);
            $table->boolean('is_superadmin')->default(0);
            $table->boolean('is_verified')->default(0);
            $table->boolean('is_internal')->default(0);
            $table->string('instagram_trend')->nullable();
            $table->text('soundcloud_playlist')->nullable();
            $table->string('video_profile')->nullable();
            $table->text('youtube')->nullable();
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->text('soundcloud')->nullable();
            $table->string('fotoktp')->nullable();
            $table->string('foto_with_ktp')->nullable();
            $table->string('type_akun')->nullable();
            $table->integer('group_privilege_id')->nullable();
            $table->string('provider')->nullable();
            $table->text('provider_id')->nullable();
            $table->string('code_referral')->nullable(); //unique manual validasi
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::drop('users');
    }
}
