<?php

namespace App\Constants;

/**
 * Application-wide constants
 */
class AppConstants
{
    // User Roles
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MAIN_ADMIN = 'main_admin';
    public const ROLE_SECTORAL_ADMIN = 'sectoral_admin';
    public const ROLE_INSTITUTIONAL_ADMIN = 'institutional_admin';
    public const ROLE_COMPANY = 'company';

    // Verification Status
    public const VERIFICATION_PENDING = 'pending';
    public const VERIFICATION_VERIFIED = 'verified';
    public const VERIFICATION_REJECTED = 'rejected';

    // Submission Status
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_UNDER_REVIEW = 'under_review';

    // Company Approval Status
    public const APPROVAL_PENDING = 'pending';
    public const APPROVAL_APPROVED = 'approved';
    public const APPROVAL_REJECTED = 'rejected';

    // File Upload
    public const MAX_FILE_SIZE = 10240; // 10MB in KB
    public const ALLOWED_IMAGE_TYPES = ['jpg', 'jpeg', 'png', 'gif'];
    public const ALLOWED_DOCUMENT_TYPES = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];

    // Pagination
    public const DEFAULT_PER_PAGE = 15;
    public const DASHBOARD_PER_PAGE = 10;

    // Cache Keys
    public const CACHE_DASHBOARD_STATS = 'dashboard_stats_';
    public const CACHE_SUBMISSIONS_COUNT = 'submissions_count_';
    public const CACHE_TTL = 3600; // 1 hour

    // Rate Limiting
    public const RATE_LIMIT_FORM_SUBMISSION = 10; // per minute
    public const RATE_LIMIT_LOGIN = 5; // per minute
    public const RATE_LIMIT_API = 60; // per minute

    // Date Formats
    public const DATE_FORMAT = 'Y-m-d';
    public const DATETIME_FORMAT = 'Y-m-d H:i:s';
    public const DISPLAY_DATE_FORMAT = 'd/m/Y';
    public const DISPLAY_DATETIME_FORMAT = 'd/m/Y H:i';

    // Currency
    public const CURRENCY_MAD = 'MAD';
    public const CURRENCY_EUR = 'EUR';
    public const CURRENCY_USD = 'USD';

    // Business Types
    public const BUSINESS_TYPE_COMMERCE = 'commerce';
    public const BUSINESS_TYPE_SERVICE = 'service';
    public const BUSINESS_TYPE_ARTISANAT = 'artisanat';
    public const BUSINESS_TYPE_PROFESSION_LIBERALE = 'profession_liberale';
    public const BUSINESS_TYPE_AUTRE = 'autre';

    // Project Types
    public const PROJECT_TYPE_SOCIAL = 'social';
    public const PROJECT_TYPE_ECONOMIC = 'economic';
    public const PROJECT_TYPE_ENVIRONMENTAL = 'environmental';
    public const PROJECT_TYPE_CULTURAL = 'cultural';
    public const PROJECT_TYPE_EDUCATIONAL = 'educational';
    public const PROJECT_TYPE_HEALTH = 'health';
    public const PROJECT_TYPE_INFRASTRUCTURE = 'infrastructure';
    public const PROJECT_TYPE_AGRICULTURE = 'agriculture';
    public const PROJECT_TYPE_AUTRE = 'autre';

    // Training Types
    public const TRAINING_TYPE_TECHNICAL = 'technical';
    public const TRAINING_TYPE_BUSINESS = 'business';
    public const TRAINING_TYPE_SOFT_SKILLS = 'soft_skills';
    public const TRAINING_TYPE_CERTIFICATION = 'certification';
    public const TRAINING_TYPE_WORKSHOP = 'workshop';
    public const TRAINING_TYPE_SEMINAR = 'seminar';
    public const TRAINING_TYPE_LANGUAGE = 'language';
    public const TRAINING_TYPE_DIGITAL_SKILLS = 'digital_skills';
    public const TRAINING_TYPE_ENTREPRENEURSHIP = 'entrepreneurship';

    // Investment Types
    public const INVESTMENT_TYPE_EQUITY = 'equity';
    public const INVESTMENT_TYPE_DEBT = 'debt';
    public const INVESTMENT_TYPE_GRANT = 'grant';
    public const INVESTMENT_TYPE_LOAN = 'loan';

    // Funding Sources
    public const FUNDING_SOURCE_PERSONAL_SAVINGS = 'personal_savings';
    public const FUNDING_SOURCE_FAMILY_LOAN = 'family_loan';
    public const FUNDING_SOURCE_BANK_LOAN = 'bank_loan';
    public const FUNDING_SOURCE_INVESTOR = 'investor';
    public const FUNDING_SOURCE_GOVERNMENT_SUPPORT = 'government_support';
    public const FUNDING_SOURCE_AUTRE = 'autre';

    // Notification Types
    public const NOTIFICATION_TYPE_SUBMISSION = 'submission';
    public const NOTIFICATION_TYPE_APPROVAL = 'approval';
    public const NOTIFICATION_TYPE_REJECTION = 'rejection';
    public const NOTIFICATION_TYPE_SYSTEM = 'system';

    // Notification Priority
    public const PRIORITY_LOW = 'low';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_URGENT = 'urgent';

    // Log Levels
    public const LOG_LEVEL_INFO = 'info';
    public const LOG_LEVEL_WARNING = 'warning';
    public const LOG_LEVEL_ERROR = 'error';
    public const LOG_LEVEL_CRITICAL = 'critical';

    // Log Actions
    public const LOG_ACTION_LOGIN = 'login';
    public const LOG_ACTION_LOGOUT = 'logout';
    public const LOG_ACTION_SUBMISSION = 'submission';
    public const LOG_ACTION_APPROVAL = 'approval';
    public const LOG_ACTION_REJECTION = 'rejection';
    public const LOG_ACTION_FILE_UPLOAD = 'file_upload';
    public const LOG_ACTION_PROFILE_UPDATE = 'profile_update';

    /**
     * Get all user roles
     */
    public static function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
            self::ROLE_MAIN_ADMIN,
            self::ROLE_SECTORAL_ADMIN,
            self::ROLE_INSTITUTIONAL_ADMIN,
            self::ROLE_COMPANY,
        ];
    }

    /**
     * Get all submission statuses
     */
    public static function getSubmissionStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
            self::STATUS_UNDER_REVIEW,
        ];
    }

    /**
     * Get all verification statuses
     */
    public static function getVerificationStatuses(): array
    {
        return [
            self::VERIFICATION_PENDING,
            self::VERIFICATION_VERIFIED,
            self::VERIFICATION_REJECTED,
        ];
    }

    /**
     * Get all currencies
     */
    public static function getCurrencies(): array
    {
        return [
            self::CURRENCY_MAD,
            self::CURRENCY_EUR,
            self::CURRENCY_USD,
        ];
    }
}














