<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->string('tax_number')->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->tinyInteger('type_id')->unsigned();
            $table->tinyInteger('status_id')->unsigned();
            $table->text('company_statement_description')->nullable();
            $table->dateTime('datetime');
            $table->string('number');
            $table->boolean('vat_included')->default(0);
            $table->string('waybill_number')->nullable();
            $table->dateTime('waybill_datetime')->nullable();
            $table->string('order_number')->nullable();
            $table->dateTime('order_datetime')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price')->default(0);
            $table->boolean('locked')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
