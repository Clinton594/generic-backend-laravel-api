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
        Schema::create('assets', function (Blueprint $table) {
            $accountStatus = config("data.accountStatus");

            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('coins_id');
            $table->decimal('staked', total: 10, places: 8)->default(0.00000000);
            $table->decimal('balance', total: 10, places: 8)->default(0.00000000);
            $table->tinyInteger('roi');
            $table->string('reoccur', 10);
            $table->string('duration', 10);

            $table->timestamp('date_renewed')->useCurrent();
            $table->timestamp('last_topup')->useCurrent();
            $table->timestamp('next_unlock')->useCurrent();
            $table->string("withdrawal_message")->nullable();

            $table->enum("status", array_values($accountStatus))->default(object($accountStatus)->pending);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
