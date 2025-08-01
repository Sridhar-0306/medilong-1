// JavaScript to connect HTML form to PHP backend
// Add this script to your booking-appointment.html file

// Update the form submission function
document.addEventListener('DOMContentLoaded', function() {
    
    // Override the existing submit button functionality
    const submitBtn = document.getElementById('submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            submitFormToDatabase();
        });
    }
    
    function submitFormToDatabase() {
        // Collect all form data
        const formData = new FormData();
        
        // Patient details
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('age', document.getElementById('age').value);
        formData.append('gender', document.getElementById('gender').value);
        
        // Medical condition
        formData.append('medical-condition', document.getElementById('medical-condition').value);
        
        // Appointment details
        formData.append('appointment_time', selectedTime);
        formData.append('department', document.getElementById('department').value);
        
        // Doctor details (if selected)
        if (selectedDoctor) {
            formData.append('doctor', selectedDoctor.name);
            formData.append('doctor_specialty', selectedDoctor.specialty);
        }
        
        // Submit to PHP backend
        fetch('form-handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Redirect to the response page (success or error)
                window.location.href = 'form-handler.php?' + new URLSearchParams(formData);
            } else {
                throw new Error('Network response was not ok');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting your booking. Please try again.');
        });
    }
    
    // Alternative AJAX submission (returns JSON)
    function submitWithAjax() {
        const formData = new FormData();
        
        // Add form data
        formData.append('action', 'book_appointment');
        formData.append('name', document.getElementById('name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('age', document.getElementById('age').value);
        formData.append('gender', document.getElementById('gender').value);
        formData.append('medical_condition', document.getElementById('medical-condition').value);
        formData.append('appointment_time', selectedTime);
        formData.append('selected_department', document.getElementById('department').value);
        
        if (selectedDoctor) {
            formData.append('selected_doctor', selectedDoctor.name);
            formData.append('doctor_specialty', selectedDoctor.specialty);
        }
        
        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`✅ Booking Successful!\nReference: ${data.booking_reference}`);
                // Optionally redirect or reset form
                location.reload();
            } else {
                alert(`❌ Booking Failed: ${data.message}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
    
    // Test database connection function
    function testConnection() {
        fetch('api.php?action=test_connection')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Database connection successful');
            } else {
                console.log('❌ Database connection failed');
            }
        })
        .catch(error => {
            console.error('Connection test error:', error);
        });
    }
    
    // Call test connection on page load (optional)
    // testConnection();
    
});