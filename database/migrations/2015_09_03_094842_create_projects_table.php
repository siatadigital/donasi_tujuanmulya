<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('category_id');
            $table->integer('provinsi_id');
            $table->integer('kota_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('content');
            $table->string('cover')->nullable();
            $table->string('video');
            $table->string('video_type');
            $table->bigInteger('money_target');
            $table->bigInteger('money_progress');
            $table->dateTime('time_start');
            $table->dateTime('time_end');
            $table->string('type_business');
            $table->string('status');
            $table->string('status_progress');
            $table->boolean('is_featured')->default(TRUE);
            $table->integer('is_fundraiser')->default(FALSE);
            $table->integer('fundraiser_project_id')->nullable();
            $table->boolean('support_fundraiser')->default(TRUE);
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
        Schema::drop('projects');
    }
}
