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
        Schema::create('transactions', function (Blueprint $table) {
            $transactionType = config("data.transactionType");
            $approval = config("data.approval");
            $provider = config("data.providers");

            $table->id();
            $table->string("reference", 20)->nullable();
            $table->string("authorization_url")->nullable();
            $table->foreignId("user_id");
            $table->foreignId("asset_id");
            $table->decimal('amount', total: 10, places: 8)->default(0.00000000);
            $table->text("description");
            $table->enum("tx_type", array_values($transactionType));
            $table->enum("provider", array_values($provider))->default(object($provider)->wallet);
            $table->string("transaction_id")->nullable();

            $table->enum("status", array_values($approval))->default(object($approval)->pending);
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
        Schema::dropIfExists('transactions');
    }
};
