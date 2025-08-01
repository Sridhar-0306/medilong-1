<?php
/**
 * Simple Form Handler for Hospital Booking
 * Processes form submission from HTML page
 */

require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $booking = new HospitalBooking();
    
    // Get form data
    $patientData = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'address' => $_POST['address'] ?? '',
        'age' => $_POST['age'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'medical_condition' => $_POST['medical-condition'] ?? '', // Note: HTML uses hyphen
        'appointment_time' => $_POST['appointment_time'] ?? '',
        'selected_department' => $_POST['department'] ?? '', // Note: HTML uses 'department'
        'selected_doctor' => $_POST['doctor'] ?? '',
        'doctor_specialty' => $_POST['doctor_specialty'] ?? ''
    ];
    
    // Save booking
    $result = $booking->saveBooking($patientData);
    
    if ($result['success']) {
        // Success - show success page
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Booking Successful</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .success { background: #d4edda; padding: 20px; border-radius: 5px; border: 1px solid #c3e6cb; }
                .booking-ref { font-size: 20px; font-weight: bold; color: #155724; }
            </style>
        </head>
        <body>
            <div class="success">
                <h2>✅ Appointment Booked Successfully!</h2>
                <p class="booking-ref">Your Booking Reference: <?php echo $result['booking_reference']; ?></p>
                <p>Thank you, <?php echo htmlspecialchars($patientData['name']); ?>!</p>
                <p>Your appointment has been booked for <?php echo htmlspecialchars($patientData['appointment_time']); ?></p>
                <p>Department: <?php echo htmlspecialchars($patientData['selected_department']); ?></p>
                <p>Please save your booking reference for future use.</p>
                <a href="booking-appointment.html">Book Another Appointment</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Error - show error page
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Booking Error</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .error { background: #f8d7da; padding: 20px; border-radius: 5px; border: 1px solid #f5c6cb; }
            </style>
        </head>
        <body>
            <div class="error">
                <h2>❌ Booking Failed</h2>
                <p><?php echo htmlspecialchars($result['message']); ?></p>
                <a href="booking-appointment.html">Try Again</a>
            </div>
        </body>
        </html>
        <?php
    }
    
} else {
    // Not a POST request
    header('Location: booking-appointment.html');
    exit;
}

?>