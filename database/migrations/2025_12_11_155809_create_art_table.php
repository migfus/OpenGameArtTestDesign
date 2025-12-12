<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('art', function (Blueprint $table) {
            $table->string('id')->primary();

            $table->string('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreignId('art_category_id')->constrained('art_categories')->cascadeOnUpdate()->cascadeOnDelete();


            $table->string('title');
            $table->longText('content')->nullable();
            $table->unsignedInteger('favorites_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('art');
    }
};
