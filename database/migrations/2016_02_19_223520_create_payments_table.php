<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("physician_id", false, true)->nullable();
            $table->bigInteger("recipient_location_id", false, true);
            $table->bigInteger("gpo_id", false, true);
            $table->string("payment_type");
            $table->string("payment_form");
            $table->string("payment_recipient_indicator");
            $table->integer("payment_number", false, true);
            $table->decimal("payment_amount", 12, 2);
            $table->date("payment_date");
            $table->string("program_year", 4);
            $table->boolean("charity_indicator");
            $table->boolean("publication_dispute_indicator");
            $table->boolean("publication_delay_indicator");
            $table->date("publication_date");
            $table->string("product_name")->nullable();
            $table->string("gpo_name");
            $table->string("covered_recipient_type");
            $table->text("contextual_information")->nullable();
            $table->string("product_indicator");
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign("physician_id")
                ->references('id')
                ->on("physicians");

            $table->foreign("recipient_location_id")
                ->references('id')
                ->on("recipient_locations");

            $table->foreign("gpo_id")
                ->references('id')
                ->on("gpo");

            $table->index(["physician_id","gpo_id","payment_type","payment_form"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
