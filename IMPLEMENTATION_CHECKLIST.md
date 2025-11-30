# 🚀 UBUNTU SKILLS - SECURITY IMPLEMENTATION CHECKLIST

## ✅ STEP-BY-STEP IMPLEMENTATION GUIDE

Follow this checklist in order. Each step must be completed before moving to the next.

---

## 📦 PHASE 1: DATABASE SETUP (15 minutes)

### Step 1.1: Import Database Structure
```bash
# Login to MySQL
mysql -u root -p

# Import the secure database
source secure_database_setup.sql

# OR use this command from terminal:
mysql -u root -p < secure_database_setup.sql
```

**Verify:**
```sql
SHOW DATABASES;
USE ubuntu_db;
SHOW TABLES;
-- Should see: users, login_attempts, sessions, courses, etc.
```

- [ ] Database `ubuntu_db` created
- [ ] All 8 tables created successfully
- [ ] User `ubuntu_app_user` created
- [ ] Sample courses inserted
- [ ] Test user created (testuser / TestUser123!)

### Step 1.2: Change Database Password
```sql
-- In MySQL
ALTER USER 'ubuntu_app_user'@'localhost' 
IDENTIFIED BY 'YourNewStrongPassword_2025!';

FLUSH PRIVILEGES;
```

- [ ] Database user password changed
- [ ] New password noted securely

---

## ⚙️ PHASE 2: APPLICATION CONFIGURATION (10 minutes)

### Step 2.1: Copy Configuration Template
```bash
cp config.example.php config.php
```

### Step 2.2: Edit config.php

Open `config.php` and change:

**Line 58: Database Password**
```php
define('DB_PASS', 'YourNewStrongPassword_2025!');
```

**Line 67: CSRF Secret (generate random 32+ character string)**
```php
define('CSRF_SECRET', 'your_random_32_character_secret_here_change_this');
```

**Line 35: Session Cookie Security**
```php
// For localhost testing:
ini_set('session.cookie_secure', 0);

// For production with HTTPS:
ini_set('session.cookie_secure', 1);
```

- [ ] DB_PASS updated with real password
- [ ] CSRF_SECRET changed to random string
- [ ] session.cookie_secure set correctly

### Step 2.3: Create Logs Directory
```bash
mkdir logs
chmod 750 logs
touch logs/security.log
touch logs/php-errors.log
chmod 640 logs/*.log
```

- [ ] logs/ directory created
- [ ] Permissions set correctly

---

## 🔐 PHASE 3: SECURITY VERIFICATION (10 minutes)

### Step 3.1: Test Database Connection
```bash
php -r "
define('SECURE_ACCESS', true);
require 'config.php';
try {
    \$pdo = getSecureDBConnection();
    echo 'Database connection successful!\n';
} catch (Exception \$e) {
    echo 'ERROR: ' . \$e->getMessage() . '\n';
}
"
```

- [ ] Database connection works
- [ ] No errors displayed

### Step 3.2: Start Web Server
```bash
# Option 1: PHP built-in server
php -S localhost:8000

# Option 2: XAMPP/WAMP/MAMP
# Place files in htdocs/www folder
```

- [ ] Server started successfully
- [ ] Can access http://localhost:8000/index.php

### Step 3.3: Test Registration
1. Go to http://localhost:8000/signup.php
2. Register with:
   - Username: `demo`
   - Email: `demo@test.com`
   - Password: `Demo123!@#`
   - Confirm Password: `Demo123!@#`
3. Click "Create Account"

- [ ] Registration form displays
- [ ] Form validates password strength
- [ ] Registration succeeds
- [ ] Redirects to login page

### Step 3.4: Test Login
1. Go to http://localhost:8000/login.php
2. Login with credentials from Step 3.3
3. Should redirect to homepage

- [ ] Login form displays
- [ ] Can log in successfully
- [ ] See "Hi, [username]" on homepage
- [ ] See "Log Out" button

