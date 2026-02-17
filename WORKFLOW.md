# Top Colleges India - Workflow Documentation

## 📊 System Architecture

```
NewVcollege/
├── 🏠 Landing Page
│   └── index.php (Homepage with college information)
│
├── 🔐 Authentication Layer
│   ├── login.php (Unified login for Admin & Student)
│   ├── register_admin.php (Admin registration)
│   ├── register_student.php (Student registration)
│   └── logout.php (Session destruction)
│
├── 👨‍💼 Admin Module
│   └── admin/dashboard.php (Admin-only area)
│
├── 👨‍🎓 Student Module
│   └── student/dashboard.php (Student-only area)
│
├── ⚙️ Core Components
│   ├── config/database.php (PDO database connection)
│   └── includes/auth.php (Session validation middleware)
│
└── 🎨 Assets
    ├── css/ (Bootstrap + custom styles)
    ├── js/ (jQuery + custom scripts)
    ├── images/ (Media files)
    └── fonts/ (Font files)
```

---

## 🔄 Application Flow

### 1. Entry Point
```
User visits → index.php (Homepage)
   ↓
   Presents three options:
   • Login (existing users)
   • Register as Student
   • Register as Admin
```

### 2. Registration Flow

#### Student Registration:
```
index.php 
   → register_student.php
      ├── User fills form (name, mobile, email, password)
      ├── Validation checks:
      │   ├── Password match confirmation
      │   └── Email uniqueness check
      ├── Hash password with password_hash()
      ├── Insert into 'students' table
      └── Success → Redirect to login.php
```

#### Admin Registration:
```
index.php 
   → register_admin.php
      ├── User fills form (name, mobile, email, password)
      ├── Validation checks:
      │   ├── Password match confirmation
      │   └── Email uniqueness check
      ├── Hash password with password_hash()
      ├── Insert into 'admins' table
      └── Success → Redirect to login.php
```

### 3. Login Flow

```
login.php receives credentials
   ↓
1. Check students table
   ├── Match found & password verified?
   │   ├── YES: Set session variables
   │   │         $_SESSION['user'] = name
   │   │         $_SESSION['role'] = "student"
   │   └───────→ Redirect to student/dashboard.php
   │
   └── NO: Continue to step 2
   
2. Check admins table
   ├── Match found & password verified?
   │   ├── YES: Set session variables
   │   │         $_SESSION['user'] = name
   │   │         $_SESSION['role'] = "admin"
   │   └───────→ Redirect to admin/dashboard.php
   │
   └── NO: Show error "Invalid Email or Password"
```

### 4. Protected Dashboard Access

#### Student Dashboard:
```
student/dashboard.php
   ↓
1. includes/auth.php (checks if session exists)
   ├── NO session → Redirect to login.php
   └── Session exists → Continue
   
2. Role verification (must be "student")
   ├── Role != "student" → Redirect to login.php
   └── Role = "student" → Display dashboard
   
3. Display welcome message with user name
4. Logout button available
```

#### Admin Dashboard:
```
admin/dashboard.php
   ↓
1. includes/auth.php (checks if session exists)
   ├── NO session → Redirect to login.php
   └── Session exists → Continue
   
2. Role verification (must be "admin")
   ├── Role != "admin" → Redirect to login.php
   └── Role = "admin" → Display dashboard
   
3. Display welcome message with user name
4. Logout button available
```

### 5. Logout Flow

```
logout.php
   ↓
1. Start session
2. Destroy all session data
3. Redirect to login.php
```

---

## 🔒 Security Features

| Feature | Implementation | Status |
|---------|----------------|--------|
| **Password Security** | `password_hash()` bcrypt algorithm | ✅ Implemented |
| **Password Verification** | `password_verify()` for login | ✅ Implemented |
| **SQL Injection Protection** | PDO prepared statements | ✅ Implemented |
| **Session Management** | PHP sessions for authentication | ✅ Implemented |
| **Role-Based Access** | Middleware checks user role | ✅ Implemented |
| **Email Uniqueness** | Database constraint + validation | ✅ Implemented |
| **XSS Protection** | Input sanitization | ⚠️ Basic |
| **CSRF Protection** | Token validation | ❌ Not implemented |

