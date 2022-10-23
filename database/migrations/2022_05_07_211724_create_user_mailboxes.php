<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Managers\UserWebsiteManager;

class CreateUserMailboxes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('user_mailboxes', function (Blueprint $table) {
            $table->id();
            $table->string('mailbox_domain')->nullable();
            $table->string('mailbox_url')->nullable();
            $table->enum('status', UserWebsiteManager::STATUSES)->default(UserWebsiteManager::STATUS_INITIAL);
            $table->float('mailbox_size')->nullable()->comment('GB');
            $table->timestamp('mailbox_created_date')->nullable();
            $table->timestamp('mailbox_expired_date')->nullable();
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('user_website_id')->nullable();
            $table->unsignedBigInteger('domain_id')->nullable();
            $table->unsignedBigInteger('website_message_id')->nullable();
            $table->foreign('created_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('deleted_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('user_website_id')->references('id')->on('user_websites')->nullOnDelete();
            $table->foreign('domain_id')->references('id')->on('domains')->nullOnDelete();
            $table->foreign('website_message_id')->references('id')->on('website_messages')->nullOnDelete();
            $table->boolean('active')->default(0);
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
        Schema::dropIfExists('user_mailboxes');
        Schema::enableForeignKeyConstraints();
    }
}
