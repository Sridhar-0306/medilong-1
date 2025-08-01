<?php
/**
 * API Endpoints for Hospital Booking System
 * Handles form submissions and AJAX requests
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once 'functions.php';

try {
    $booking = new HospitalBooking();
    $action = $_GET['action'] ?? $_POST['action'] ?? '';
    
    switch ($action) {
        
        case 'book_appointment':
            // Get form data
            $patientData = [
                'name' => $_POST['name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'age' => $_POST['age'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'medical_condition' => $_POST['medical_condition'] ?? '',
                'appointment_time' => $_POST['appointment_time'] ?? '',
                'selected_department' => $_POST['selected_department'] ?? '',
                'selected_doctor' => $_POST['selected_doctor'] ?? '',
                'doctor_specialty' => $_POST['doctor_specialty'] ?? ''
            ];
            
            $result = $booking->saveBooking($patientData);
            echo json_encode($result);
            break;
            
        case 'get_booking':
            $reference = $_GET['reference'] ?? '';
            if (empty($reference)) {
                throw new Exception('Booking reference is required');
            }
            
            $result = $booking->getBookingByReference($reference);
            echo json_encode($result);
            break;
            
        case 'update_status':
            $bookingId = $_POST['booking_id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($bookingId) || empty($status)) {
                throw new Exception('Booking ID and status are required');
            }
            
            $result = $booking->updateBookingStatus($bookingId, $status);
            echo json_encode($result);
            break;
            
        case 'search_bookings':
            $searchTerm = $_GET['search'] ?? '';
            if (empty($searchTerm)) {
                throw new Exception('Search term is required');
            }
            
            $result = $booking->searchBookings($searchTerm);
            echo json_encode($result);
            break;
            
        case 'get_all_bookings':
            $limit = (int)($_GET['limit'] ?? 50);
            $offset = (int)($_GET['offset'] ?? 0);
            
            $result = $booking->getAllBookings($limit, $offset);
            echo json_encode($result);
            break;
            
        case 'test_connection':
            $db = new Database();
            $connected = $db->testConnection();
            echo json_encode([
                'success' => $connected,
                'message' => $connected ? 'Database connection successful!' : 'Database connection failed!'
            ]);
            break;
            
        default:
            throw new Exception('Invalid action specified');
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

?>