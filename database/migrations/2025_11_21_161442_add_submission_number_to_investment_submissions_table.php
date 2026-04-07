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
        Schema::table('investment_submissions', function (Blueprint $table) {
            // Add submission_number if it doesn't exist
            if (!Schema::hasColumn('investment_submissions', 'submission_number')) {
                $table->string('submission_number')->unique()->nullable()->after('user_id');
            }
            
            // Add currency if it doesn't exist (migration has default but might be missing)
            if (!Schema::hasColumn('investment_submissions', 'currency')) {
                $table->string('currency', 3)->default('MAD')->after('investment_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_submissions', function (Blueprint $table) {
            if (Schema::hasColumn('investment_submissions', 'submission_number')) {
                $table->dropColumn('submission_number');
            }
            // Don't drop currency as it might be needed
        });
    }
};
