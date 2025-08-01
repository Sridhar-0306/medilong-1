<?php
/**
 * Database Connection Test
 * Verify FreeSQLDatabase connection works
 */

require_once 'config.php';
require_once 'database.php';

echo "<h2>🏥 Hospital Booking System - Database Test</h2>";
echo "<hr>";

try {
    // Test 1: Basic Connection
    echo "<h3>Test 1: Database Connection</h3>";
    $db = new Database();
    $connected = $db->testConnection();
    
    if ($connected) {
        echo "✅ <strong>SUCCESS:</strong> Connected to FreeSQLDatabase<br>";
        echo "📍 Host: " . DB_HOST . "<br>";
        echo "🗄️ Database: " . DB_NAME . "<br>";
        echo "👤 User: " . DB_USER . "<br>";
        echo "📊 Port: " . DB_PORT . "<br><br>";
    } else {
        echo "❌ <strong>FAILED:</strong> Could not connect to database<br><br>";
        exit;
    }
    
    // Test 2: Check if patient_bookings table exists
    echo "<h3>Test 2: Table Check</h3>";
    try {
        $result = $db->query("SELECT COUNT(*) as count FROM patient_bookings");
        $count = $result->fetch();
        echo "✅ Table 'patient_bookings' exists with {$count['count']} records<br><br>";
    } catch (Exception $e) {
        echo "❌ Table 'patient_bookings' does not exist<br>";
        echo "Please create the table using this SQL:<br>";
        echo "<textarea rows='15' cols='80' readonly>";
        echo "CREATE TABLE patient_bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    patient_email VARCHAR(100) NOT NULL,
    patient_phone VARCHAR(20) NOT NULL,
    patient_address TEXT NOT NULL,
    patient_age INT NOT NULL,
    patient_gender ENUM('male', 'female', 'other', 'prefer-not-to-say') NOT NULL,
    medical_condition TEXT NOT NULL,
    appointment_time VARCHAR(10) NOT NULL,
    selected_department VARCHAR(50) NOT NULL,
    selected_doctor VARCHAR(100),
    doctor_specialty VARCHAR(100),
    booking_reference VARCHAR(20) UNIQUE,
    booking_status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
        echo "</textarea><br><br>";
        exit;
    }
    
    // Test 3: Test Booking Functions
    echo "<h3>Test 3: Booking System Test</h3>";
    require_once 'functions.php';
    
    $booking = new HospitalBooking();
    echo "✅ HospitalBooking class loaded successfully<br>";
    
    // Test with sample data
    $testData = [
        'name' => 'Test Patient',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'age' => 30,
        'gender' => 'male',
        'medical_condition' => 'Test condition',
        'appointment_time' => '10:00 AM',
        'selected_department' => 'general-medicine',
        'selected_doctor' => 'Dr. Test',
        'doctor_specialty' => 'General Medicine'
    ];
    
    echo "📝 Testing with sample data...<br>";
    $result = $booking->saveBooking($testData);
    
    if ($result['success']) {
        echo "✅ <strong>SUCCESS:</strong> Test booking saved successfully!<br>";
        echo "📋 Booking Reference: {$result['booking_reference']}<br>";
        echo "🆔 Booking ID: {$result['booking_id']}<br><br>";
        
        // Clean up test data
        $db->query("DELETE FROM patient_bookings WHERE id = ?", [$result['booking_id']]);
        echo "🧹 Test data cleaned up<br><br>";
    } else {
        echo "❌ <strong>ERROR:</strong> {$result['message']}<br><br>";
    }
    
    // Test 4: Configuration Check
    echo "<h3>Test 4: Configuration Check</h3>";
    echo "🕐 Timezone: " . date_default_timezone_get() . "<br>";
    echo "📅 Current Date/Time: " . date('Y-m-d H:i:s') . "<br>";
    echo "🔐 Encryption Key Set: " . (defined('ENCRYPT_KEY') ? 'Yes' : 'No') . "<br>";
    echo "📝 PHP Version: " . phpversion() . "<br>";
    echo "🗄️ PDO MySQL: " . (extension_loaded('pdo_mysql') ? 'Available' : 'NOT Available') . "<br><br>";
    
    // Final Status
    echo "<h3>🎉 Overall Status</h3>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; color: #155724;'>";
    echo "<strong>✅ SYSTEM READY!</strong><br>";
    echo "Your hospital booking system is properly configured.<br>";
    echo "You can now use the booking form to save appointments to the database.";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "<strong>❌ ERROR:</strong><br>";
    echo "Error: " . $e->getMessage();
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Test completed at: " . date('Y-m-d H:i:s') . "</small></p>";

?>