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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // ministry, cri, chamber, commune, association, etc.
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Morocco');
            $table->json('jurisdiction')->nullable(); // regions, cities, sectors they handle
            $table->json('form_types')->nullable(); // types of forms they handle
            $table->boolean('is_active')->default(true);
            $table->foreignId('admin_user_id')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['type', 'region']);
            $table->index(['is_active', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
