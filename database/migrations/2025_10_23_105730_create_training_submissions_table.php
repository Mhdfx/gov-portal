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
        Schema::create('training_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('submission_number')->unique();
            $table->string('training_title');
            $table->text('training_description');
            $table->enum('training_type', ['technical', 'business', 'soft_skills', 'certification', 'workshop', 'seminar']);
            $table->text('target_audience');
            $table->integer('participant_count');
            $table->integer('duration_hours');
            $table->string('preferred_location')->nullable();
            $table->text('preferred_schedule')->nullable();
            $table->decimal('budget_available', 15, 2)->nullable();
            $table->string('budget_currency', 3)->default('MAD');
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'scheduled', 'completed'])->default('pending');
            $table->json('routing_institutions')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['training_type', 'status']);
            $table->index('submitted_at');
            $table->index('submission_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_submissions');
    }
};
