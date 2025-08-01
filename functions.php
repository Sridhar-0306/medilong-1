<?php
/**
 * Hospital Booking System Functions
 * Core functions for patient booking management
 */

require_once 'database.php';

class HospitalBooking {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
        $this->db->connect();
    }
    
    /**
     * Generate unique booking reference
     */
    private function generateBookingReference() {
        $prefix = 'APT';
        $timestamp = date('YmdHis');
        $random = sprintf('%04d', mt_rand(1000, 9999));
        return $prefix . $timestamp . $random;
    }
    
    /**
     * Validate patient data
     */
    private function validatePatientData($data) {
        $errors = [];
        
        // Required fields
        $required = ['name', 'email', 'phone', 'address', 'age', 'gender', 
                    'medical_condition', 'appointment_time', 'selected_department'];
        
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
            }
        }
        
        // Email validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Age validation
        if (!empty($data['age']) && (!is_numeric($data['age']) || $data['age'] < 1 || $data['age'] > 120)) {
            $errors[] = "Age must be between 1 and 120";
        }
        
        // Phone validation (basic)
        if (!empty($data['phone']) && !preg_match('/^[0-9+\\-\\s()]{10,15}$/', $data['phone'])) {
            $errors[] = "Invalid phone number format";
        }
        
        return $errors;
    }
    
    /**
     * Save patient booking to database
     */
    public function saveBooking($patientData) {
        try {
            // Validate data
            $errors = $this->validatePatientData($patientData);
            if (!empty($errors)) {
                return [
                    'success' => false,
                    'message' => 'Validation errors: ' . implode(', ', $errors)
                ];
            }
            
            // Generate booking reference
            $bookingReference = $this->generateBookingReference();
            
            // Prepare SQL query
            $sql = "INSERT INTO patient_bookings (
                patient_name, patient_email, patient_phone, patient_address, 
                patient_age, patient_gender, medical_condition, appointment_time, 
                selected_department, selected_doctor, doctor_specialty, 
                booking_reference, booking_status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
            
            $params = [
                trim($patientData['name']),
                trim($patientData['email']),
                trim($patientData['phone']),
                trim($patientData['address']),
                (int)$patientData['age'],
                $patientData['gender'],
                trim($patientData['medical_condition']),
                $patientData['appointment_time'],
                $patientData['selected_department'],
                $patientData['selected_doctor'] ?? '',
                $patientData['doctor_specialty'] ?? '',
                $bookingReference
            ];
            
            // Execute query
            $this->db->query($sql, $params);
            $bookingId = $this->db->lastInsertId();
            
            return [
                'success' => true,
                'message' => 'Appointment booked successfully!',
                'booking_id' => $bookingId,
                'booking_reference' => $bookingReference
            ];
            
        } catch (Exception $e) {
            error_log("Booking Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to save booking. Please try again.'
            ];
        }
    }
    
    /**
     * Get booking by reference number
     */
    public function getBookingByReference($reference) {
        try {
            $sql = "SELECT * FROM patient_bookings WHERE booking_reference = ?";
            $booking = $this->db->fetchOne($sql, [$reference]);
            
            if ($booking) {
                return [
                    'success' => true,
                    'data' => $booking
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Booking not found'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Get Booking Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve booking'
            ];
        }
    }
    
    /**
     * Update booking status
     */
    public function updateBookingStatus($bookingId, $status) {
        try {
            $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                return [
                    'success' => false,
                    'message' => 'Invalid status'
                ];
            }
            
            $sql = "UPDATE patient_bookings SET booking_status = ? WHERE id = ?";
            $this->db->query($sql, [$status, $bookingId]);
            
            return [
                'success' => true,
                'message' => 'Booking status updated'
            ];
            
        } catch (Exception $e) {
            error_log("Update Status Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update status'
            ];
        }
    }
    
    /**
     * Get all bookings (for admin)
     */
    public function getAllBookings($limit = 50, $offset = 0) {
        try {
            $sql = "SELECT * FROM patient_bookings ORDER BY created_at DESC LIMIT ? OFFSET ?";
            $bookings = $this->db->fetchAll($sql, [$limit, $offset]);
            
            return [
                'success' => true,
                'data' => $bookings
            ];
            
        } catch (Exception $e) {
            error_log("Get All Bookings Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to retrieve bookings'
            ];
        }
    }
    
    /**
     * Search bookings by email or phone
     */
    public function searchBookings($searchTerm) {
        try {
            $sql = "SELECT * FROM patient_bookings 
                    WHERE patient_email LIKE ? OR patient_phone LIKE ? 
                    ORDER BY created_at DESC";
            $searchPattern = '%' . $searchTerm . '%';
            $bookings = $this->db->fetchAll($sql, [$searchPattern, $searchPattern]);
            
            return [
                'success' => true,
                'data' => $bookings
            ];
            
        } catch (Exception $e) {
            error_log("Search Bookings Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Search failed'
            ];
        }
    }
}

?>