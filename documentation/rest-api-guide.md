# Clean and Simple Contact Form - REST API Guide

This guide explains how to use the REST API endpoint to submit contact forms programmatically, perfect for headless WordPress implementations, mobile apps, and external integrations.

## Table of Contents

- [Overview](#overview)
- [Enabling the REST API](#enabling-the-rest-api)
- [Authentication](#authentication)
- [API Endpoint](#api-endpoint)
- [Request Format](#request-format)
- [Response Format](#response-format)
- [Code Examples](#code-examples)
- [Use Cases](#use-cases)
- [Troubleshooting](#troubleshooting)

---

## Overview

The REST API allows authenticated users to submit contact forms without using the traditional web interface. This is ideal for:

- Headless WordPress implementations
- Mobile applications
- External websites
- Automated systems
- Single Page Applications (SPAs)

**Key Features:**
- Secure authentication required
- All form validations apply (except reCAPTCHA)
- Same email delivery as regular forms
- Full integration with hooks and filters
- JSON request/response format

---

## Enabling the REST API

The REST API is disabled by default for security. To enable it:

1. Navigate to **Settings → Contact Form** in WordPress admin
2. Find the **REST API Settings** section
3. Check **Enable REST API**
4. Set the **Required User Capability** (default: `edit_posts`)
5. Click **Save Changes**

### Security Considerations

- Only authenticated users with the specified capability can submit forms
- Consider using a custom capability for better control
- Monitor API usage through hooks
- Use HTTPS in production

---

## Authentication

The REST API requires WordPress authentication. Several methods are available:

### Application Passwords (Recommended for WordPress 5.6+)

1. Go to **Users → Profile** in WordPress admin
2. Scroll to **Application Passwords**
3. Enter a name and click **Add New Application Password**
4. Save the generated password (shown only once)

**Usage:**
```bash
curl -u "username:application-password" https://site.com/wp-json/cscf/v1/submit
```

### Basic Authentication (Development Only)

For local development, you can use regular WordPress credentials:

```bash
curl -u "username:password" http://localhost/wp-json/cscf/v1/submit
```

**Warning:** Never use basic auth with regular passwords in production!

### JWT Authentication (via Plugin)

Install a JWT plugin like "JWT Authentication for WP REST API":

```javascript
// First, get a token
const response = await fetch('https://site.com/wp-json/jwt-auth/v1/token', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    username: 'your-username',
    password: 'your-password'
  })
});

const { token } = await response.json();

// Then use the token
const formResponse = await fetch('https://site.com/wp-json/cscf/v1/submit', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${token}`
  },
  body: JSON.stringify(formData)
});
```

---

## API Endpoint

### Endpoint URL
```
POST /wp-json/cscf/v1/submit
```

### Full URL Example
```
https://yoursite.com/wp-json/cscf/v1/submit
```

### HTTP Method
Only `POST` requests are accepted.

---

## Request Format

Send a JSON object with the following fields:

### Required Fields

| Field | Type | Description |
|-------|------|-------------|
| `name` | string | Sender's name |
| `email` | string | Valid email address |
| `message` | string | Message content |

### Conditional Fields

| Field | Type | Required When | Description |
|-------|------|---------------|-------------|
| `confirm_email` | string | Email confirmation enabled | Must match `email` field |
| `phone_number` | string | Phone field is mandatory | Contact phone number |
| `contact_consent` | boolean | Consent checkbox enabled | GDPR consent |

### Optional Fields

| Field | Type | Description |
|-------|------|-------------|
| `email_sender` | boolean | Send copy to sender (default: false) |
| `post_id` | integer | Source page/post ID |

### Example Request

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "confirm_email": "john@example.com",
  "message": "I would like more information about your services.",
  "phone_number": "+1-555-123-4567",
  "contact_consent": true,
  "email_sender": true,
  "post_id": 42
}
```

---

## Response Format

### Success Response

**HTTP Status:** 200 OK

```json
{
  "success": true,
  "message": "Message Sent"
}
```

### Validation Error Response

**HTTP Status:** 400 Bad Request

```json
{
  "code": "validation_failed",
  "message": "Validation failed.",
  "data": {
    "status": 400,
    "errors": {
      "email": "Please enter a valid email address.",
      "message": "Please enter a message.",
      "confirm-email": "Sorry the email addresses do not match."
    }
  }
}
```

### Authentication Error Response

**HTTP Status:** 401 Unauthorized

```json
{
  "code": "rest_forbidden",
  "message": "Authentication required.",
  "data": {
    "status": 401
  }
}
```

### Permission Error Response

**HTTP Status:** 403 Forbidden

```json
{
  "code": "rest_forbidden",
  "message": "Insufficient permissions.",
  "data": {
    "status": 403
  }
}
```

### Server Error Response

**HTTP Status:** 500 Internal Server Error

```json
{
  "code": "email_failed",
  "message": "Failed to send email.",
  "data": {
    "status": 500
  }
}
```

---

## Code Examples

### JavaScript (Fetch API)

```javascript
async function submitContactForm(formData) {
  const username = 'your-username';
  const appPassword = 'xxxx xxxx xxxx xxxx xxxx xxxx'; // Application password
  
  try {
    const response = await fetch('https://yoursite.com/wp-json/cscf/v1/submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Basic ' + btoa(username + ':' + appPassword)
      },
      body: JSON.stringify({
        name: formData.name,
        email: formData.email,
        confirm_email: formData.email,
        message: formData.message,
        contact_consent: true
      })
    });

    const result = await response.json();

    if (response.ok) {
      console.log('Success:', result.message);
      return { success: true, message: result.message };
    } else {
      console.error('Error:', result);
      return { success: false, errors: result.data.errors };
    }
  } catch (error) {
    console.error('Network error:', error);
    return { success: false, error: 'Network error' };
  }
}

// Usage
submitContactForm({
  name: 'Jane Smith',
  email: 'jane@example.com',
  message: 'Testing the REST API'
}).then(result => {
  if (result.success) {
    alert('Message sent successfully!');
  } else {
    alert('Error sending message');
  }
});
```

### PHP

```php
function submit_contact_form($data) {
    $username = 'your-username';
    $app_password = 'xxxx xxxx xxxx xxxx xxxx xxxx';
    
    $args = [
        'body' => json_encode([
            'name' => $data['name'],
            'email' => $data['email'],
            'confirm_email' => $data['email'],
            'message' => $data['message'],
            'contact_consent' => true
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($username . ':' . $app_password)
        ],
        'timeout' => 30
    ];
    
    $response = wp_remote_post('https://yoursite.com/wp-json/cscf/v1/submit', $args);
    
    if (is_wp_error($response)) {
        return ['error' => $response->get_error_message()];
    }
    
    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Usage
$result = submit_contact_form([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'message' => 'This is a test message'
]);

if ($result['success']) {
    echo 'Message sent!';
} else {
    echo 'Error: ' . print_r($result['data']['errors'], true);
}
```

### Python

```python
import requests
import base64
import json

def submit_contact_form(form_data):
    url = 'https://yoursite.com/wp-json/cscf/v1/submit'
    username = 'your-username'
    app_password = 'xxxx xxxx xxxx xxxx xxxx xxxx'
    
    # Create auth header
    credentials = f"{username}:{app_password}"
    auth_header = base64.b64encode(credentials.encode()).decode()
    
    headers = {
        'Content-Type': 'application/json',
        'Authorization': f'Basic {auth_header}'
    }
    
    data = {
        'name': form_data['name'],
        'email': form_data['email'],
        'confirm_email': form_data['email'],
        'message': form_data['message'],
        'contact_consent': True
    }
    
    try:
        response = requests.post(url, json=data, headers=headers)
        result = response.json()
        
        if response.status_code == 200:
            return {'success': True, 'message': result['message']}
        else:
            return {'success': False, 'errors': result.get('data', {}).get('errors', {})}
    except requests.exceptions.RequestException as e:
        return {'success': False, 'error': str(e)}

# Usage
result = submit_contact_form({
    'name': 'Python Test',
    'email': 'python@example.com',
    'message': 'Testing from Python'
})

if result['success']:
    print(f"Success: {result['message']}")
else:
    print(f"Error: {result}")
```

### cURL

```bash
# Using application password
curl -X POST https://yoursite.com/wp-json/cscf/v1/submit \
  -u "username:xxxx xxxx xxxx xxxx xxxx xxxx" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "cURL Test",
    "email": "test@example.com",
    "confirm_email": "test@example.com",
    "message": "Testing from command line",
    "contact_consent": true
  }'

# Using base64 encoded credentials
AUTH=$(echo -n "username:password" | base64)
curl -X POST https://yoursite.com/wp-json/cscf/v1/submit \
  -H "Authorization: Basic $AUTH" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "cURL Test",
    "email": "test@example.com",
    "confirm_email": "test@example.com",
    "message": "Testing from command line"
  }'
```

### React Example

```jsx
import React, { useState } from 'react';

function ContactForm() {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    message: ''
  });
  const [status, setStatus] = useState('');
  const [errors, setErrors] = useState({});

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus('sending');
    setErrors({});

    const username = process.env.REACT_APP_WP_USERNAME;
    const appPassword = process.env.REACT_APP_WP_APP_PASSWORD;

    try {
      const response = await fetch(`${process.env.REACT_APP_WP_URL}/wp-json/cscf/v1/submit`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': 'Basic ' + btoa(username + ':' + appPassword)
        },
        body: JSON.stringify({
          ...formData,
          confirm_email: formData.email,
          contact_consent: true
        })
      });

      const result = await response.json();

      if (response.ok) {
        setStatus('success');
        setFormData({ name: '', email: '', message: '' });
      } else {
        setStatus('error');
        setErrors(result.data?.errors || {});
      }
    } catch (error) {
      setStatus('error');
      setErrors({ general: 'Network error. Please try again.' });
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <div>
        <label>Name:</label>
        <input
          type="text"
          value={formData.name}
          onChange={(e) => setFormData({...formData, name: e.target.value})}
          required
        />
        {errors.name && <span className="error">{errors.name}</span>}
      </div>

      <div>
        <label>Email:</label>
        <input
          type="email"
          value={formData.email}
          onChange={(e) => setFormData({...formData, email: e.target.value})}
          required
        />
        {errors.email && <span className="error">{errors.email}</span>}
      </div>

      <div>
        <label>Message:</label>
        <textarea
          value={formData.message}
          onChange={(e) => setFormData({...formData, message: e.target.value})}
          required
        />
        {errors.message && <span className="error">{errors.message}</span>}
      </div>

      <button type="submit" disabled={status === 'sending'}>
        {status === 'sending' ? 'Sending...' : 'Send Message'}
      </button>

      {status === 'success' && <p className="success">Message sent successfully!</p>}
      {status === 'error' && errors.general && <p className="error">{errors.general}</p>}
    </form>
  );
}

export default ContactForm;
```

---

## Use Cases

### Headless WordPress with Next.js

```javascript
// pages/api/contact.js
export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ error: 'Method not allowed' });
  }

  const response = await fetch(process.env.WP_API_URL + '/cscf/v1/submit', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': `Basic ${process.env.WP_API_AUTH}`
    },
    body: JSON.stringify(req.body)
  });

  const data = await response.json();
  res.status(response.status).json(data);
}
```

### Mobile App Integration

```swift
// Swift example for iOS
func submitContactForm(name: String, email: String, message: String) {
    let url = URL(string: "https://yoursite.com/wp-json/cscf/v1/submit")!
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.setValue("application/json", forHTTPHeaderField: "Content-Type")
    
    let authString = "\(username):\(appPassword)"
    let authData = authString.data(using: .utf8)!.base64EncodedString()
    request.setValue("Basic \(authData)", forHTTPHeaderField: "Authorization")
    
    let body = [
        "name": name,
        "email": email,
        "confirm_email": email,
        "message": message,
        "contact_consent": true
    ] as [String : Any]
    
    request.httpBody = try? JSONSerialization.data(withJSONObject: body)
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        // Handle response
    }.resume()
}
```

### Automated Testing

```javascript
// Jest test example
describe('Contact Form API', () => {
  test('should submit form successfully', async () => {
    const formData = {
      name: 'Test User',
      email: 'test@example.com',
      confirm_email: 'test@example.com',
      message: 'Automated test message',
      contact_consent: true
    };

    const response = await fetch('https://yoursite.com/wp-json/cscf/v1/submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Basic ${process.env.TEST_AUTH}`
      },
      body: JSON.stringify(formData)
    });

    const result = await response.json();
    
    expect(response.status).toBe(200);
    expect(result.success).toBe(true);
    expect(result.message).toBe('Message Sent');
  });
});
```

---

## Troubleshooting

### Common Issues

#### 404 Not Found
- **Cause**: REST API not enabled or permalinks not set
- **Solution**: 
  1. Enable REST API in plugin settings
  2. Go to Settings → Permalinks and save (even without changes)
  3. Ensure pretty permalinks are enabled (not "Plain")

#### 401 Unauthorized
- **Cause**: Missing or invalid authentication
- **Solution**: 
  1. Verify credentials are correct
  2. Check application password is properly formatted
  3. Ensure user exists and is active

#### 403 Forbidden
- **Cause**: User lacks required capability
- **Solution**: 
  1. Check user has the capability set in plugin settings
  2. Default is `edit_posts` - verify user role has this
  3. Consider using a custom capability for better control

#### 400 Bad Request
- **Cause**: Invalid JSON or missing required fields
- **Solution**: 
  1. Validate JSON syntax
  2. Ensure all required fields are included
  3. Check field names match exactly (case-sensitive)

#### 500 Internal Server Error
- **Cause**: Email sending failure
- **Solution**: 
  1. Check WordPress email configuration
  2. Verify SMTP settings if configured
  3. Check server email logs
  4. Test with wp_mail() directly

### Debug Mode

Enable WordPress debug mode to get more detailed error information:

```php
// In wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

