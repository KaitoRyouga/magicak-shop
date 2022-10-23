<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePythonAutomationLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('python_automation_log', function (Blueprint $table) {
            $table->id();
            $table->string('log_type');
            $table->timestamp('created_at');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('website_id')->nullable();
            $table->string('website_name')->nullable();
            $table->text('log_information')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('python_automation_log');
    }
}
