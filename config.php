<?php
/**
 * Database Configuration for Hospital Appointment Booking System
 * Updated FreeSQLDatabase Connection Settings
 */

// Database connection parameters
define('DB_HOST', 'sql3.freesqldatabase.com');
define('DB_PORT', '3306');
define('DB_NAME', 'sql3793147');
define('DB_USER', 'sql3793147');
define('DB_PASS', 'TUhmBbWKwT');

// Application settings
define('APP_NAME', 'Hospital Appointment Booking System');
define('APP_VERSION', '1.0');
define('TIMEZONE', 'Asia/Kolkata');

// Security settings
define('ENCRYPT_KEY', 'hospital-booking-2025-secure-key-' . mt_rand(1000, 9999));
define('SESSION_LIFETIME', 3600);

// Set timezone
date_default_timezone_set(TIMEZONE);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>