### Testing Tools

#### Postman
1. Create a new POST request
2. Set URL to `https://yoursite.com/wp-json/cscf/v1/submit`
3. In Authorization tab, select "Basic Auth"
4. Enter username and application password
5. In Body tab, select "raw" and "JSON"
6. Enter your JSON data

#### Browser Console
```javascript
// Quick test in browser console
fetch('/wp-json/cscf/v1/submit', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Basic ' + btoa('username:password')
  },
  body: JSON.stringify({
    name: 'Console Test',
    email: 'test@example.com',
    confirm_email: 'test@example.com',
    message: 'Testing from browser console'
  })
}).then(r => r.json()).then(console.log);
```

### Rate Limiting

Consider implementing rate limiting to prevent abuse:

```php
// Example using transients
add_action('cscf_before_send_email', function($contact) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = 'cscf_rate_limit_' . md5($ip);
    $count = get_transient($key) ?: 0;
    
    if ($count >= 10) { // 10 submissions per hour
        wp_die('Rate limit exceeded', 429);
    }
    
    set_transient($key, $count + 1, HOUR_IN_SECONDS);
});
```

### Security Best Practices

1. **Always use HTTPS** in production
2. **Never expose** regular passwords
3. **Use application passwords** or JWT tokens
4. **Implement rate limiting** to prevent abuse
5. **Monitor usage** through logging
6. **Restrict capabilities** to minimum needed
7. **Validate and sanitize** all inputs
8. **Keep WordPress and plugins** updated