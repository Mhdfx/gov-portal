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
        Schema::create('form_routing_rules', function (Blueprint $table) {
            $table->id();
            $table->enum('form_type', ['investment', 'project_carrier', 'idea_carrier', 'auto_entrepreneur', 'indh', 'training']);
            $table->string('region')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->integer('priority_order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->text('conditions')->nullable(); // JSON conditions for complex routing
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['form_type', 'region', 'sector']);
            $table->index(['institution_id', 'is_active']);
            $table->index('priority_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_routing_rules');
    }
};
