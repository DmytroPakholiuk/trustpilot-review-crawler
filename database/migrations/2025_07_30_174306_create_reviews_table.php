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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('title')->index();
            $table->integer('rating');
            $table->text('content');
            $table->foreignId('reviewer_id')->references('id')->on('reviewers');
            $table->foreignId('review_subject_id')->references('id')->on('review_subjects');
            $table->date('review_date');
            $table->date('experience_date')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
