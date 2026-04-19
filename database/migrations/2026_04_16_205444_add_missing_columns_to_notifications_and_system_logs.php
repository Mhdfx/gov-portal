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
        // Add missing columns to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('read_at');
            }
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->string('priority')->default('low')->after('is_read');
            }
            if (!Schema::hasColumn('notifications', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('priority');
            }
        });

        // Add missing columns to system_logs table
        Schema::table('system_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('system_logs', 'level')) {
                $table->string('level')->default('info')->after('action');
            }
            if (!Schema::hasColumn('system_logs', 'description')) {
                $table->text('description')->nullable()->after('level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['is_read', 'priority', 'expires_at']);
        });

        Schema::table('system_logs', function (Blueprint $table) {
            $table->dropColumn(['level', 'description']);
        });
    }
};
