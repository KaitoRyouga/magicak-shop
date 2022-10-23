<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Managers\ProductManager;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('user_websites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->unsignedBigInteger('hosting_plan_id')->nullable();
            $table->unsignedBigInteger('dc_location_id')->nullable();
            $table->unsignedBigInteger('domain_type_id')->nullable();
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->unsignedBigInteger('website_message_id')->nullable();
            $table->string('business_name');
            $table->string('current_tasks')->nullable()->comment('create_web, up_dns');
            $table->enum('status', ProductManager::STATUSES)->default(ProductManager::STATUS_INITIAL);
            $table->string('template_name')->index()->nullable();
            $table->string('template_code')->index()->nullable();
            $table->string('template_version')->nullable();
            $table->unsignedTinyInteger('latest_purchased_package')->default(6)->comment('month');
            $table->float('hosting_ram')->nullable()->comment('GB');
            $table->float('hosting_cpu')->nullable()->comment('Core');
            $table->float('hosting_app_storage')->nullable()->comment('GB');
            $table->float('hosting_db_storage')->nullable()->comment('GB');
            $table->string('hosting_cluster')->nullable();
            $table->timestamp('hosting_created_date')->nullable();
            $table->timestamp('hosting_expired_date')->nullable();
            $table->string('dc_location')->nullable();
            $table->string('hosting_ip')->nullable();
            $table->string('website_url')->nullable();
            $table->boolean('backup_option')->default(0);
            $table->boolean('backup_status')->default(0);
            $table->timestamp('backup_date')->nullable();
            $table->float('total_price')->default(0);
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            // $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('template_id')->references('id')->on('templates')->nullOnDelete();
            $table->foreign('hosting_plan_id')->references('id')->on('hosting_plans')->nullOnDelete();
            $table->foreign('dc_location_id')->references('id')->on('data_center_locations')->nullOnDelete();
            $table->foreign('domain_type_id')->references('id')->on('domain_types')->nullOnDelete();
            $table->foreign('domain_id')->references('id')->on('domains')->nullOnDelete();
            $table->foreign('website_message_id')->references('id')->on('website_messages')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('user_websites');
        Schema::enableForeignKeyConstraints();
    }
}
