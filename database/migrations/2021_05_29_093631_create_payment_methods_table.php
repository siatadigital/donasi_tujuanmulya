<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->string('code');
            $table->text('logo');
            $table->string('name');
            $table->string('account_name')->nullable();
            $table->string('account_number_zakat')->nullable();
            $table->string('account_number_infak')->nullable();
            $table->boolean('is_active_infak')->default(FALSE);
            $table->boolean('is_active_zakat')->default(FALSE);
            $table->boolean('is_active_campaign')->default(FALSE);
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
        Schema::drop('payment_methods');
    }
}
