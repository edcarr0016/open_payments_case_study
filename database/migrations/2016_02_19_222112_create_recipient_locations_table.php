<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipientLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("address_line1");
            $table->string("address_line2")->nullable();
            $table->string("city");
            $table->string("state", 2);
            $table->string("country");
            $table->string("zip_code");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->unique([
                'address_line1',
                'city',
                'state',
                'zip_code'
            ],'address_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recipient_locations');
    }
}
