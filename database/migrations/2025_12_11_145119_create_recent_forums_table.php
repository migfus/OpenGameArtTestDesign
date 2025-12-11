<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('recent_forums', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id')->nullable(); // null for 1st time fetch of data
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('title');
            $table->longText('content')->nullable(); // null for 1st time fetch of data

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('recent_forums');
    }
};
