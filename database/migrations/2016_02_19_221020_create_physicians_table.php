<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physicians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("first_name");
            $table->string("middle_name");
            $table->string("last_name");
            $table->string("primary_type");
            $table->boolean("ownership_indicator");
            $table->string("license_state_code1", 2);
            $table->string("specialty");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->index("primary_type");
            $table->index("license_state_code1");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('physicians');
    }
}
