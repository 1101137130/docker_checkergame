<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemrulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemrules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id')->unsigned();
            $table->tinyInteger('one')->nullable();
            $table->tinyInteger('twp')->nullable();
            $table->tinyInteger('three')->nullable();
            $table->tinyInteger('four')->nullable();
            $table->tinyInteger('five')->nullable();
            $table->string('specialcards', 5)->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('itemrules');
    }
}
