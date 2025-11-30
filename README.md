# Ubuntu Skills - Secure Learning Platform

A fully secured PHP learning management system with hands on craft courses, implementing OWASP Top 10 protections.

## Features

- **Enterprise Security** - Complete OWASP Top 10 protection
- **User Authentication** - Secure login/signup with Argon2id password hashing
- **Courses** - Crochet, Bead Making, Mbira, Henna courses
- **Progress Tracking** - Track user progress through modules
- **Session Security** - Anti-hijacking, anti-fixation, automatic timeout
- **Attack Prevention** - SQL injection, XSS, CSRF protection
- **Security Logging** - Complete audit trail of all security events
- **Rate Limiting** - Prevent brute force attacks

---

## QUICK START (5 Minutes)

### Prerequisites
- PHP 7.4+ (8.0+ recommended)
- MySQL 5.7+ or MariaDB 10.3+
- Web server (Apache/Nginx) or PHP built-in server

### Installation

```bash
# 1. Clone or download the project
cd ubuntu_skills/

# 2. Import database
mysql -u root -p < secure_database_setup.sql

# 3. Copy configuration template
cp config.example.php config.php

# 4. Edit config.php and set your passwords
nano config.php  # or use your favorite editor

# 5. Create logs directory
mkdir logs
chmod 750 logs

# 6. Start server
php -S localhost:8000

# 7. Open browser
http://localhost:8000/index.php
```

### First Time Setup Checklist

- [ ] Import `secure_database_setup.sql` into MySQL
- [ ] Copy `config.example.php` to `config.php`
- [ ] Change `DB_PASS` in config.php
- [ ] Change `CSRF_SECRET` in config.php
- [ ] Create `logs/` directory
- [ ] Set `session.cookie_secure` to 0 (localhost) or 1 (production)
- [ ] Test registration with test account
- [ ] Test login/logout functionality

---

## File Structure

```
ubuntu_skills/
│
├── README.md                      # This file
├── SECURITY_GUIDE.md              # Comprehensive security documentation
│
├── config.php                     # Main configuration (create from template)
├── config.example.php             # Configuration template (safe to commit)
├── secure_database_setup.sql     # Complete database structure
│
├── login.php                      # Secure login handler
├── signup.php                     # Secure registration
├── logout.php                     # Logout handler
├── index.php                      # Main homepage
│
├── logs/                          # Security & error logs (auto-created)
│   ├── security.log
│   └── php-errors.log
│
├── Course Folders/
│   ├── crochet/
│   ├── beads/
│   ├── mbira/
│   └── henna/
│
└── assets/
    ├── styles.css
    └── script.js
```

---

## Security Features

### OWASP Top 10 Protected

| Risk | Protection | Status |
|------|-----------|--------|
| A01: Broken Access Control | Session validation, server-side checks | Implemented |
| A02: Cryptographic Failures | Argon2id hashing, HTTPS ready |Implemented |
| A03: Injection (SQL) | PDO prepared statements |Implemented |
| A04: Insecure Design | Threat modeling, secure defaults |Implemented |
| A05: Security Misconfiguration | Secure headers, proper permissions |Implemented |
| A06: Vulnerable Components | Minimal dependencies |Implemented |
| A07: Auth Failures | Strong passwords, rate limiting |Implemented |
| A08: Data Integrity | CSRF tokens, input validation |Implemented |
| A09: Logging Failures | Comprehensive logging |Implemented |
| A10: SSRF | N/A (no server requests) |N/A |

### Key Security Implementations

1. **Sessions**
   - Session fixation prevention
   - Session hijacking detection
   - Automatic timeout (30 min)
   - Secure cookies (HTTP-only, SameSite)

2. **Passwords**
   - Argon2id hashing (most secure)
   - Automatic salting
   - Strength validation
   - Password policy enforcement

3. **Database**
   - Prepared statements only
   - Input sanitization
   - No direct SQL concatenation

4. **Access Control**
   - Server-side validation
   - Role-based access ready
   - Protected routes
   - Redirect on unauthorized access

5. **Attack Prevention**
   - SQL Injection: PDO prepared statements
   - XSS: HTML escaping, CSP headers
   - CSRF: Unique tokens per session
   - Brute Force: Rate limiting (5 attempts)
   - Session Hijacking: User agent/IP validation

---

## Usage Guide

### For Students

1. **Register Account**
   ```
   http://localhost:8000/signup.php
   ```
   - Username: 3-20 characters
   - Strong password required
   - Unique email

