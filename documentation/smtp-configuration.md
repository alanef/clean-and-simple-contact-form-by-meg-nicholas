# Clean and Simple Contact Form - SMTP Configuration Guide

This guide explains how to configure SMTP settings for reliable email delivery with the Clean and Simple Contact Form plugin.

## Table of Contents

- [Overview](#overview)
- [Configuration via Constants](#configuration-via-constants)
- [Available Constants](#available-constants)
- [Popular Email Service Configurations](#popular-email-service-configurations)
- [Local Development with Mailpit](#local-development-with-mailpit)
- [Testing Your Configuration](#testing-your-configuration)
- [Troubleshooting](#troubleshooting)
- [Security Best Practices](#security-best-practices)

---

## Overview

By default, WordPress uses the PHP `mail()` function which can be unreliable. SMTP (Simple Mail Transfer Protocol) configuration ensures better email deliverability by using authenticated email servers.

The Clean and Simple Contact Form plugin supports SMTP configuration through WordPress constants, allowing you to:

- Configure SMTP settings at the server level
- Keep sensitive credentials out of the database
- Use different settings for different environments
- Integrate with any SMTP service

---

## Configuration via Constants

SMTP settings are configured by adding constants to your `wp-config.php` file. This approach is:

- **Secure**: Credentials aren't stored in the database
- **Environment-specific**: Different settings for dev/staging/production
- **Version-controlled**: Can be included in deployment scripts
- **Plugin-agnostic**: Works alongside other SMTP plugins

### Basic Setup

Add these lines to your `wp-config.php` file (above the `/* That's all, stop editing! */` line):

```php
// Enable SMTP
define('CSCF_USE_SMTP', true);

// SMTP Server Settings
define('CSCF_SMTP_HOST', 'smtp.example.com');
define('CSCF_SMTP_PORT', 587);

// Authentication
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'your-email@example.com');
define('CSCF_SMTP_PASS', 'your-password');

// Security
define('CSCF_SMTP_SECURE', 'tls'); // 'tls' or 'ssl'
```

---

## Available Constants

### Core Settings

| Constant | Type | Description | Required |
|----------|------|-------------|----------|
| `CSCF_USE_SMTP` | boolean | Enable SMTP configuration | Yes |
| `CSCF_SMTP_HOST` | string | SMTP server hostname | Yes |
| `CSCF_SMTP_PORT` | integer | SMTP server port | Yes |

### Authentication

| Constant | Type | Description | Default |
|----------|------|-------------|---------|
| `CSCF_SMTP_AUTH` | boolean | Enable SMTP authentication | false |
| `CSCF_SMTP_USER` | string | SMTP username | - |
| `CSCF_SMTP_PASS` | string | SMTP password | - |

### Security & Advanced

| Constant | Type | Description | Options |
|----------|------|-------------|---------|
| `CSCF_SMTP_SECURE` | string | Encryption method | '', 'ssl', 'tls' |
| `CSCF_SMTP_FROM` | string | Override sender email | - |
| `CSCF_SMTP_FROM_NAME` | string | Override sender name | - |
| `CSCF_SMTP_DEBUG` | boolean | Enable debug output | false |

---

## Popular Email Service Configurations

### Gmail / Google Workspace

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.gmail.com');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'your-email@gmail.com');
define('CSCF_SMTP_PASS', 'your-app-password'); // Use App Password, not regular password!
define('CSCF_SMTP_SECURE', 'tls');
```

**Important**: Gmail requires an [App Password](https://support.google.com/accounts/answer/185833) instead of your regular password.

### SendGrid

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.sendgrid.net');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'apikey'); // Always 'apikey' for SendGrid
define('CSCF_SMTP_PASS', 'your-sendgrid-api-key');
define('CSCF_SMTP_SECURE', 'tls');
```

### Mailgun

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.mailgun.org');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'postmaster@your-domain.mailgun.org');
define('CSCF_SMTP_PASS', 'your-mailgun-password');
define('CSCF_SMTP_SECURE', 'tls');
```

### Amazon SES

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'email-smtp.us-east-1.amazonaws.com'); // Change region as needed
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'your-ses-smtp-username');
define('CSCF_SMTP_PASS', 'your-ses-smtp-password');
define('CSCF_SMTP_SECURE', 'tls');
```

### Office 365 / Outlook.com

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.office365.com');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'your-email@domain.com');
define('CSCF_SMTP_PASS', 'your-password');
define('CSCF_SMTP_SECURE', 'tls');
```

### Postmark

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.postmarkapp.com');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'your-postmark-server-token');
define('CSCF_SMTP_PASS', 'your-postmark-server-token'); // Same as username
define('CSCF_SMTP_SECURE', 'tls');
```

### SparkPost

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'smtp.sparkpostmail.com');
define('CSCF_SMTP_PORT', 587);
define('CSCF_SMTP_AUTH', true);
define('CSCF_SMTP_USER', 'SMTP_Injection');
define('CSCF_SMTP_PASS', 'your-sparkpost-api-key');
define('CSCF_SMTP_SECURE', 'tls');
```

---

## Local Development with Mailpit

Mailpit is perfect for catching emails during development without sending real emails.

### Docker Setup

1. Create `docker-compose.mailpit.yml`:

```yaml
version: '3.8'

services:
  mailpit:
    image: axllent/mailpit:latest
    container_name: mailpit
    ports:
      - "1025:1025"  # SMTP port
      - "8025:8025"  # Web UI port
```

2. Start Mailpit:
```bash
docker compose -f docker-compose.mailpit.yml up -d
```

3. Configure WordPress:

```php
define('CSCF_USE_SMTP', true);
define('CSCF_SMTP_HOST', 'localhost'); // or 'host.docker.internal' if WP is in Docker
define('CSCF_SMTP_PORT', 1025);
define('CSCF_SMTP_AUTH', false);
define('CSCF_SMTP_SECURE', '');
```

4. View emails at: http://localhost:8025

### Using with wp-env

If using wp-env for development, add to `.wp-env.override.json`:

```json
{
  "config": {
    "CSCF_USE_SMTP": true,
    "CSCF_SMTP_HOST": "host.docker.internal",
    "CSCF_SMTP_PORT": 1025,
    "CSCF_SMTP_AUTH": false,
    "CSCF_SMTP_SECURE": ""
  }
}
```

---

## Testing Your Configuration

### Method 1: Debug Mode

Enable debug mode to see SMTP communication:

```php
define('CSCF_SMTP_DEBUG', true);
```

This will output detailed SMTP logs when sending emails.

### Method 2: Test Email Script

Create a test file in your WordPress root:

```php
<?php
// test-smtp.php
require_once('wp-load.php');

// Test email
$to = 'test@example.com';
$subject = 'SMTP Test';
$message = 'This is a test email from Clean and Simple Contact Form.';
$headers = array('Content-Type: text/plain; charset=UTF-8');

$result = wp_mail($to, $subject, $message, $headers);

if ($result) {
    echo "Email sent successfully!";
} else {
    echo "Email failed to send.";
    
    // Check for errors
    global $phpmailer;
    if (isset($phpmailer->ErrorInfo)) {
        echo "\nError: " . $phpmailer->ErrorInfo;
    }
}
```

### Method 3: Use the Contact Form

Simply submit a test message through the contact form and check if it's delivered.

---

## Troubleshooting

### Common Issues

#### "Could not connect to SMTP host"
- **Check**: Firewall settings, port numbers
- **Try**: Different ports (25, 587, 465, 2525)
- **Verify**: Host name is correct

#### "SMTP authentication failed"
- **Check**: Username and password
- **Note**: Some services require app passwords
- **Verify**: Account isn't locked or requires 2FA

#### "Connection timeout"
- **Check**: Server allows outbound SMTP
- **Try**: Increasing timeout in code
- **Consider**: Using a different port

#### Emails marked as spam
- **Set**: Proper FROM address
- **Use**: SPF, DKIM records
- **Avoid**: Spam trigger words

### Debug Output

When `CSCF_SMTP_DEBUG` is enabled, look for output like:

```
SMTP -> FROM SERVER: 220 smtp.gmail.com ESMTP
SMTP -> FROM SERVER: 250 smtp.gmail.com at your service
SMTP -> FROM SERVER: 334 VXNlcm5hbWU6
SMTP -> FROM SERVER: 334 UGFzc3dvcmQ6
SMTP -> FROM SERVER: 235 2.7.0 Accepted
```

### Server Requirements

Ensure your server:
- Allows outbound SMTP connections
- Has required PHP extensions (openssl for TLS/SSL)
- Isn't blocking SMTP ports
- Has correct DNS resolution

### Testing Commands

Test connectivity:
```bash
# Test connection
telnet smtp.gmail.com 587

# Test with OpenSSL
openssl s_client -connect smtp.gmail.com:587 -starttls smtp
```

---

## Security Best Practices

### 1. Use Environment Variables

Instead of hardcoding credentials:

```php
define('CSCF_SMTP_USER', getenv('SMTP_USERNAME'));
define('CSCF_SMTP_PASS', getenv('SMTP_PASSWORD'));
```

### 2. Restrict File Permissions

Ensure `wp-config.php` has proper permissions:
```bash
chmod 600 wp-config.php
```

### 3. Use Encrypted Connections

Always use TLS or SSL when available:
```php
define('CSCF_SMTP_SECURE', 'tls');
```

### 4. Implement Rate Limiting

Prevent abuse by limiting emails:
```php
add_action('cscf_before_send_email', function($contact) {
    $count = get_transient('cscf_email_count_' . date('YmdH'));
    if ($count > 100) { // 100 emails per hour
        wp_die('Rate limit exceeded', 429);
    }
    set_transient('cscf_email_count_' . date('YmdH'), $count + 1, HOUR_IN_SECONDS);
});
```

### 5. Monitor Email Logs

Keep track of sent emails:
```php
add_action('cscf_after_send_email', function($contact, $result) {
    if ($result) {
        error_log(sprintf(
            '[CSCF] Email sent to %s from %s at %s',
            implode(', ', cscf_PluginSettings::RecipientEmails()),
            $contact->Email,
            current_time('mysql')
        ));
    }
}, 10, 2);
```

### 6. Use Application Passwords

For services that support it, use application-specific passwords instead of your main account password.

### 7. Regular Updates

Keep WordPress, plugins, and server software updated for security patches.

### 8. Backup Configuration

Document your SMTP settings securely for disaster recovery.

---

## Alternative Solutions

If you need more advanced features, consider dedicated SMTP plugins:

- **WP Mail SMTP**: User-friendly interface with logging
- **Post SMTP**: Advanced diagnostics and OAuth support
- **Easy WP SMTP**: Simple configuration with test features
- **FluentSMTP**: Modern interface with email logging

These plugins work alongside Clean and Simple Contact Form and provide:
- GUI configuration
- Email logging
- Advanced authentication (OAuth)
- Detailed error reporting
- Multiple mailer options

The constant-based approach in Clean and Simple Contact Form is designed to complement these solutions, not replace them.