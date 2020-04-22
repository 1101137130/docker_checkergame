<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateRaterecordTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::unprepared('
        
        CREATE TRIGGER raterecord_trigger AFTER INSERT ON laravel_test.raterecords FOR EACH ROW
            BEGIN
                UPDATE  laravel_test.items 
                SET updated_at =  NEW.updated_at, rate = NEW.rate
                WHERE id = NEW.item_id;
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
        DB::unprepared('DROP TRIGGER `raterecord_trigger`');

    }
}
