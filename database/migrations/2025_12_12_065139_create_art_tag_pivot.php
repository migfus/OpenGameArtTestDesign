<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('art_tag', function (Blueprint $table) {
            $table->id();

            $table->string('art_id');
            $table->foreign('art_id')
                ->references('id')
                ->on('art')
                ->onDelete('cascade');

            $table->foreignId('tag_id')->constrained('tags')->cascadeOnUpdate()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('art_tag');
    }
};
