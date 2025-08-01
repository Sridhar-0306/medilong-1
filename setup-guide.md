# Hospital Booking System - Complete Backend Setup Guide

## Overview
This guide provides a complete PHP backend to connect your HTML booking form to your FreeSQLDatabase MySQL database.

## Your Database Details
- **Host**: sql3.freesqldatabase.com
- **Database**: sql3793147
- **Username**: sql3793147
- **Password**: TUhmBbWKwT
- **Port**: 3306

## Files You Need

### 1. Core PHP Files
- `config.php` - Database configuration
- `database.php` - Database connection class
- `functions.php` - Booking system functions
- `form-handler.php` - Processes form submissions
- `api.php` - API endpoints for AJAX
- `test-connection.php` - Test database connection

### 2. Optional Files
- `booking-backend.js` - JavaScript to connect form to backend

## Installation Steps

### Step 1: Upload Files
1. Upload all PHP files to your web server in the same folder as `booking-appointment.html`
2. Make sure all files have proper permissions (644)

### Step 2: Test Database Connection
1. Visit `http://your-domain.com/test-connection.php`
2. You should see "✅ SYSTEM READY!" message
3. If you see errors, check your database credentials

### Step 3: Update Your HTML Form
Add this to the bottom of your `booking-appointment.html` file, just before `</body>`:

```html
<script>
// Override the existing submit function
document.getElementById('submit-btn').addEventListener('click', function() {
    // Collect form data
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('phone', document.getElementById('phone').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('age', document.getElementById('age').value);
    formData.append('gender', document.getElementById('gender').value);
    formData.append('medical-condition', document.getElementById('medical-condition').value);
    formData.append('appointment_time', selectedTime);
    formData.append('department', document.getElementById('department').value);
    
    if (selectedDoctor) {
        formData.append('doctor', selectedDoctor.name);
        formData.append('doctor_specialty', selectedDoctor.specialty);
    }
    
    // Submit to backend
    fetch('form-handler.php', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            window.location.href = response.url;
        }
    }).catch(error => {
        alert('Error: ' + error.message);
    });
});
</script>
```

## How It Works

### Form Submission Flow
1. User fills out the booking form
2. JavaScript collects form data
3. Data is sent to `form-handler.php`
4. PHP validates and saves data to database
5. User sees success/error page with booking reference

### Database Structure
The system uses a single table `patient_bookings` with these fields:
- Patient info (name, email, phone, address, age, gender)
- Medical condition
- Appointment details (time, department, doctor)
- Booking reference and status
- Creation timestamp

### Security Features
- ✅ SQL injection protection (prepared statements)
- ✅ Input validation and sanitization
- ✅ Error logging
- ✅ Secure database connections

## API Endpoints

You can also use these API endpoints for advanced features:

### POST /api.php
- `action=book_appointment` - Save new booking
- `action=update_status` - Update booking status

### GET /api.php
- `action=get_booking&reference=APT123` - Get booking details
- `action=search_bookings&search=email@domain.com` - Search bookings
- `action=get_all_bookings` - Get all bookings (admin)
- `action=test_connection` - Test database connection

## Testing Your Setup

### 1. Test Database Connection
Visit: `http://your-domain.com/test-connection.php`

### 2. Test Booking Form
1. Fill out your booking form
2. Submit it
3. You should see a success page with booking reference
4. Check your database to confirm the record was saved

### 3. Test API Endpoints
Visit: `http://your-domain.com/api.php?action=test_connection`
Should return: `{"success":true,"message":"Database connection successful!"}`

## Troubleshooting

### Common Issues:

**1. Database Connection Failed**
- Check your database credentials in `config.php`
- Ensure your hosting supports external MySQL connections
- Verify FreeSQLDatabase is accessible from your host

**2. Table Doesn't Exist**
Run this SQL in phpMyAdmin:
```sql
CREATE TABLE patient_bookings (
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
);
```

**3. Form Not Submitting**
- Check browser console for JavaScript errors
- Ensure all required form fields have values
- Verify `form-handler.php` is accessible

**4. Permission Errors**
Set file permissions:
```bash
chmod 644 *.php
chmod 644 *.html
```

## Next Steps

### Enhancements You Can Add:
1. **Email Notifications**: Send confirmation emails to patients
2. **Admin Panel**: Create a dashboard to manage bookings
3. **Calendar Integration**: Show available/booked time slots
4. **Payment Integration**: Add payment processing
5. **SMS Notifications**: Send SMS confirmations

### Admin Features:
- View all bookings: `/api.php?action=get_all_bookings`
- Search bookings: `/api.php?action=search_bookings&search=email`
- Update status: Use the update_status endpoint

## Support
If you encounter issues:
1. Check the `test-connection.php` page first
2. Look at your server's error logs
3. Ensure your hosting supports PHP 7.4+ and PDO MySQL

## File Summary
- ✅ `config.php` - Database credentials
- ✅ `database.php` - Secure PDO connection
- ✅ `functions.php` - Booking logic
- ✅ `form-handler.php` - Form processor
- ✅ `api.php` - API endpoints
- ✅ `test-connection.php` - Connection tester

Your hospital booking system is now ready to save real appointments to your FreeSQLDatabase!