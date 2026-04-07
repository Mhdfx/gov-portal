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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->text('requirements');
            $table->text('responsibilities');
            $table->string('location');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship', 'remote']);
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'executive']);
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->string('currency', 3)->default('MAD');
            $table->text('benefits')->nullable();
            $table->date('application_deadline')->nullable();
            $table->enum('status', ['active', 'paused', 'closed'])->default('active');
            $table->integer('views_count')->default(0);
            $table->integer('applications_count')->default(0);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['company_id', 'status']);
            $table->index(['location', 'employment_type']);
            $table->index(['experience_level', 'status']);
            $table->index('created_at');
            $table->index('application_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
