# Property & Rental Management System

[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-00758F?logo=mysql)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen)](https://github.com/)

A robust, production-ready **PHP & MySQL-based system** for comprehensive management of properties, rentals, landlords, renters, inspections, payments, and related services. Built with a focus on **relational database design**, **secure CRUD operations**, and **clean code architecture**.

### 🎯 Purpose
This project serves as both a **learning resource** for database-driven web applications and a **foundation** for real estate management systems, demonstrating industry best practices in PHP development.

---

## ✨ Key Features

| Feature | Description |
|---------|-------------|
| 🏠 **Property Management** | Comprehensive add, view, and delete operations for properties with type, location, pricing, and landlord associations |
| 📋 **Rental Management** | Track rental agreements with start/end dates, monthly rent calculations, and deposit management |
| 👥 **Stakeholder Management** | Maintain detailed records of landlords and renters with contact information |
| 💳 **Payment Tracking** | Monitor rental payments with precise date tracking and amount management |
| 🔍 **Inspection System** | Document property inspections with findings and responsible inspector assignment |
| 🛠️ **Services Management** | Link and manage additional property services with many-to-many relationships |
| 🔐 **Secure Architecture** | Prepared statements, input validation, and parameterized queries prevent SQL injection |
| 🎨 **Responsive UI** | Clean, modern interface with mobile-friendly design |
| 📊 **Relational Database** | Well-normalized schema with mapping tables for complex relationships

---

## 📊 Database Schema

The system is built on a **fully normalized relational database** with the following core entities:

```
┌─────────────────────────────────────────────────────────┐
│                   Core Entities                         │
├─────────────────────────────────────────────────────────┤
│ • Landlord          │ • Property          │ • Services  │
│ • Renter            │ • Rental            │ • Payment   │
│ • Inspection        │ • PropertyServices  │             │
└─────────────────────────────────────────────────────────┘
```

**Relationships**:
- One Landlord → Many Properties
- One Property → Many Rentals
- O📁 Project Structure

```
property-rental-management-system/
├── config/                          # Configuration layer
│   ├── Database_Manager.php        # MySQLi connection & query execution
│   ├── Validation.php              # Input validation functions
│   └── Database_overview.php       # Database utilities
│
├── public/                          # Web root
│   ├── index.php                   # Main dashboard & entry point
│   ├── css/                        # Styling
│   │   ├── style.css              # Main styles
│   │   ├── header.css             # Header styling
│   │   ├── tables.css             # Table styling
│   │   ├── forms.css              # Form styling
│   │   ├── utilities.css          # Utility classes
│   │   └── animations.css         # CSS animations
│   │
│   ├── layouts/                    # Reusable templates
│   │   ├── Header.html
│   │   └── Footer.html
│   │
│   ├── Entity Files (CRUD Operations)
│   │   ├── Property.php            # Property management
│   │   ├── Landlord.php            # Landlord management
│   │   ├── Renter.php              # Renter management
│  🚀 Getting Started

### Prerequisites

| Requirement | Version | Purpose |
|-------------|---------|---------|
| PHP | 7.4+ | Runtime environment |
| MySQLi Extension | Built-in | Database driver |
| MySQL/MariaDB | 5.7+ | Database server |
| Git | Latest | Version control |

### Installation

#### 1️⃣ Clone the Repository
```bash
git clone https://github.com/<your-username>/<repo-name>.git
cd property-rental-management-system
```

#### 2️⃣ Install System Dependencies

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install -y php8.3-cli php8.3-mysql php8.3-json php8.3-mbstring mysql-server git
```

**macOS (Homebrew):**
```bash
brew install php mysql
```

#### 3️⃣ Configure Database

Start MySQL service:
```bash
# Ubuntu/Debian
sudo systemctl start mysql

# macOS
brew services start mysql
```

Create database and user:
```bash
mysql -u root -p

# In MySQL console:
CREATE DATABASE property_management;
CREATE USER 'property_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON property_management.* TO 'property_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Import the schema from [`schima.pdf`](./schima.pdf):
```bash
# After creating tables from the schema document
mysql -u property_user -p property_management < schema.sql
```

#### 4️⃣ Configure Application

Update credentials in `config/Database_Manager.php`:
```php
$db_server = 'localhost';
$db_user = 'property_user';
$db_pass = 'your_secure_password';
$db_name = 'property_management';
```

**OR** use environment variables:
```bash
export DB_SERVER=localhost
export DB_USER=property_user
export DB_PASS=your_secure_password
export DB_NAME=property_management
```

#### 5️⃣ Start Development Server

```bash
php -S localhost:8000 -t public
```

Open in browser: **http://localhost:8000**

---

## 🧪 Testing

### Manual Testing Checklist

- [ ] **CRUD Operations**
  - Create new properties, landlords, renters, payments, inspections, and services
  - View all records in the system
  - Edit/Update existing records
  - Delete records (verify relational integrity)

- [ ] **Database Validation**
  ```bash
  mysql -u property_user -p property_management
  mysql> SHOW TABLES;
  mysql> SELECT COUNT(*) FROM landlord;
  ```

- [ ] **Browser Console** (F12)
  - Check for PHP/JavaScript errors
  - Verify network requests complete successfully

- [ ] **Input Validation**
  - Test with invalid email addresses
  - Test with special characters
  - Test with SQL injection attempts

---

## 🔐 Security Features

- ✅ **Prepared Statements** - Protection against SQL injection
- ✅ **Input Validation** - Comprehensive validation for all user inputs
- ✅ **Input Sanitization** - htmlspecialchars() and trim() for output encoding
- ✅ **Type Checking** - Strict parameter type binding
- ✅ **Error Handling** - Centralized exception handling with logging

---

## 📚 Usage Examples

### Adding a Property
1. Navigate to Property Management
2. Fill in property details (type, location, price, landlord)
3. Click "Add Property"
4. Verify in the properties table

### Managing Rentals
1. Go to Rental Management
2. Select property and renter
3. Set rental period and monthly rent
4. Save rental agreement

### Tracking Payments
1. Open Payment Tracking
2. Link to specific rental agreement
3. Record payment date and amount
4. View payment history

---

## 🛣️ Roadmap

### Short-term Improvements
- [ ] User authentication (Admin / Landlord / Renter roles)
- [ ] Search and filtering capabilities
- [ ] Data export (CSV/PDF)
- [ ] Email notifications

### Medium-term Enhancements
- [ ] Modern UI framework (Bootstrap 5 / Tailwind CSS)
- [ ] Dashboard analytics and reporting
- [ ] Document management system
- [ ] Lease agreement templates

### Long-term Vision
- [ ] REST API for mobile applications
- [ ] Real-time notifications
- [ ] Advanced analytics and forecasting
- [ ] Multi-tenant support
- [ ] Third-party integrations

---

## 📋 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---


##  Purpose

This project was developed mainly for:
- Practicing **relational database design**.
- Implementing **basic PHP CRUD operations**.
- Serving as a starting point for a **real estate management system**.

---

## Author

Amir Khoshdel Louyeh
