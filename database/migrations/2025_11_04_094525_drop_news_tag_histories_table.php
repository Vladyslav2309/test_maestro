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
        Schema::dropIfExists('news_tag_histories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('news_tag_histories', function ($table) {
            $table->id();
            $table->foreignId('news_id')->constrained()->onDelete('cascade');
            $table->string('tag_name');
            $table->timestamps();
        });
    }
};
