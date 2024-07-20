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
        Schema::create('contents', function (Blueprint $table) {
            $contentType = config("data.contentType");
            $approval = config("data.approval");

            $table->id();
            $table->string("title");
            $table->longText("body");
            $table->longText("created_by");
            $table->text("image");
            $table->string("url");
            $table->tinyInteger("views")->default(0);
            $table->enum("type", array_values($contentType));

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
        Schema::dropIfExists('contents');
    }
};