2. **Login**
   ```
   http://localhost:8000/login.php
   ```
   - Automatic session timeout after 30 min
   - Rate limited (5 attempts per 15 min)

3. **Browse Courses**
   - View all available courses
   - Must be logged in to access

4. **Track Progress**
   - Progress automatically saved
   - Resume where you left off

## Testing

### Test Accounts

After running `secure_database_setup.sql`, you'll have:

**Test User:**
- Username: `testuser`
- Password: `TestUser123!`
- Status: Active, Email Verified

### Security Tests

```bash
# Test 1: SQL Injection Protection
# Try login with: admin' OR '1'='1
# Expected: Login fails safely

# Test 2: XSS Protection
# Try username: <script>alert('XSS')</script>
# Expected: Script is escaped

# Test 3: CSRF Protection
# Submit form without CSRF token
# Expected: Request blocked

# Test 4: Rate Limiting
# Try 6 wrong passwords
# Expected: "Too many attempts" message

# Test 5: Session Timeout
# Login and wait 31 minutes
# Expected: Redirected to login
```

### Session Settings (`config.php`)

```php
// Session lifetime (seconds)
ini_set('session.cookie_lifetime', 86400);  // 24 hours

// Inactivity timeout (seconds)
// Change on line 54: time() - $_SESSION['last_activity'] > 1800
// Default: 1800 seconds (30 minutes)
```

### Rate Limiting

```php
// Maximum login attempts before lockout
define('MAX_LOGIN_ATTEMPTS', 5);

// Lockout duration (seconds)
define('LOGIN_LOCKOUT_TIME', 900);  // 15 minutes
```

### Password Policy

```php
// Password algorithm
define('PASSWORD_ALGO', PASSWORD_ARGON2ID);

// Validation rules (config.php line 194-199)
// - Minimum 8 characters
// - At least 1 uppercase
// - At least 1 lowercase
// - At least 1 number
// - At least 1 special character
```

---

## Database Schema

### Main Tables

1. **users** - User accounts with security features
2. **login_attempts** - Rate limiting tracker
3. **sessions** - Server-side session storage
4. **courses** - Available courses
5. **user_progress** - Learning progress tracking
6. **security_log** - Audit trail
7. **password_resets** - Password recovery (future)

### Automatic Cleanup

The database includes event schedulers that auto-delete:
- Login attempts older than 7 days
- Expired sessions older than 1 day
- Used/expired password reset tokens

---

## Troubleshooting

### "Database connection error"

```bash
# Check MySQL is running
systemctl status mysql  # Linux
brew services list  # macOS

# Test connection
mysql -u ubuntu_app_user -p ubuntu_db

# Verify credentials in config.php
```

### "Session validation failed"

```bash
# Clear browser cookies
# Check session.cookie_secure setting (0 for HTTP, 1 for HTTPS)
# Verify /tmp is writable (Linux)
```

### "CSRF validation failed"

```bash
# Enable cookies in browser
# Clear cache and cookies
# Check that form has csrf_token field
```

### "Too many login attempts" 

```sql
# Reset attempts for specific user
DELETE FROM login_attempts WHERE username = 'username';

# Or reset all
TRUNCATE TABLE login_attempts;
```

### Development (Localhost)

```php
// In config.php

// Allow HTTP (no HTTPS required)
ini_set('session.cookie_secure', 0);

// Enable error display (for debugging)
ini_set('display_errors', 1);
```

### Production (Live Server)

```php
// In config.php

// Require HTTPS
ini_set('session.cookie_secure', 1);

// Hide errors from users
ini_set('display_errors', 0);

// Use strong unique secrets
define('CSRF_SECRET', 'unique_random_32_char_string');
```

**Production Checklist:**
- [ ] Enable HTTPS (SSL certificate)
- [ ] Set `session.cookie_secure` to 1
- [ ] Set `display_errors` to 0
- [ ] Change all default passwords
- [ ] Use strong CSRF_SECRET
- [ ] Restrict database user permissions
- [ ] Set proper file permissions
---

## Key Takeaways

- **All passwords are hashed** with Argon2id (never stored in plaintext)
- **All database queries use prepared statements** (SQL injection impossible)
- **CSRF tokens protect forms** (CSRF attacks prevented)
- **Rate limiting prevents brute force** (account lockout after attempts)
- **Sessions are validated** (hijacking prevented)
- **Complete audit trail** (all security events logged)

