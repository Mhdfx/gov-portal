<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds performance indexes to frequently queried columns.
     */
    public function up(): void
    {
        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            if (!$this->hasIndex('users', 'users_role_index')) {
                $table->index('role');
            }
            if (!$this->hasIndex('users', 'users_verification_status_index')) {
                $table->index('verification_status');
            }
            if (!$this->hasIndex('users', 'users_created_at_index')) {
                $table->index('created_at');
            }
            if (!$this->hasIndex('users', 'users_email_index')) {
                $table->index('email');
            }
        });

        // Add indexes to companies table if it exists
        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                if (!$this->hasIndex('companies', 'companies_approval_status_index')) {
                    $table->index('approval_status');
                }
                if (!$this->hasIndex('companies', 'companies_created_at_index')) {
                    $table->index('created_at');
                }
            });
        }

        // Add composite indexes for common query patterns
        Schema::table('investment_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('investment_submissions', 'investment_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        Schema::table('project_carrier_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('project_carrier_submissions', 'project_carrier_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        Schema::table('idea_carrier_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('idea_carrier_submissions', 'idea_carrier_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        Schema::table('auto_entrepreneur_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('auto_entrepreneur_submissions', 'auto_entrepreneur_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        Schema::table('indh_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('indh_submissions', 'indh_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });

        Schema::table('training_submissions', function (Blueprint $table) {
            if (!$this->hasIndex('training_submissions', 'training_submissions_user_status_index')) {
                $table->index(['user_id', 'status', 'created_at']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['email']);
        });

        if (Schema::hasTable('companies')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropIndex(['approval_status']);
                $table->dropIndex(['created_at']);
            });
        }

        Schema::table('investment_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('project_carrier_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('idea_carrier_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('auto_entrepreneur_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('indh_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });

        Schema::table('training_submissions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status', 'created_at']);
        });
    }

    /**
     * Check if an index exists on a table
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $driver = $connection->getDriverName();
            
            if ($driver === 'mysql') {
                // MySQL uses information_schema
                $databaseName = $connection->getDatabaseName();
                $result = $connection->select(
                    "SELECT COUNT(*) as count FROM information_schema.statistics 
                     WHERE table_schema = ? AND table_name = ? AND index_name = ?",
                    [$databaseName, $table, $indexName]
                );
                return $result[0]->count > 0;
            } else {
                // For other databases, try to get indexes directly
                try {
                    $indexes = $connection->select("SHOW INDEXES FROM `{$table}`");
                    foreach ($indexes as $index) {
                        if (isset($index->Key_name) && $index->Key_name === $indexName) {
                            return true;
                        }
                    }
                } catch (\Exception $e) {
                    // If check fails, assume index doesn't exist (safer for migrations)
                    return false;
                }
                return false;
            }
        } catch (\Exception $e) {
            // If check fails, assume index doesn't exist (safer for migrations)
            return false;
        }
    }
};

