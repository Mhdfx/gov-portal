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
        Schema::create('auto_entrepreneur_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('submission_number')->unique();
            $table->string('business_name');
            $table->text('business_description');
            $table->string('sector');
            $table->enum('business_type', ['service', 'commerce', 'manufacturing', 'consulting', 'digital', 'other']);
            $table->decimal('startup_capital', 15, 2);
            $table->string('capital_currency', 3)->default('MAD');
            $table->string('cv_path')->nullable();
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
        Schema::dropIfExists('auto_entrepreneur_submissions');
    }
};
