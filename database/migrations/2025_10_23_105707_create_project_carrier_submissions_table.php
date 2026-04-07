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
        Schema::create('project_carrier_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('submission_number')->unique();
            $table->string('project_name');
            $table->text('project_description');
            $table->string('sector');
            $table->enum('development_stage', ['idea', 'prototype', 'mvp', 'scaling', 'established']);
            $table->integer('team_size')->default(1);
            $table->decimal('funding_required', 15, 2);
            $table->string('funding_currency', 3)->default('MAD');
            $table->string('business_plan_path')->nullable();
            $table->string('location_region');
            $table->string('location_city');
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'in_progress'])->default('pending');
            $table->json('routing_institutions')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['location_region', 'sector']);
            $table->index('submitted_at');
            $table->index('submission_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_carrier_submissions');
    }
};
