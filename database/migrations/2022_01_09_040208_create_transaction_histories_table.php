<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('relation_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('order_id');
            $table->double('amount');
            $table->string('currency');
            $table->boolean('active')->default(0);
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->cascadeOnDelete();
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->cascadeOnDelete();
            $table->unsignedBigInteger('created_id')->nullable();
            // $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_histories');
    }
}
