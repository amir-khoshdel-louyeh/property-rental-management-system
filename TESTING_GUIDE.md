# Testing Guide for User Authentication System

This guide will help you test the user authentication system.

## Prerequisites

1. **Database Setup**
   - Run the database setup script: `http://localhost/your-project/config/setup_database.php`
   - This will create the `users` table with the correct structure
   - Verify the table was created successfully

2. **Web Server**
   - Ensure your web server (Apache/Nginx) is running
   - PHP version 7.4 or higher recommended
   - MySQL/MariaDB database server running

## Test Cases

### 1. User Registration

**Test Case 1.1: Successful Registration**
- Navigate to: `http://localhost/your-project/public/register.php`
- Fill in:
  - Username: `testuser123`
  - Email: `test@example.com`
  - Password: `password123`
  - Confirm Password: `password123`
- Click "Register"
- Expected: Redirect to login page with success message

**Test Case 1.2: Duplicate Username**
- Try registering with the same username again
- Expected: Error message "Username already exists"

**Test Case 1.3: Duplicate Email**
- Try registering with a different username but same email
- Expected: Error message "Email already exists"

**Test Case 1.4: Password Mismatch**
- Fill in all fields but use different passwords
- Expected: Error message "Passwords do not match"

**Test Case 1.5: Invalid Email**
- Use invalid email format (e.g., `notanemail`)
- Expected: Error message "Invalid email format"

**Test Case 1.6: Short Username**
- Use username with less than 3 characters
- Expected: Error message about username length

**Test Case 1.7: Short Password**
- Use password with less than 6 characters
- Expected: Error message about password length

### 2. User Login

**Test Case 2.1: Successful Login**
- Navigate to: `http://localhost/your-project/public/login.php`
- Use credentials from Test Case 1.1
- Expected: Redirect to homepage with welcome message

**Test Case 2.2: Invalid Username**
- Use non-existent username
- Expected: Error message "Invalid username or password"

**Test Case 2.3: Invalid Password**
- Use correct username but wrong password
- Expected: Error message "Invalid username or password"

**Test Case 2.4: Empty Fields**
- Submit form with empty fields
- Expected: Error message "Please fill in all fields"

**Test Case 2.5: Rate Limiting**
- Attempt to login with wrong password 5+ times
- Expected: Error message about too many attempts with lockout time

### 3. Session Management

**Test Case 3.1: Logged In State**
- After successful login, check header navigation
- Expected: See "Profile" link, welcome message, and "Logout" link

**Test Case 3.2: Protected Pages**
- Try to access `profile.php` without logging in
- Expected: Redirect to login page with error message

**Test Case 3.3: Logout**
- Click "Logout" link
- Expected: Redirect to login page with success message
- Verify header no longer shows profile/welcome/logout

### 4. User Profile

**Test Case 4.1: View Profile**
- Login and navigate to profile page
- Expected: See user information (username, email, dates, status)

**Test Case 4.2: Profile Data Accuracy**
- Verify displayed information matches registration data
- Check "Account Status" shows "Active"

### 5. Change Password

**Test Case 5.1: Successful Password Change**
- Navigate to profile, click "Change Password"
- Fill in:
  - Current Password: `password123`
  - New Password: `newpassword123`
  - Confirm New Password: `newpassword123`
- Expected: Redirect to profile with success message

**Test Case 5.2: Wrong Current Password**
- Use incorrect current password
- Expected: Error message "Current password is incorrect"

**Test Case 5.3: New Password Mismatch**
- Use mismatched new passwords
- Expected: Error message "New passwords do not match"

**Test Case 5.4: Test New Password**
- Logout and login with new password
- Expected: Successful login

### 6. CSRF Protection

**Test Case 6.1: Missing CSRF Token**
- Open browser developer tools
- Remove CSRF token from form before submitting
- Expected: Error message about invalid security token

### 7. Security Features

**Test Case 7.1: Session Regeneration**
- Check that session ID changes after login (compare cookies before/after)
- Expected: Different session ID after login

**Test Case 7.2: XSS Prevention**
- Try registering with username: `<script>alert('XSS')</script>`
- Expected: Script should be escaped and not executed

**Test Case 7.3: SQL Injection Prevention**
- Try logging in with username: `admin' OR '1'='1`
- Expected: Login fails safely without SQL errors

## Common Issues and Solutions

### Issue 1: "Database connection failed"
- **Solution**: Check database credentials in `config/Database_Manager.php`
- Verify database server is running

### Issue 2: "Headers already sent" error
- **Solution**: Ensure no output before session_start() or header() calls
- Check for BOM in PHP files

### Issue 3: CSRF token errors
- **Solution**: Clear browser cookies and cache
- Ensure session is started properly

### Issue 4: CSS not loading
- **Solution**: Check file paths in HTML
- Verify css/ directory exists in public/

### Issue 5: Rate limiting not resetting
- **Solution**: Clear session or wait 15 minutes
- Or manually clear session data

## Manual Database Testing

```sql
-- View all users
SELECT user_id, username, email, created_at, last_login, is_active FROM users;

-- Check specific user
SELECT * FROM users WHERE username = 'testuser123';

-- Reset password attempts (if needed)
-- Session data is stored in PHP sessions, not database

-- Deactivate user
UPDATE users SET is_active = FALSE WHERE username = 'testuser123';

-- Activate user
UPDATE users SET is_active = TRUE WHERE username = 'testuser123';
```

## Performance Testing

1. **Load Test**: Try registering multiple users quickly
2. **Session Test**: Open multiple browser tabs and test concurrent sessions
3. **Rate Limit Test**: Verify rate limiting works across multiple attempts

## Security Checklist

- ✅ Passwords are hashed (never stored as plain text)
- ✅ CSRF protection on all forms
- ✅ Rate limiting on login
- ✅ Session regeneration on login
- ✅ Input validation and sanitization
- ✅ XSS protection (htmlspecialchars)
- ✅ SQL injection protection (prepared statements)
- ✅ Session timeout on inactivity
- ✅ Secure session cookies

## Browser Compatibility

Test on:
- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)

## Notes

- Default session timeout: 30 minutes of inactivity
- Default login rate limit: 5 attempts per 15 minutes
- Minimum password length: 6 characters
- Username: 3-50 characters (alphanumeric, underscore, hyphen)
