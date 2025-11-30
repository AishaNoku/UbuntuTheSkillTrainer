# 🔒 UBUNTU SKILLS - COMPLETE SECURITY IMPLEMENTATION GUIDE

## 📋 TABLE OF CONTENTS
1. [Quick Start Setup](#quick-start-setup)
2. [Security Features Implemented](#security-features-implemented)
3. [OWASP Top 10 Protection](#owasp-top-10-protection)
4. [File Structure](#file-structure)
5. [Step-by-Step Installation](#installation)
6. [Testing Security](#testing)
7. [Maintenance & Monitoring](#maintenance)
8. [Troubleshooting](#troubleshooting)

---

## 🚀 QUICK START SETUP

### Step 1: Database Setup
```bash
# Import the secure database structure
mysql -u root -p < secure_database_setup.sql

# Verify tables were created
mysql -u root -p ubuntu_db -e "SHOW TABLES;"
```

### Step 2: Configure Application
```bash
# Edit config.php and change these values:
# - DB_PASS (line 58)
# - CSRF_SECRET (line 67)
# - PASSWORD_ALGO settings (lines 70-75)
```

### Step 3: Set Permissions
```bash
# Create logs directory
mkdir logs
chmod 750 logs

# Set file permissions
chmod 644 *.php
chmod 600 config.php
chmod 755 .
```

### Step 4: Test
```bash
# Start your web server
php -S localhost:8000

# Open browser to:
http://localhost:8000/index.php
```

---

## 🛡️ SECURITY FEATURES IMPLEMENTED

### 1. **SECURE SESSIONS**
✅ Session fixation prevention (regenerate ID on login)
✅ Session hijacking protection (validate user agent & IP)
✅ HTTP-only cookies (prevents XSS access)
✅ Secure cookies (HTTPS only in production)
✅ SameSite=Strict (CSRF protection)
✅ Session timeout (30 minutes inactivity)
✅ Server-side session validation

**How it works:**
```php
// In config.php lines 21-60
initSecureSession(); // Initializes all security settings
validateSession();   // Checks for hijacking attempts
```

### 2. **PASSWORD SECURITY**
✅ Argon2id hashing (most secure algorithm)
✅ Password strength validation
✅ Salting (automatic with password_hash)
✅ No plaintext passwords ever stored

**Password Requirements:**
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- At least 1 special character

**How it works:**
```php
// Hashing (signup.php line 94)
$hashedPassword = password_hash($password, PASSWORD_ARGON2ID, [
    'memory_cost' => 65536,  // 64 MB
    'time_cost' => 4,
    'threads' => 3
]);

// Verification (login.php line 91)
password_verify($password, $user['password'])
```

### 3. **SQL INJECTION PROTECTION**
✅ PDO prepared statements (100% of queries)
✅ No string concatenation in SQL
✅ Input type validation
✅ Parameterized queries

**Example:**
```php
// SECURE (what we use)
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);

// VULNERABLE (never do this)
$query = "SELECT * FROM users WHERE username = '$username'";
```

### 4. **XSS PROTECTION**
✅ htmlspecialchars() on all output
✅ Content Security Policy headers
✅ X-XSS-Protection header
✅ Input sanitization

**How it works:**
```php
// Output escaping (everywhere in HTML)
echo htmlspecialchars($username);

// CSP Header (config.php line 358)
header("Content-Security-Policy: default-src 'self'...");
```

### 5. **CSRF PROTECTION**
✅ Unique tokens per session
✅ Token validation on all POST requests
✅ Token expiration (1 hour)
✅ SameSite cookie attribute

**How it works:**
```php
// Generate token (in forms)
<input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

// Validate token (on submission)
if (!validateCSRFToken($_POST['csrf_token'])) {
    die('CSRF validation failed');
}
```

### 6. **RATE LIMITING**
✅ Login attempt limiting (5 attempts)
✅ Account lockout (15 minutes)
✅ Automatic account lock after 10 failed attempts
✅ IP-based tracking

**Configuration:**
```php
// In config.php
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes
```

### 7. **ACCESS CONTROL**
✅ Server-side authorization checks
✅ Session validation on every request
✅ Redirect to login if not authenticated
✅ Least privilege database user

**How to use:**
```php
// In any protected page
requireLogin(); // Redirects if not logged in

if (isLoggedIn()) {
    // Show protected content
}
```

### 8. **SECURE LOGGING**
✅ All authentication events logged
✅ Security events tracked
✅ No sensitive data in logs
✅ Audit trail in database

**Log Locations:**
- File: `logs/security.log`
- Database: `security_log` table

### 9. **DATABASE SECURITY**
✅ Least privilege user (not root)
✅ Limited permissions per table
✅ Auto-cleanup of old data
✅ Encrypted connections ready

**Database User Permissions:**
```sql
ubuntu_app_user can:
- SELECT, INSERT, UPDATE on users
- SELECT, INSERT, DELETE on login_attempts
- SELECT on courses
- INSERT on security_log

ubuntu_app_user CANNOT:
- DROP tables
- CREATE tables
- Access other databases
```

### 10. **SECURITY HEADERS**
✅ X-Frame-Options (clickjacking protection)
✅ X-Content-Type-Options (MIME sniffing protection)
✅ Strict-Transport-Security (force HTTPS)
✅ Content-Security-Policy (XSS/injection protection)
✅ Referrer-Policy
✅ Permissions-Policy

---

## 🎯 OWASP TOP 10 PROTECTION

| OWASP Risk | Protection Implemented | Location |
|------------|----------------------|----------|
| **A01: Broken Access Control** | ✅ Session validation, requireLogin(), server-side checks | config.php lines 204-217 |
| **A02: Cryptographic Failures** | ✅ Argon2id hashing, HTTPS ready, no plaintext secrets | config.php lines 70-75 |
| **A03: Injection (SQLi)** | ✅ PDO prepared statements, no concatenation | All database queries |
| **A04: Insecure Design** | ✅ Threat modeling, secure defaults, least privilege | Entire architecture |
| **A05: Security Misconfiguration** | ✅ Secure headers, proper permissions, no defaults | config.php lines 342-369 |
| **A06: Vulnerable Components** | ✅ Minimal dependencies, update mechanism ready | composer.json (if needed) |
| **A07: Authentication Failures** | ✅ Strong passwords, rate limiting, MFA-ready | login.php, signup.php |
| **A08: Data Integrity Failures** | ✅ CSRF tokens, input validation, business logic checks | config.php lines 230-258 |
| **A09: Logging Failures** | ✅ Comprehensive logging, audit trail, monitoring | config.php lines 315-340 |
| **A10: SSRF** | ✅ No server-side requests, URL validation if needed | N/A (not applicable) |

---

## 📁 FILE STRUCTURE

```
ubuntu_skills/
│
├── config.php                    # 🔒 Main security configuration
├── login.php                     # 🔑 Secure login handler
├── signup.php                    # ✍️ Secure registration
├── logout.php                    # 🚪 Logout handler
├── index.php                     # 🏠 Home page with auth
├── secure_database_setup.sql     # 🗄️ Database structure
│
├── logs/                         # 📝 Security logs
│   ├── security.log
│   └── php-errors.log
│
├── crochet/                      # 📚 Course folders
├── beads/
├── mbira/
└── henna/
```

---

## 🔧 INSTALLATION STEPS

### Step 1: Import Database

```bash
# Login to MySQL
mysql -u root -p

# Run the setup script
source secure_database_setup.sql

# Verify
SHOW DATABASES;
USE ubuntu_db;
SHOW TABLES;
```

### Step 2: Configure Application

Edit `config.php` and change:

```php
// Line 58: Database password
define('DB_PASS', 'YOUR_STRONG_PASSWORD_HERE');

// Line 67: CSRF secret
define('CSRF_SECRET', 'your_random_32_character_secret_here');

// Line 35: For development (set to 0), for production (set to 1)
ini_set('session.cookie_secure', 0); // Change to 1 with HTTPS
```

### Step 3: Create Logs Directory

```bash
mkdir logs
chmod 750 logs
touch logs/security.log
touch logs/php-errors.log
chmod 640 logs/*.log
```

### Step 4: Set File Permissions

```bash
# PHP files readable by web server
chmod 644 *.php

# Config file more restrictive
chmod 600 config.php

# Make directories executable
chmod 755 crochet beads mbira henna
```

### Step 5: Update Database User Password

```sql
-- In MySQL
ALTER USER 'ubuntu_app_user'@'localhost' 
IDENTIFIED BY 'YourNewStrongPassword123!';

FLUSH PRIVILEGES;
```

Then update `config.php` line 58 with the same password.

### Step 6: Test Basic Functionality

1. **Start web server:**
   ```bash
   php -S localhost:8000
   ```

2. **Open browser:**
   ```
   http://localhost:8000/index.php
   ```

3. **Test registration:**
   - Click "Create Account"
   - Fill form with strong password
   - Submit

4. **Test login:**
   - Use created credentials
   - Should redirect to homepage
   - Should see "Hi, [username]"

5. **Test logout:**
   - Click "Log Out"
   - Should return to homepage logged out

---

## 🧪 TESTING SECURITY

### Test 1: SQL Injection Protection

Try logging in with:
- Username: `admin' OR '1'='1`
- Password: `anything`

**Expected:** Login fails, no error shown

### Test 2: XSS Protection

Try registering with:
- Username: `<script>alert('XSS')</script>`

**Expected:** Script tags are escaped, no alert shown

### Test 3: CSRF Protection

1. Login normally
2. Open browser console
3. Try submitting login form without CSRF token:
   ```javascript
   fetch('login.php', {
       method: 'POST',
       body: new FormData(document.querySelector('form'))
   })
   ```

**Expected:** Request blocked

### Test 4: Session Timeout

1. Login
2. Wait 31 minutes (or modify timeout in config.php to 1 minute for testing)
3. Try to access a protected page

**Expected:** Redirected to login with timeout message

### Test 5: Rate Limiting

1. Try logging in with wrong password 6 times in a row
2. Try again

**Expected:** "Too many attempts" message

### Test 6: Password Strength

Try registering with weak passwords:
- `password` (no uppercase/symbol)
- `Pass1` (too short)
- `PASSWORD123` (no lowercase)

**Expected:** Validation errors shown

---

## 📊 MONITORING & MAINTENANCE

### Daily Checks

```bash
# Check security logs
tail -n 50 logs/security.log

# Check for failed logins
mysql -u root -p ubuntu_db -e "
SELECT username, COUNT(*) as attempts 
FROM login_attempts 
WHERE success = 0 
AND attempted_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
GROUP BY username 
HAVING attempts > 3;
"

# Check locked accounts
mysql -u root -p ubuntu_db -e "
SELECT username, account_status, last_login 
FROM users 
WHERE account_status != 'active';
"
```

### Weekly Tasks

1. **Review security logs:**
   ```bash
   grep -i "error\|critical\|warning" logs/security.log
   ```

2. **Check database size:**
   ```sql
   SELECT 
       table_name, 
       ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
   FROM information_schema.TABLES 
   WHERE table_schema = 'ubuntu_db';
   ```

3. **Backup database:**
   ```bash
   mysqldump -u root -p ubuntu_db > backup_$(date +%Y%m%d).sql
   ```

### Monthly Tasks

1. **Update dependencies** (if using Composer)
2. **Review and rotate logs**
3. **Check for suspicious patterns**
4. **Update passwords if needed**

---

## 🐛 TROUBLESHOOTING

### Problem: "Database connection error"

**Solution:**
1. Check MySQL is running: `systemctl status mysql`
2. Verify credentials in `config.php`
3. Test connection:
   ```bash
   mysql -u ubuntu_app_user -p ubuntu_db
   ```

### Problem: "Session validation failed"

**Solution:**
1. Clear browser cookies
2. Check `session.cookie_secure` in config.php (should be 0 for HTTP, 1 for HTTPS)
3. Verify session files are writable:
   ```bash
   ls -la /tmp | grep sess_
   ```

### Problem: "CSRF validation failed"

**Solution:**
1. Ensure cookies are enabled
2. Check that form has hidden csrf_token field
3. Verify session is active: `var_dump($_SESSION);`

### Problem: "Too many login attempts" but you just started

**Solution:**
1. Clear login attempts:
   ```sql
   DELETE FROM login_attempts WHERE username = 'your_username';
   ```
2. Reset failed attempts counter:
   ```sql
   UPDATE users SET failed_login_attempts = 0 WHERE username = 'your_username';
   ```

### Problem: Pages not loading CSS/JS

**Solution:**
1. Check file paths are correct
2. Verify CSP headers allow local resources (config.php line 358)
3. Inspect browser console for errors

---

## 🔐 SECURITY BEST PRACTICES

### DO ✅
- Use HTTPS in production
- Keep strong unique passwords
- Regular backups
- Monitor logs frequently
- Update PHP and MySQL regularly
- Use environment variables for secrets
- Test security regularly
- Document changes

### DON'T ❌
- Commit config.php to Git
- Use 'root' MySQL user
- Disable security features
- Ignore warnings/errors
- Share credentials
- Display detailed errors to users
- Store passwords in plaintext
- Skip input validation

---

## 📞 SUPPORT

If you encounter issues:

1. Check this documentation
2. Review logs: `logs/security.log` and `logs/php-errors.log`
3. Verify database connection
4. Test with provided test credentials

---

## 📝 CHANGELOG

### Version 1.0 (Current)
- ✅ Secure session management
- ✅ Password hashing (Argon2id)
- ✅ SQL injection protection
- ✅ XSS protection
- ✅ CSRF protection
- ✅ Rate limiting
- ✅ Security logging
- ✅ Access control
- ✅ Security headers
- ✅ Database security

---

## 🎓 LEARNING RESOURCES

- [OWASP Top 10](https://owasp.org/Top10/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)
- [Password Hashing](https://www.php.net/manual/en/function.password-hash.php)
- [PDO Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php)

---

**🎉 Your Ubuntu Skills platform is now SECURE!**
