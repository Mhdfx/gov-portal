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
        Schema::create('investment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('project_name');
            $table->text('project_description');
            $table->decimal('investment_amount', 15, 2);
            $table->string('currency', 3)->default('MAD');
            $table->string('investment_type'); // equity, loan, grant, etc.
            $table->string('sector');
            $table->string('region');
            $table->string('city');
            $table->text('business_plan')->nullable();
            $table->json('financial_projections')->nullable();
            $table->string('contact_person');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'completed'])->default('pending');
            $table->json('routing_history')->nullable();
            $table->unsignedBigInteger('current_institution_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['status', 'submitted_at']);
            $table->index(['region', 'city', 'sector']);
            $table->index(['investment_type', 'investment_amount']);
            $table->index(['current_institution_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_submissions');
    }
};
