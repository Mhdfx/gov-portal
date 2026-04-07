<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Indexes for users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // Check if index doesn't exist before creating
                $indexes = $this->getIndexes('users');
                if (!in_array('users_role_index', $indexes)) {
                    $table->index('role', 'users_role_index');
                }
                if (!in_array('users_verification_status_index', $indexes)) {
                    $table->index('verification_status', 'users_verification_status_index');
                }
                if (!in_array('users_created_at_index', $indexes)) {
                    $table->index('created_at', 'users_created_at_index');
                }
                if (!in_array('users_role_status_index', $indexes)) {
                    $table->index(['role', 'verification_status'], 'users_role_status_index');
                }
            });
        }

        // Indexes for companies table
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                $indexes = $this->getIndexes('companies');
                if (!in_array('companies_approval_status_index', $indexes)) {
                    $table->index('approval_status', 'companies_approval_status_index');
                }
                if (!in_array('companies_user_id_index', $indexes)) {
                    $table->index('user_id', 'companies_user_id_index');
                }
                if (!in_array('companies_created_at_index', $indexes)) {
                    $table->index('created_at', 'companies_created_at_index');
                }
                // Note: companies table uses 'business_sectors' (JSON), not 'sector'
                // Index on business_sectors not needed as it's JSON
            });
        }

        // Indexes for products table
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $indexes = $this->getIndexes('products');
                if (!in_array('products_company_id_index', $indexes)) {
                    $table->index('company_id', 'products_company_id_index');
                }
                // Only add is_active index if column exists
                if (Schema::hasColumn('products', 'is_active')) {
                    if (!in_array('products_is_active_index', $indexes)) {
                        $table->index('is_active', 'products_is_active_index');
                    }
                    if (!in_array('products_company_active_index', $indexes)) {
                        $table->index(['company_id', 'is_active'], 'products_company_active_index');
                    }
                }
            });
        }

        // Indexes for orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $indexes = $this->getIndexes('orders');
                if (!in_array('orders_company_id_index', $indexes)) {
                    $table->index('company_id', 'orders_company_id_index');
                }
                // Only add user_id index if column exists
                if (Schema::hasColumn('orders', 'user_id')) {
                    if (!in_array('orders_user_id_index', $indexes)) {
                        $table->index('user_id', 'orders_user_id_index');
                    }
                }
                if (!in_array('orders_status_index', $indexes)) {
                    $table->index('status', 'orders_status_index');
                }
                if (!in_array('orders_created_at_index', $indexes)) {
                    $table->index('created_at', 'orders_created_at_index');
                }
                if (!in_array('orders_company_status_index', $indexes)) {
                    $table->index(['company_id', 'status'], 'orders_company_status_index');
                }
            });
        }

        // Indexes for job_listings table
        if (Schema::hasTable('job_listings')) {
            Schema::table('job_listings', function (Blueprint $table) {
                $indexes = $this->getIndexes('job_listings');
                if (!in_array('job_listings_company_id_index', $indexes)) {
                    $table->index('company_id', 'job_listings_company_id_index');
                }
                // Only add is_active index if column exists
                if (Schema::hasColumn('job_listings', 'is_active')) {
                    if (!in_array('job_listings_is_active_index', $indexes)) {
                        $table->index('is_active', 'job_listings_is_active_index');
                    }
                }
                if (!in_array('job_listings_created_at_index', $indexes)) {
                    $table->index('created_at', 'job_listings_created_at_index');
                }
            });
        }

        // Indexes for job_applications table
        if (Schema::hasTable('job_applications')) {
            Schema::table('job_applications', function (Blueprint $table) {
                $indexes = $this->getIndexes('job_applications');
                if (!in_array('job_applications_job_listing_id_index', $indexes)) {
                    $table->index('job_listing_id', 'job_applications_job_listing_id_index');
                }
                // Only add user_id index if column exists
                if (Schema::hasColumn('job_applications', 'user_id')) {
                    if (!in_array('job_applications_user_id_index', $indexes)) {
                        $table->index('user_id', 'job_applications_user_id_index');
                    }
                }
                if (!in_array('job_applications_status_index', $indexes)) {
                    $table->index('status', 'job_applications_status_index');
                }
            });
        }

        // Indexes for notifications table
        if (Schema::hasTable('notifications')) {
            Schema::table('notifications', function (Blueprint $table) {
                $indexes = $this->getIndexes('notifications');
                if (!in_array('notifications_user_id_index', $indexes)) {
                    $table->index('user_id', 'notifications_user_id_index');
                }
                if (!in_array('notifications_type_index', $indexes)) {
                    $table->index('type', 'notifications_type_index');
                }
                if (!in_array('notifications_read_at_index', $indexes)) {
                    $table->index('read_at', 'notifications_read_at_index');
                }
                if (!in_array('notifications_created_at_index', $indexes)) {
                    $table->index('created_at', 'notifications_created_at_index');
                }
            });
        }

        // Indexes for system_logs table
        if (Schema::hasTable('system_logs')) {
            Schema::table('system_logs', function (Blueprint $table) {
                $indexes = $this->getIndexes('system_logs');
                if (!in_array('system_logs_user_id_index', $indexes)) {
                    $table->index('user_id', 'system_logs_user_id_index');
                }
                if (!in_array('system_logs_action_index', $indexes)) {
                    $table->index('action', 'system_logs_action_index');
                }
                if (!in_array('system_logs_created_at_index', $indexes)) {
                    $table->index('created_at', 'system_logs_created_at_index');
                }
            });
        }

        // Indexes for submission tables (status column)
        $submissionTables = [
            'investment_submissions',
            'project_carrier_submissions',
            'idea_carrier_submissions',
            'auto_entrepreneur_submissions',
            'indh_submissions',
            'training_submissions'
        ];

        foreach ($submissionTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $indexes = $this->getIndexes($tableName);
                    if (!in_array($tableName . '_user_id_index', $indexes)) {
                        $table->index('user_id', $tableName . '_user_id_index');
                    }
                    if (!in_array($tableName . '_status_index', $indexes)) {
                        $table->index('status', $tableName . '_status_index');
                    }
                    if (!in_array($tableName . '_created_at_index', $indexes)) {
                        $table->index('created_at', $tableName . '_created_at_index');
                    }
                    if (!in_array($tableName . '_user_status_index', $indexes)) {
                        $table->index(['user_id', 'status'], $tableName . '_user_status_index');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes in reverse order
        $submissionTables = [
            'training_submissions',
            'indh_submissions',
            'auto_entrepreneur_submissions',
            'idea_carrier_submissions',
            'project_carrier_submissions',
            'investment_submissions'
        ];

        foreach ($submissionTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $indexes = $this->getIndexes($tableName);
                    if (in_array($tableName . '_user_status_index', $indexes)) {
                        $table->dropIndex($tableName . '_user_status_index');
                    }
                    if (in_array($tableName . '_created_at_index', $indexes)) {
                        $table->dropIndex($tableName . '_created_at_index');
                    }
                    if (in_array($tableName . '_status_index', $indexes)) {
                        $table->dropIndex($tableName . '_status_index');
                    }
                    if (in_array($tableName . '_user_id_index', $indexes)) {
                        $table->dropIndex($tableName . '_user_id_index');
                    }
                });
            }
        }

        // Drop other table indexes
        $tables = [
            'system_logs' => ['system_logs_created_at_index', 'system_logs_action_index', 'system_logs_user_id_index'],
            'notifications' => ['notifications_created_at_index', 'notifications_read_at_index', 'notifications_type_index', 'notifications_user_id_index'],
            'job_applications' => ['job_applications_status_index', 'job_applications_user_id_index', 'job_applications_job_listing_id_index'],
            'job_listings' => ['job_listings_created_at_index', 'job_listings_is_active_index', 'job_listings_company_id_index'],
            'orders' => ['orders_company_status_index', 'orders_created_at_index', 'orders_status_index', 'orders_user_id_index', 'orders_company_id_index'],
            'products' => ['products_company_active_index', 'products_is_active_index', 'products_company_id_index'],
            'companies' => ['companies_created_at_index', 'companies_user_id_index', 'companies_approval_status_index'],
            'users' => ['users_role_status_index', 'users_created_at_index', 'users_verification_status_index', 'users_role_index'],
        ];

        foreach ($tables as $tableName => $indexList) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($indexList) {
                    foreach ($indexList as $index) {
                        $table->dropIndex($index);
                    }
                });
            }
        }
    }

    /**
     * Get existing indexes for a table.
     */
    private function getIndexes(string $tableName): array
    {
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
            $indexes = DB::select("SHOW INDEXES FROM `{$tableName}`");
            return array_column($indexes, 'Key_name');
            } elseif ($driver === 'sqlite') {
                $indexes = DB::select("SELECT name FROM sqlite_master WHERE type='index' AND tbl_name='{$tableName}'");
                return array_column($indexes, 'name');
            } else {
                // For other databases, return empty array to be safe
                return [];
            }
        } catch (\Exception $e) {
            // If we can't get indexes, return empty array to be safe
            return [];
        }
    }
};
