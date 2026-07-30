<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('arts', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('art_category_id')->nullable()->constrained('art_categories')->cascadeOnUpdate()->nullOnDelete();

            $table->string('title');
            $table->longText('content')->nullable();
            $table->unsignedInteger('favorites_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->string('image_preview')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('arts');
    }
};
