<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAmountTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        
        CREATE TRIGGER amount_trigger AFTER INSERT ON laravel.amountrecords FOR EACH ROW
            BEGIN
                UPDATE  laravel.amounts 
                SET amount = amount + NEW.amount
                WHERE user_id = NEW.user_id;
            END
        
        ');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `amount_trigger`');

    }
}
