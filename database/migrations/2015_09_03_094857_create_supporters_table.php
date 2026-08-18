<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supporters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('project_id');
            $table->text('reward_id')->nullable();
            $table->integer('money');
            $table->integer('unique_code')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('notes');
            $table->string('status')->default('pending'); // accept, pending
            $table->boolean('has_confirm_payment');
            $table->boolean('noauth')->default(false);
            $table->string('email')->default('');
            $table->string('phone')->default('');
            $table->string('city')->nullable();
            $table->string('fullname')->default('');
            $table->string('referal')->default('');
            $table->boolean('is_anonim')->default(FALSE);
            $table->timestamp('payment_confirm_at');
            $table->text('snap_token')->nullable();
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
        Schema::drop('supporters');
    }
}
