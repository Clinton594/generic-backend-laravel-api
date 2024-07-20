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
        Schema::create('token_verifications', function (Blueprint $table) {
            $tokenfor = config("data.tokenFor");

            $table->uuid('id')->primary();
            $table->foreignId("user_id");
            $table->enum("tokenFor", array_values($tokenfor));
            $table->float('OTP', 6)->nullable();
            $table->boolean('is_used')->default(0);
            $table->timestamp('expires_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }
    // 2023_02_21_152657_create_token_verifications_table
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('token_verifications');
    }
};