### Step 3.5: Test Logout
1. Click "Log Out"
2. Should redirect to homepage without username

- [ ] Logout works
- [ ] Session cleared
- [ ] No longer see username

---

## 🛡️ PHASE 4: SECURITY TESTING (15 minutes)

### Test 4.1: SQL Injection Protection
Try logging in with:
- Username: `admin' OR '1'='1`
- Password: `anything`

**Expected:** Login fails, no error message shown

- [ ] ✅ SQL injection blocked

### Test 4.2: XSS Protection
Try registering with:
- Username: `<script>alert('XSS')</script>`
- Email: `test@test.com`
- Password: `Test123!@#`

**Expected:** Script tags are escaped in display

- [ ] ✅ XSS attack blocked

### Test 4.3: CSRF Protection
1. Login normally
2. Open browser DevTools → Console
3. Run:
```javascript
fetch('/login.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'username=test&password=test'
});
```

**Expected:** Request fails (CSRF token missing)

- [ ] ✅ CSRF protection works

### Test 4.4: Rate Limiting
1. Try logging in with wrong password 6 times
2. On 6th attempt, should see:

**Expected:** "Too many login attempts. Please try again in 15 minutes."

- [ ] ✅ Rate limiting works

### Test 4.5: Session Timeout
**Option A: Wait 31 minutes (not recommended)**
**Option B: Temporarily modify config.php line 54:**

```php
// Change this:
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {

// To this (60 seconds):
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
```

Then:
1. Login
2. Wait 61 seconds
3. Refresh page

**Expected:** Redirected to login with timeout message

- [ ] ✅ Session timeout works
- [ ] REMEMBER to change back to 1800!

### Test 4.6: Password Validation
Try registering with weak passwords:
- `password` (no uppercase/symbol)
- `Pass1` (too short)
- `PASSWORD123` (no lowercase)

**Expected:** Validation errors shown

- [ ] ✅ Password policy enforced

---

## 📁 PHASE 5: PROTECT YOUR COURSE PAGES (20 minutes)

### Step 5.1: Rename HTML Files to PHP
```bash
# In each course folder, rename:
mv crochet/crochetHome.html crochet/crochetHome.php
mv crochet/view/module1.html crochet/view/module1.php
# ... repeat for all course pages
```

### Step 5.2: Add Security to Each PHP File

**At the TOP of each file (before <!DOCTYPE html>):**

```php
<?php
define('SECURE_ACCESS', true);
require_once '../config.php';  // Adjust path as needed
requireLogin();
$username = getUsername();
$userId = getUserId();
?>
```

**Example for crochet/crochetHome.php:**
```php
<?php
define('SECURE_ACCESS', true);
require_once '../config.php';
requireLogin();
?>
<!DOCTYPE html>
<html>
...
```

**Example for crochet/view/module1.php:**
```php
<?php
define('SECURE_ACCESS', true);
require_once '../../config.php';  // Note: two levels up
requireLogin();
?>
<!DOCTYPE html>
<html>
...
```

- [ ] All course home pages converted to .php
- [ ] Security code added to all pages
- [ ] Correct path to config.php used
- [ ] Pages redirect to login when not authenticated

### Step 5.3: Update Navigation Links

In `index.php`, update course links:

```php
// FROM:
<a href="crochet/crochetHome.html">

// TO:
<a href="crochet/crochetHome.php">
```

- [ ] All course links updated to .php
- [ ] Links work correctly

---

## 🔍 PHASE 6: MONITORING SETUP (10 minutes)

### Step 6.1: Verify Logging Works

```bash
# Check if logs are being created
ls -lh logs/

# View recent security events
tail -20 logs/security.log

# Watch logs in real-time
tail -f logs/security.log
```

**Expected:** See login/logout/registration events

- [ ] Logs are being written
- [ ] Security events recorded
- [ ] Timestamps correct

