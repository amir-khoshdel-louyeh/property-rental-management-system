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

### 1. Clone the Repository
```bash
git clone https://github.com/<your-username>/<repo-name>.git
cd <repo-name>
```

### 2. Setup Database
- Create a MySQL database (e.g., `property_management`).
- Import tables based on [`schima.pdf`](./schima.pdf).
- Update database credentials in `Database_Manager.php`.

### 3. Run Locally
- Place the project in your local server directory (e.g., `htdocs` for XAMPP).
- Open in browser:
  ```
  http://localhost/<repo-name>
  ```

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