---

## 🗄️ Database Schema

```sql
Database: college

Table: students
├── id (PK, AUTO_INCREMENT)
├── name (VARCHAR 255)
├── mobile (VARCHAR 20)
├── email (VARCHAR 255, UNIQUE)
├── password (VARCHAR 255, hashed)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)

Table: admins
├── id (PK, AUTO_INCREMENT)
├── name (VARCHAR 255)
├── mobile (VARCHAR 20)
├── email (VARCHAR 255, UNIQUE)
├── password (VARCHAR 255, hashed)
├── created_at (TIMESTAMP)
└── updated_at (TIMESTAMP)
```

**Test Accounts:**
- **Admin**: admin@test.com / password123
- **Student**: student@test.com / password123

---

## 🎯 User Journey Map

### New User (Student):
```
Home → Register Student → Fill Form → Success Message → 
Login → Enter Credentials → Student Dashboard
```

### New User (Admin):
```
Home → Register Admin → Fill Form → Success Message → 
Login → Enter Credentials → Admin Dashboard
```

### Returning User:
```
Home → Login → Credentials Check → Dashboard (role-based)
```

### Logged In User:
```
Dashboard → Work/View Data → Logout → Login Page
```

---

## 🔑 Session Variables

| Variable | Type | Purpose |
|----------|------|---------|
| `$_SESSION['user']` | String | Stores authenticated user's name |
| `$_SESSION['role']` | String | Stores user role ("student" or "admin") |

---

## 🚦 Access Control Matrix

| Page | Public | Student | Admin | Notes |
|------|--------|---------|-------|-------|
| index.php | ✅ | ✅ | ✅ | Homepage accessible to all |
| login.php | ✅ | ⚠️ | ⚠️ | Redirects if logged in |
| register_admin.php | ✅ | ⚠️ | ⚠️ | Registration page |
| register_student.php | ✅ | ⚠️ | ⚠️ | Registration page |
| student/dashboard.php | ❌ | ✅ | ❌ | Student role required |
| admin/dashboard.php | ❌ | ❌ | ✅ | Admin role required |
| logout.php | ✅ | ✅ | ✅ | Destroys session |

**Legend:**
- ✅ = Allowed
- ❌ = Blocked (redirects to login)
- ⚠️ = Allowed but may redirect based on session

---

## 📝 Key Files & Their Roles

### Core Files

| File | Purpose | Dependencies |
|------|---------|--------------|
| **config/database.php** | PDO connection to MySQL database | None |
| **includes/auth.php** | Session validation middleware | None |
| **database.sql** | Database schema & test data | None |

### Authentication Files

| File | Purpose | Dependencies |
|------|---------|--------------|
| **login.php** | Unified authentication for both roles | database.php |
| **register_admin.php** | Admin account creation | database.php |
| **register_student.php** | Student account creation | database.php |
| **logout.php** | Session termination | None |

### Dashboard Files

| File | Purpose | Dependencies |
|------|---------|--------------|
| **admin/dashboard.php** | Admin control panel | auth.php |
| **student/dashboard.php** | Student control panel | auth.php |

### Frontend Files

