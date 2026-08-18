<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZakatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zakat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('amount');
            $table->string('fullname');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('city')->nullable();
            $table->boolean('is_anonim')->default(FALSE);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->boolean('is_payment_confirmed');
            $table->timestamp('payment_confirm_at');
            $table->text('snap_token')->nullable();
            $table->string('payment_method', 50)->nullable();
            $table->integer('unique_code')->nullable();
            $table->string('va_number')->nullable();
            $table->text('redirect_url')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->string('code_referral')->nullable(); //from table users
            $table->boolean('sent_expired_email')->default(FALSE);
            $table->boolean('is_checked')->default(FALSE);
            $table->text('check_note');
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
        Schema::drop('zakat');
    }
}
