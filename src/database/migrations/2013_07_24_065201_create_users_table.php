<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email', 40)->nullable();
            $table->string('phone_number', 40)->nullable();
            $table->string('address')->nullable();
            $table->string('username', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->date('birth')->nullable();
            $table->integer('gender')->nullable();
            $table->string('password', 100)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->boolean('email_verified')->default(0);
            $table->string('account_type')->default('normal')->comment('normal, facebook, google, ...');
            $table->string('social_id')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('status')->nullable();
            $table->string('verify_token');
            $table->rememberToken();
            $table->timestamps();
            // $table->foreign('gender')->references('id')->on('genders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
    }
}
