<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_withdraws', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id');
            $table->integer('amount');
            $table->string('account_bank');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('description');
            $table->string('status')->default('pending'); //pending,accept,failed
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
        Schema::drop('project_withdraws');
    }
}
