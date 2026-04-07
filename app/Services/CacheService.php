<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    /**
     * Cache duration in seconds
     */
    const CACHE_DURATION = 3600; // 1 hour
    const SHORT_CACHE_DURATION = 300; // 5 minutes
    const LONG_CACHE_DURATION = 86400; // 24 hours

    /**
     * Cache key prefixes
     */
    const USER_PREFIX = 'user:';
    const COMPANY_PREFIX = 'company:';
    const SUBMISSION_PREFIX = 'submission:';
    const STATISTICS_PREFIX = 'stats:';
    const DASHBOARD_PREFIX = 'dashboard:';

    /**
     * Cache a database query result
     */
    public static function remember(string $key, int $duration, callable $callback)
    {
        return Cache::remember($key, $duration, $callback);
    }

    /**
     * Cache user data
     */
    public static function cacheUser(int $userId, callable $callback)
    {
        $key = self::USER_PREFIX . $userId;
        return self::remember($key, self::CACHE_DURATION, $callback);
    }

    /**
     * Cache company data
     */
    public static function cacheCompany(int $companyId, callable $callback)
    {
        $key = self::COMPANY_PREFIX . $companyId;
        return self::remember($key, self::CACHE_DURATION, $callback);
    }

    /**
     * Cache submission data
     */
    public static function cacheSubmission(string $type, int $id, callable $callback)
    {
        $key = self::SUBMISSION_PREFIX . $type . ':' . $id;
        return self::remember($key, self::CACHE_DURATION, $callback);
    }

    /**
     * Cache statistics data
     */
    public static function cacheStatistics(string $type, callable $callback)
    {
        $key = self::STATISTICS_PREFIX . $type;
        return self::remember($key, self::SHORT_CACHE_DURATION, $callback);
    }

    /**
     * Cache dashboard data
     */
    public static function cacheDashboard(string $role, int $userId, callable $callback)
    {
        $key = self::DASHBOARD_PREFIX . $role . ':' . $userId;
        return self::remember($key, self::SHORT_CACHE_DURATION, $callback);
    }

    /**
     * Invalidate user cache
     */
    public static function invalidateUser(int $userId)
    {
        Cache::forget(self::USER_PREFIX . $userId);
    }

    /**
     * Invalidate company cache
     */
    public static function invalidateCompany(int $companyId)
    {
        Cache::forget(self::COMPANY_PREFIX . $companyId);
    }

    /**
     * Invalidate submission cache
     */
    public static function invalidateSubmission(string $type, int $id)
    {
        Cache::forget(self::SUBMISSION_PREFIX . $type . ':' . $id);
    }

    /**
     * Invalidate statistics cache
     */
    public static function invalidateStatistics(string $type = null)
    {
        if ($type) {
            Cache::forget(self::STATISTICS_PREFIX . $type);
        } else {
            // Clear all statistics cache
            $keys = Redis::keys(self::STATISTICS_PREFIX . '*');
            if (!empty($keys)) {
                Redis::del($keys);
            }
        }
    }

    /**
     * Invalidate dashboard cache
     */
    public static function invalidateDashboard(string $role = null, int $userId = null)
    {
        if ($role && $userId) {
            Cache::forget(self::DASHBOARD_PREFIX . $role . ':' . $userId);
        } else {
            // Clear all dashboard cache
            $keys = Redis::keys(self::DASHBOARD_PREFIX . '*');
            if (!empty($keys)) {
                Redis::del($keys);
            }
        }
    }

    /**
     * Clear all cache
     */
    public static function clearAll()
    {
        Cache::flush();
    }

    /**
     * Get cache statistics
     */
    public static function getCacheStats()
    {
        try {
            $info = Redis::info();
            return [
                'used_memory' => $info['used_memory_human'] ?? 'N/A',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => isset($info['keyspace_hits'], $info['keyspace_misses']) 
                    ? round($info['keyspace_hits'] / ($info['keyspace_hits'] + $info['keyspace_misses']) * 100, 2)
                    : 0
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Redis not available',
                'message' => $e->getMessage()
            ];
        }
    }
}






























