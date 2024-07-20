<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('logo_ref');
            $table->string('website');
            $table->string('email');
            $table->string('phone', 15);
            $table->string('notifier')->nullable();
            $table->tinyInteger('notification')->default(3);
            $table->enum('email_channel', config('data.emailChannels'))->default('mailtrap');
            $table->string('address')->nullable();
            $table->boolean("lock_site")->default(false);
            $table->boolean("lock_withdrawals")->default(false);
            $table->boolean("concurrent_withdrawal")->default(false);
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
        Schema::dropIfExists('company');
    }
};
