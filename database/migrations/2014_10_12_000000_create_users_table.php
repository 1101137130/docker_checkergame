<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->string('username', 20)->unique();
            $table->string('email')->unique();
            $table->string('password', 256);
            $table->tinyInteger('status')->default(1)->nullable();
            $table->boolean('view_orders')->default(0)->nullable();
            $table->boolean('manager_editor')->default(0)->nullable();
            $table->boolean('manage_rate')->default(0)->nullable();
            $table->boolean('deposit_able')->default(0)->nullable();
            $table->boolean('order_amount_arrangement')->default(0)->nullable();
            $table->rememberToken();
            $table->Integer('created_at');
            $table->Integer('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
