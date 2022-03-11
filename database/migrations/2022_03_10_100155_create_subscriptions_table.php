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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price')->unsigned();
            $table->smallInteger('duration_of_days')->unsigned();
            $table->integer('company_limit');
            $table->integer('safebox_limit');
            $table->integer('user_limit');
            $table->integer('invoice_limit');
            $table->integer('transaction_limit');
            $table->boolean('order_management');
            $table->boolean('product_management');
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
        Schema::dropIfExists('subscriptions');
    }
};
