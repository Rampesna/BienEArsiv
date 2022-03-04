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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('company_id')->unsigned()->nullable();
            $table->bigInteger('invoice_id')->unsigned()->nullable();
            $table->dateTime('datetime');
            $table->bigInteger('type_id')->unsigned();
            $table->string('receipt_number')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('safebox_id')->unsigned()->nullable();
            $table->boolean('direction');
            $table->double('amount')->unsigned();
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
        Schema::dropIfExists('transactions');
    }
};
