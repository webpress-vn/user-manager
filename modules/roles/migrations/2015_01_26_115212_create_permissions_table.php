<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('model')->nullable();
            $table->bigInteger('permission_group_id')->unsigned();
            $table->timestamps();
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreign('permission_group_id')->references('id')->on('permission_group')->onDelete('cascade');
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->index(['permission_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permissions');
    }
}
