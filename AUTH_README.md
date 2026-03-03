# User Authentication System

This property rental management system now includes a complete user authentication system.

## Features

### User Registration
- Username validation (3-50 characters)
- Email validation
- Password validation (minimum 6 characters)
- Password confirmation
- Duplicate username/email checking
- Secure password hashing using PHP's `password_hash()`

### User Login
- Username and password authentication
- Secure password verification using `password_verify()`
- Session management
- Session regeneration for security
- Last login tracking
- Account status checking (active/inactive)

### Session Management
- Session start/check utilities
- User session storage (user_id, username, email)
- Session timeout (30 minutes of inactivity)
- Session regeneration for security
- Flash message system for user feedback
- Protected pages requiring authentication

### User Profile
- View user information
- Display username, email, member since date, last login
- Account status display
- Password change functionality

### Password Management
- Change password with current password verification
- Password strength validation
- Secure password update

### User Account Management
- Deactivate user account
- Activate user account
- Get user by ID
- Get user by username
- Update user email

## Database Schema

```sql
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
);
```

## Installation

1. Run the SQL schema in your database:
```bash
mysql -u your_user -p your_database < config/schema_users.sql
```

2. The system uses the existing database connection from `config/Database_Manager.php`

## Files Structure

### Configuration Files
- `config/User.php` - User model class with authentication methods
- `config/session.php` - Session management utilities
- `config/schema_users.sql` - Database schema for users table

### Public Pages
- `public/login.php` - Login form
- `public/login_process.php` - Login processing
- `public/register.php` - Registration form
- `public/register_process.php` - Registration processing
- `public/logout.php` - Logout functionality
- `public/profile.php` - User profile page
- `public/change_password.php` - Change password form
- `public/change_password_process.php` - Password change processing

### Styling
- `public/css/auth.css` - Authentication forms and profile styling

### Navigation
- `public/Header.php` - Main header with authentication links
- `public/layouts/Header.php` - Layout header with authentication links

## Usage

### Protecting Pages
To require authentication for a page:

```php
<?php
require_once '../config/session.php';
requireLogin();
?>
```

### Getting Current User Information
```php
$user_id = getCurrentUserId();
$username = getCurrentUsername();
$email = getCurrentUserEmail();
```

### Using Flash Messages
```php
// Set a flash message
setFlashMessage('success', 'Operation completed successfully');

// Display flash message
$flash = getFlashMessage();
if ($flash) {
    echo '<div class="alert alert-' . $flash['type'] . '">' . $flash['message'] . '</div>';
}
```

## Security Features

- Password hashing using `PASSWORD_DEFAULT` algorithm
- Session regeneration on login to prevent session fixation
- Session timeout (30 minutes of inactivity)
- CSRF protection through session validation
- Input validation and sanitization
- Secure password verification
- Account status checking
- Protected route system

## Navigation Links

When logged in, users see:
- Profile link
- Welcome message with username
- Logout link

When not logged in, users see:
- Login link
- Register link