### Step 6.2: Check Database Logs

```sql
USE ubuntu_db;

-- View recent security events
SELECT * FROM security_log 
ORDER BY created_at DESC 
LIMIT 20;

-- View login attempts
SELECT * FROM login_attempts 
ORDER BY attempted_at DESC 
LIMIT 20;
```

- [ ] Database logging works
- [ ] Events recorded in security_log table
- [ ] Login attempts tracked

---

## 🎯 PHASE 7: PRODUCTION PREPARATION (If deploying)

### Step 7.1: Enable HTTPS
- [ ] SSL certificate installed
- [ ] HTTPS working
- [ ] HTTP redirects to HTTPS

### Step 7.2: Update Configuration

In `config.php`:

```php
// Change to 1 for HTTPS
ini_set('session.cookie_secure', 1);

// Hide errors from users
ini_set('display_errors', 0);
```

- [ ] session.cookie_secure = 1
- [ ] display_errors = 0

### Step 7.3: Set File Permissions

```bash
# PHP files readable only by web server
chmod 644 *.php

# Config more restrictive
chmod 600 config.php

# Directories
chmod 755 crochet beads mbira henna
chmod 750 logs
```

- [ ] Permissions set correctly
- [ ] config.php not world-readable

### Step 7.4: Security Headers

Verify security headers are working:

```bash
curl -I https://yoursite.com
```

Should see:
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- Strict-Transport-Security
- Content-Security-Policy

- [ ] All security headers present

---

## 📊 PHASE 8: FINAL VERIFICATION

### Checklist Summary

#### Database
- [ ] ubuntu_db created
- [ ] All tables present
- [ ] Secure user created
- [ ] Passwords changed

#### Configuration
- [ ] config.php created from template
- [ ] Database password set
- [ ] CSRF secret changed
- [ ] Cookie security configured

#### Authentication
- [ ] Registration works
- [ ] Login works
- [ ] Logout works
- [ ] Session timeout works

#### Security
- [ ] SQL injection blocked
- [ ] XSS attacks blocked
- [ ] CSRF protection active
- [ ] Rate limiting works
- [ ] Password policy enforced

#### Course Protection
- [ ] Course pages converted to PHP
- [ ] Authentication added
- [ ] Links updated
- [ ] Redirects working

#### Logging
- [ ] File logging works
- [ ] Database logging works
- [ ] Security events tracked

#### Production (if applicable)
- [ ] HTTPS enabled
- [ ] Security headers active
- [ ] Permissions set
- [ ] Errors hidden from users

---

## 🎉 COMPLETION

If all checkboxes are checked, congratulations! Your Ubuntu Skills platform is:

✅ **Fully Secured** - OWASP Top 10 protected
✅ **Production Ready** - Enterprise-grade security
✅ **Properly Authenticated** - Secure sessions & passwords
✅ **Attack Resistant** - Multiple layers of protection
✅ **Fully Monitored** - Complete audit trail
✅ **Well Documented** - README & SECURITY_GUIDE

---

## 📚 NEXT STEPS

1. **Read Documentation**
   - README.md - Usage guide
   - SECURITY_GUIDE.md - Deep dive into security

2. **Test Thoroughly**
   - Create test accounts
   - Try all features
   - Verify logging

3. **Monitor Regularly**
   - Check logs daily
   - Review security events
   - Update as needed

4. **Stay Secure**
   - Regular backups
   - Keep PHP/MySQL updated
   - Monitor for vulnerabilities

---

## 🆘 TROUBLESHOOTING

If you encounter issues during setup:

1. **Check logs:** `logs/security.log` and `logs/php-errors.log`
2. **Verify database:** Test connection with mysql command
3. **Check permissions:** Ensure files are readable by web server
4. **Review config.php:** Verify all settings are correct
5. **Consult SECURITY_GUIDE.md:** Contains detailed troubleshooting

---

**Your secure learning platform is ready! 🚀**
