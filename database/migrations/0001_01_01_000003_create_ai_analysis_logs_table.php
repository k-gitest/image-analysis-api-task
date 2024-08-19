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
        Schema::create('ai_analysis_logs', function (Blueprint $table) {
            $table->integer('id', true, true)->length(11);
            $table->string('image_path', 255)->nullable();
            $table->tinyInteger('success')->length(1)->nullable(false);
            $table->string('message', 255)->nullable();
            $table->integer('class', false, true)->length(11)->nullable();
            $table->decimal('confidence', 5, 4)->nullable();
            $table->dateTime('request_timestamp', 6)->nullable();
            $table->dateTime('response_timestamp', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analysis_logs');
    }
};
