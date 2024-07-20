<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coins', function (Blueprint $table) {
            $activeStatus = config("data.activeStatus");


            $table->id();
            $table->string('symbol', '6');
            $table->string('name', '20');
            $table->string('logo', '255');
            $table->string('coin_id', '20');
            $table->string('address', '30')->nullable();
            $table->string('network', '10')->nullable();
            $table->string('qr_code', '255')->nullable();
            $table->tinyInteger('decimals')->default(2);
            $table->integer('minimum');
            $table->integer('maximum');
            $table->tinyInteger('roi');
            $table->string('reoccur', 10);
            $table->string('duration', 10);
            $table->boolean("restake")->default(false);

            $table->enum("status", array_values($activeStatus))->default(object($activeStatus)->inactive);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coins');
    }
};
