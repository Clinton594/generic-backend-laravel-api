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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('referral_id');
            $table->unsignedBigInteger('referred_id');
            $table->decimal('amount', total: 10, places: 8)->default(0.00000000);

            $table->boolean("status")->default(false);
            $table->timestamps();
        });

        Schema::table("referrals", function (Blueprint $table) {
            $table->foreign('referral_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('referred_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
