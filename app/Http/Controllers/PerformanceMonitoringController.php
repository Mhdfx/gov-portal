<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PerformanceMonitoringController extends Controller
{
    /**
     * Get performance metrics
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function metrics()
    {
        $metrics = [
            'database' => $this->getDatabaseMetrics(),
            'cache' => $this->getCacheMetrics(),
            'memory' => $this->getMemoryMetrics(),
            'requests' => $this->getRequestMetrics(),
            'timestamp' => now()->toIso8601String()
        ];

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Get slow queries
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function slowQueries()
    {
        // This would typically come from query log or APM tool
        $slowQueries = [];
        
        return response()->json([
            'success' => true,
            'data' => $slowQueries
        ]);
    }

    /**
     * Get database metrics
     */
    private function getDatabaseMetrics()
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);

            // Get connection info
            $connection = DB::connection();
            $config = $connection->getConfig();

            return [
                'status' => 'healthy',
                'response_time_ms' => $responseTime,
                'driver' => $config['driver'] ?? 'unknown',
                'database' => $config['database'] ?? 'unknown',
                'active_connections' => null, // Would need specific driver support
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get cache metrics
     */
    private function getCacheMetrics()
    {
        try {
            $driver = config('cache.default');
            $hitRate = 0; // Would need to track this
            
            return [
                'driver' => $driver,
                'hit_rate' => $hitRate,
                'status' => 'healthy'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get memory metrics
     */
    private function getMemoryMetrics()
    {
        return [
            'memory_usage_mb' => round(memory_get_usage(true) / 1024 / 1024, 2),
            'memory_peak_mb' => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            'memory_limit_mb' => $this->parseMemoryLimit(ini_get('memory_limit')),
        ];
    }

    /**
     * Get request metrics
     */
    private function getRequestMetrics()
    {
        // This would typically come from middleware or APM
        return [
            'total_requests' => 0,
            'avg_response_time_ms' => 0,
            'requests_per_minute' => 0,
        ];
    }

    /**
     * Parse memory limit string to MB
     */
    private function parseMemoryLimit($limit)
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit)-1]);
        $value = (int) $limit;
        
        switch($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1;
            case 'k':
                $value /= 1024;
        }
        
        return round($value, 2);
    }
}














