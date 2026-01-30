# Property & Rental Management System

A lightweight **PHP & MySQL-based system** designed for managing properties, rentals, landlords, renters, inspections, payments, and related services. 
This project primarily focuses on the **database structure** and **CRUD operations**, serving as a learning resource and a foundation for further development.

---

##  Features

- **Property Management**: Add, view, and delete properties with details such as type, location, price, and landlord.
- **Rental Management**: Track rental agreements, start/end dates, monthly rent, and deposits.
- **Landlord & Renter Records**: Maintain personal details and contact information.
- **Payment Tracking**: Manage rental payments with date and amount.
- **Inspection Records**: Record inspection details, findings, and responsible inspectors.
- **Services Management**: Link additional property services to specific properties.
- **Relational Database Design**: Includes mapping tables for many-to-many relationships.
- **Separation of Logic & Layout**: Basic HTML structure with `Header.html` and `Footer.html`.

---

##  Database Schema

The project is built around a **relational database** with the following main entities:

- **Landlord**
- **Renter**
- **Property**
- **Rental**
- **Payment**
- **Inspection**
- **Services**
- **PropertyServices** (mapping table)

The full schema is available in [`schima.pdf`](./schima.pdf).

---

##  Project Structure

```
├── Add_*.php           # Add operations (CRUD - Create)
├── Del_*.php           # Delete operations (CRUD - Delete)
├── Show_*.php          # View operations (CRUD - Read)
├── Mapp_*.php          # Mapping entities for relations
├── Path_Insert.php     # Centralized insert logic
├── Path_Delete.php     # Centralized delete logic
├── Path_View.php       # Centralized view logic
├── Database_Manager.php # DB connection and management
├── Database_overview.php # DB overview and utility
├── Header.html / Footer.html
├── index.php           # Main entry point
└── schima.pdf          # Database schema (ERD + tables)
```

---

##  Getting Started

### Prerequisites
- PHP 7.4+ with MySQLi extension
- MySQL 5.7+ or MariaDB
- Git

### 1. Clone the Repository
```bash
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>
```

### 2. Install Dependencies
Install required system packages:
```bash
# On Ubuntu/Debian
sudo apt install php8.3-cli php8.3-mysql mysql-server
```

### 3. Setup Database
- Start MySQL service:
  ```bash
  sudo systemctl start mysql
  ```
- Create a MySQL database:
  ```bash
  mysql -u root -p
  mysql> CREATE DATABASE property_management;
  mysql> EXIT;
  ```
- Import the database schema from [`schima.pdf`](./schima.pdf) into the database.
- Update database credentials in [config/Database_Manager.php](config/Database_Manager.php):
  ```php
  $db_server = 'localhost';
  $db_user = 'your_mysql_user';
  $db_pass = 'your_mysql_password';
  $db_name = 'property_management';
  ```
  Or set environment variables:
  ```bash
  export DB_SERVER=localhost
  export DB_USER=your_user
  export DB_PASS=your_password
  export DB_NAME=property_management
  ```

### 4. Run Locally
Start the PHP development server from the project root:
```bash
cd /path/to/project
php -S localhost:8000 -t public
```

The `-t public` flag tells the server to use the `public/` directory as the document root.

Then open your browser:
```
http://localhost:8000
```

---

##  Testing

### Manual Testing
1. Ensure the PHP server is running with correct document root:
   ```bash
   php -S localhost:8000 -t public
   ```
2. Open `http://localhost:8000` in your browser
3. Test CRUD operations:
   - **Add**: Create new properties, landlords, renters, payments, inspections, and services
   - **View**: Display all records in the system
   - **Delete**: Remove records (verify relational integrity)
   - **Search**: Filter by different criteria if implemented

### Database Testing
Verify database connection and tables:
```bash
mysql -u root -p property_management
mysql> SHOW TABLES;
mysql> SELECT * FROM landlord;  # Test a sample table
```

### Browser Console
Check for JavaScript/PHP errors in the browser console (F12)

---

##  Purpose

This project was developed mainly for:
- Practicing **relational database design**.
- Implementing **basic PHP CRUD operations**.
- Serving as a starting point for a **real estate management system**.

---

##  Next Improvements (Ideas)

- Add authentication (Admin / Landlord / Renter).
- Improve UI with modern frameworks (Bootstrap/Tailwind).
- Add search & filtering for properties.
- Export reports (PDF/Excel).
- REST API layer for external integrations.

---

## Author

Amir Khoshdel Louyeh
