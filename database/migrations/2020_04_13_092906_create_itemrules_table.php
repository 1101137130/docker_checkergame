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
            $table->mediumInteger('one')->nullable();
            $table->mediumInteger('two')->nullable();
            $table->mediumInteger('three')->nullable();
            $table->mediumInteger('four')->nullable();
            $table->mediumInteger('five')->nullable();
            $table->mediumInteger('special_cards')->nullable();
            $table->string('extend_exist_rule')->nullable();
            $table->string('total')->nullable();
            $table->tinyInteger('operator')->nullable();
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
