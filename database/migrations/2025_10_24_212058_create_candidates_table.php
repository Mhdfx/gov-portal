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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('nationality');
            $table->string('address');
            $table->string('city');
            $table->string('region');
            $table->string('postal_code')->nullable();
            $table->string('education_level');
            $table->string('field_of_study')->nullable();
            $table->string('university')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->json('skills')->nullable();
            $table->json('languages')->nullable();
            $table->text('professional_summary')->nullable();
            $table->string('cv_file_path')->nullable();
            $table->string('cover_letter_path')->nullable();
            $table->string('profile_picture_path')->nullable();
            $table->string('availability')->default('immediate');
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('preferred_job_type')->default('full_time');
            $table->json('preferred_locations')->nullable();
            $table->json('preferred_sectors')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index(['is_available', 'is_verified']);
            $table->index(['region', 'city']);
            $table->index('education_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};