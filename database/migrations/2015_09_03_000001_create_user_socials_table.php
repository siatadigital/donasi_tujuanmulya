<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_socials', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('provider_type');
			$table->string('provider_id');
			$table->text('access_token');
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
		Schema::drop('user_socials');
	}

}
