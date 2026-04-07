<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class HealthCheckController extends Controller
{
    /**
     * Basic health check endpoint
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'status' => 'healthy',
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env')
        ]);
    }

    /**
     * Detailed health check with all services
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function detailed()
    {
        $checks = [
            'application' => $this->checkApplication(),
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'redis' => $this->checkRedis(),
        ];
        
        $allHealthy = collect($checks)->every(function($check) {
            return $check['status'] === 'healthy';
        });
        
        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'degraded',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
            'environment' => config('app.env')
        ], $allHealthy ? 200 : 503);
    }

    /**
     * Check application health
     */
    private function checkApplication()
    {
        try {
            return [
                'status' => 'healthy',
                'message' => 'Application is running',
                'response_time_ms' => 0
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Check database connection
     */
    private function checkDatabase()
    {
        try {
            $start = microtime(true);
            DB::connection()->getPdo();
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'response_time_ms' => $responseTime
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Database connection failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check cache
     */
    private function checkCache()
    {
        try {
            $key = 'health_check_' . time();
            $value = 'test';
            
            $start = microtime(true);
            Cache::put($key, $value, 10);
            $retrieved = Cache::get($key);
            Cache::forget($key);
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            if ($retrieved === $value) {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache is working',
                    'response_time_ms' => $responseTime
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Cache read/write failed'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Cache check failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check storage
     */
    private function checkStorage()
    {
        try {
            $testFile = 'health_check_' . time() . '.txt';
            $content = 'test';
            
            $start = microtime(true);
            Storage::disk('public')->put($testFile, $content);
            $exists = Storage::disk('public')->exists($testFile);
            Storage::disk('public')->delete($testFile);
            $responseTime = round((microtime(true) - $start) * 1000, 2);
            
            if ($exists) {
                return [
                    'status' => 'healthy',
                    'message' => 'Storage is working',
                    'response_time_ms' => $responseTime,
                    'free_space_mb' => round(disk_free_space(storage_path()) / 1024 / 1024, 2)
                ];
            } else {
                return [
                    'status' => 'unhealthy',
                    'message' => 'Storage write/read failed'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Storage check failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check Redis (if available)
     */
    private function checkRedis()
    {
        try {
            if (config('cache.default') === 'redis') {
                $start = microtime(true);
                Redis::ping();
                $responseTime = round((microtime(true) - $start) * 1000, 2);
                
                return [
                    'status' => 'healthy',
                    'message' => 'Redis is connected',
                    'response_time_ms' => $responseTime
                ];
            } else {
                return [
                    'status' => 'skipped',
                    'message' => 'Redis not configured'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'message' => 'Redis connection failed: ' . $e->getMessage()
            ];
        }
    }
}














