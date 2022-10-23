<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostingPlanPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('hosting_plan_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hosting_plan_id')->nullable();
            $table->tinyInteger('month')->index();
            $table->float('price')->default(0);
            $table->boolean('active')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('hosting_plan_id')->references('id')->on('hosting_plans')->nullOnDelete();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('hosting_plan_prices');
        Schema::enableForeignKeyConstraints();
    }
}