| File | Purpose |
|------|---------|
| **index.php** | Homepage with college information |
| **css/** | Bootstrap 5.3 + custom styles |
| **js/** | jQuery 3.6 + plugins |
| **images/** | Logo and media assets |
| **fonts/** | Custom font files |

---

## 🔄 Data Flow Diagram

```
┌─────────────┐
│   Browser   │
└──────┬──────┘
       │
       ↓
┌─────────────────────────────────────┐
│         index.php (Landing)         │
└─────────────┬───────────────────────┘
              │
       ┌──────┴──────┐
       ↓             ↓
┌────────────┐  ┌────────────┐
│  Register  │  │   Login    │
└─────┬──────┘  └─────┬──────┘
      │               │
      ↓               ↓
┌─────────────────────────────┐
│   config/database.php       │
│   (PDO Connection)          │
└─────────────┬───────────────┘
              │
       ┌──────┴──────┐
       ↓             ↓
┌──────────┐  ┌──────────┐
│ students │  │  admins  │
│  table   │  │  table   │
└──────┬───┘  └────┬─────┘
       │           │
       └─────┬─────┘
             ↓
      ┌─────────────┐
      │   Session   │
      │   Created   │
      └──────┬──────┘
             │
      ┌──────┴──────┐
      ↓             ↓
┌──────────┐  ┌──────────┐
│ Student  │  │  Admin   │
│Dashboard │  │Dashboard │
└──────────┘  └──────────┘
```

---

## 🛠️ Technology Stack

| Component | Technology | Version |
|-----------|-----------|---------|
| **Frontend** | Bootstrap | 5.3.2 |
| **Icons** | Bootstrap Icons | 1.11.1 |
| **JavaScript** | jQuery | 3.6.0 |
| **Backend** | PHP | 7.4+ |
| **Database** | MySQL | 5.7+ / MariaDB |
| **Server** | Apache (XAMPP) | Latest |

---

## 🚀 Setup Instructions

### 1. Install XAMPP
- Download and install XAMPP for Windows
- Start Apache and MySQL services

### 2. Create Database
```bash
# Access phpMyAdmin or MySQL CLI
mysql -u root -p
```

```sql
# Import database schema
SOURCE C:\xampp\htdocs\NewVcollege\database.sql;
```

### 3. Configure Database Connection
Edit `config/database.php` if needed:
```php
$host = "localhost";
$dbname = "college";
$username = "root";
$password = "";
```

### 4. Access Application
- Homepage: `http://localhost/NewVcollege/index.php`
- Login: `http://localhost/NewVcollege/login.php`

### 5. Test Accounts
- **Admin**: admin@test.com / password123
- **Student**: student@test.com / password123

---

## 📋 Features Checklist

### ✅ Implemented
- [x] User registration (Student & Admin)
- [x] User authentication (Login)
- [x] Password hashing & verification
- [x] Session management
- [x] Role-based access control
- [x] Protected dashboard areas
- [x] Logout functionality
- [x] Email uniqueness validation
- [x] Password confirmation
- [x] Responsive design (Bootstrap)
- [x] Home button on all auth pages
- [x] Toggle password visibility

### 🔜 Future Enhancements
- [ ] Password reset functionality
- [ ] Email verification
- [ ] Remember me functionality
- [ ] Profile management
- [ ] CSRF protection
- [ ] Rate limiting for login attempts
- [ ] Activity logging
- [ ] Admin user management
- [ ] Student course enrollment
- [ ] Dashboard analytics

---

## 🐛 Common Issues & Solutions

### Issue: Can't connect to database
**Solution:** 
1. Ensure XAMPP MySQL is running
2. Check database credentials in `config/database.php`
3. Verify database 'college' exists

### Issue: Redirected to login after authentication
**Solution:**
1. Check if sessions are enabled in php.ini
2. Verify `session_start()` in auth.php
3. Clear browser cookies

### Issue: Password not matching
**Solution:**
1. Ensure password_hash() is used during registration
2. Verify password_verify() is used during login
3. Check password field length in database (min 255 chars)

---

## 📞 Support & Maintenance

### File Structure Rules
- All authentication files in root directory
- Role-specific pages in respective folders (admin/, student/)
- Shared utilities in includes/
- Database connection in config/

### Naming Conventions
- PHP files: lowercase with underscores (register_student.php)
- CSS classes: Bootstrap conventions + custom prefixes
- Database tables: plural lowercase (students, admins)
- Session keys: lowercase with underscores

### Security Best Practices
- Never commit config/database.php with real credentials
- Always use prepared statements for queries
- Validate and sanitize all user inputs
- Keep PHP and dependencies updated
- Disable display_errors in production

---

## 📄 License
Top Colleges India © 2026

---

**Last Updated:** February 17, 2026  
**Version:** 1.0
