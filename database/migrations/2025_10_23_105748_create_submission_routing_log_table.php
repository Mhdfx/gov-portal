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
        Schema::create('submission_routing_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->enum('submission_type', ['investment', 'project_carrier', 'idea_carrier', 'auto_entrepreneur', 'indh', 'training']);
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->timestamp('routed_at')->useCurrent();
            $table->enum('status', ['sent', 'received', 'responded', 'failed'])->default('sent');
            $table->timestamp('response_received_at')->nullable();
            $table->text('notes')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['submission_id', 'submission_type']);
            $table->index(['institution_id', 'status']);
            $table->index('routed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_routing_log');
    }
};
