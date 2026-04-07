<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DatabasePerformanceService
{
    /**
     * Test database performance with various queries
     */
    public function runPerformanceTests(): array
    {
        $results = [];
        
        // Test 1: Simple count queries
        $results['count_queries'] = $this->testCountQueries();
        
        // Test 2: Complex joins
        $results['join_queries'] = $this->testJoinQueries();
        
        // Test 3: Index usage
        $results['index_usage'] = $this->testIndexUsage();
        
        // Test 4: Cache performance
        $results['cache_performance'] = $this->testCachePerformance();
        
        // Test 5: Connection pooling
        $results['connection_pooling'] = $this->testConnectionPooling();
        
        return $results;
    }

    /**
     * Test count query performance
     */
    private function testCountQueries(): array
    {
        $startTime = microtime(true);
        
        $counts = [
            'users' => DB::table('users')->count(),
            'companies' => DB::table('companies')->count(),
            'auto_entrepreneur_submissions' => DB::table('auto_entrepreneur_submissions')->count(),
            'idea_carrier_submissions' => DB::table('idea_carrier_submissions')->count(),
            'project_carrier_submissions' => DB::table('project_carrier_submissions')->count(),
            'investment_submissions' => DB::table('investment_submissions')->count(),
            'indh_submissions' => DB::table('indh_submissions')->count(),
            'training_submissions' => DB::table('training_submissions')->count(),
        ];
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds
        
        return [
            'execution_time_ms' => round($executionTime, 2),
            'counts' => $counts,
            'performance_rating' => $this->getPerformanceRating($executionTime, 100), // 100ms threshold
        ];
    }

    /**
     * Test join query performance
     */
    private function testJoinQueries(): array
    {
        $startTime = microtime(true);
        
        $results = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoin('companies', 'users.id', '=', 'companies.user_id')
            ->select('users.id', 'users.username', 'users.role', 'user_profiles.first_name', 'user_profiles.last_name', 'companies.company_name')
            ->limit(100)
            ->get();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        return [
            'execution_time_ms' => round($executionTime, 2),
            'result_count' => $results->count(),
            'performance_rating' => $this->getPerformanceRating($executionTime, 200), // 200ms threshold
        ];
    }

    /**
     * Test index usage
     */
    private function testIndexUsage(): array
    {
        $startTime = microtime(true);
        
        // Test queries that should use indexes
        $indexedQueries = [
            'users_by_role' => DB::table('users')->where('role', 'user')->count(),
            'companies_by_status' => DB::table('companies')->where('approval_status', 'pending')->count(),
            'submissions_by_status' => DB::table('auto_entrepreneur_submissions')->where('status', 'pending')->count(),
        ];
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        return [
            'execution_time_ms' => round($executionTime, 2),
            'indexed_queries' => $indexedQueries,
            'performance_rating' => $this->getPerformanceRating($executionTime, 50), // 50ms threshold
        ];
    }

    /**
     * Test cache performance
     */
    private function testCachePerformance(): array
    {
        $results = [];
        
        // Test cache write performance
        $startTime = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            Cache::put("test_key_$i", "test_value_$i", 60);
        }
        $writeTime = (microtime(true) - $startTime) * 1000;
        
        // Test cache read performance
        $startTime = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            Cache::get("test_key_$i");
        }
        $readTime = (microtime(true) - $startTime) * 1000;
        
        // Clean up test keys
        for ($i = 0; $i < 100; $i++) {
            Cache::forget("test_key_$i");
        }
        
        return [
            'write_time_ms' => round($writeTime, 2),
            'read_time_ms' => round($readTime, 2),
            'write_performance_rating' => $this->getPerformanceRating($writeTime, 10), // 10ms threshold
            'read_performance_rating' => $this->getPerformanceRating($readTime, 5), // 5ms threshold
        ];
    }

    /**
     * Test connection pooling
     */
    private function testConnectionPooling(): array
    {
        $startTime = microtime(true);
        
        // Simulate multiple concurrent connections
        $connections = [];
        for ($i = 0; $i < 10; $i++) {
            $connections[] = DB::connection();
        }
        
        // Test query execution with multiple connections
        foreach ($connections as $connection) {
            $connection->table('users')->count();
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        return [
            'execution_time_ms' => round($executionTime, 2),
            'connection_count' => count($connections),
            'performance_rating' => $this->getPerformanceRating($executionTime, 100), // 100ms threshold
        ];
    }

    /**
     * Get performance rating based on execution time
     */
    private function getPerformanceRating(float $executionTime, float $threshold): string
    {
        if ($executionTime < $threshold * 0.5) {
            return 'Excellent';
        } elseif ($executionTime < $threshold) {
            return 'Good';
        } elseif ($executionTime < $threshold * 2) {
            return 'Average';
        } else {
            return 'Poor';
        }
    }

    /**
     * Get database status and metrics
     */
    public function getDatabaseStatus(): array
    {
        try {
            $status = DB::select('SHOW STATUS');
            $statusArray = [];
            
            foreach ($status as $stat) {
                $statusArray[$stat->Variable_name] = $stat->Value;
            }
            
            return [
                'connections' => $statusArray['Threads_connected'] ?? 0,
                'max_connections' => $statusArray['Max_used_connections'] ?? 0,
                'queries' => $statusArray['Queries'] ?? 0,
                'slow_queries' => $statusArray['Slow_queries'] ?? 0,
                'uptime' => $statusArray['Uptime'] ?? 0,
                'cache_hit_rate' => $this->calculateCacheHitRate($statusArray),
            ];
        } catch (\Exception $e) {
            Log::error('Database status check failed: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Calculate cache hit rate
     */
    private function calculateCacheHitRate(array $status): float
    {
        $hits = $status['Qcache_hits'] ?? 0;
        $inserts = $status['Qcache_inserts'] ?? 0;
        
        if ($hits + $inserts == 0) {
            return 0;
        }
        
        return round(($hits / ($hits + $inserts)) * 100, 2);
    }

    /**
     * Optimize database based on performance test results
     */
    public function optimizeDatabase(array $testResults): array
    {
        $recommendations = [];
        
        // Analyze count query performance
        if ($testResults['count_queries']['performance_rating'] === 'Poor') {
            $recommendations[] = 'Consider adding indexes on frequently queried columns';
        }
        
        // Analyze join query performance
        if ($testResults['join_queries']['performance_rating'] === 'Poor') {
            $recommendations[] = 'Optimize join queries and ensure proper indexing on join columns';
        }
        
        // Analyze cache performance
        if ($testResults['cache_performance']['read_performance_rating'] === 'Poor') {
            $recommendations[] = 'Consider increasing cache memory or optimizing cache configuration';
        }
        
        // Analyze connection pooling
        if ($testResults['connection_pooling']['performance_rating'] === 'Poor') {
            $recommendations[] = 'Review connection pool settings and consider increasing pool size';
        }
        
        return $recommendations;
    }

    /**
     * Run database maintenance tasks
     */
    public function runMaintenanceTasks(): array
    {
        $results = [];
        
        try {
            // Analyze tables
            $tables = ['users', 'companies', 'auto_entrepreneur_submissions', 'idea_carrier_submissions', 'project_carrier_submissions', 'investment_submissions', 'indh_submissions', 'training_submissions'];
            
            foreach ($tables as $table) {
                DB::statement("ANALYZE TABLE $table");
                $results['analyzed_tables'][] = $table;
            }
            
            // Optimize tables
            foreach ($tables as $table) {
                DB::statement("OPTIMIZE TABLE $table");
                $results['optimized_tables'][] = $table;
            }
            
            $results['status'] = 'success';
            $results['message'] = 'Database maintenance completed successfully';
            
        } catch (\Exception $e) {
            $results['status'] = 'error';
            $results['message'] = $e->getMessage();
            Log::error('Database maintenance failed: ' . $e->getMessage());
        }
        
        return $results;
    }
}






























