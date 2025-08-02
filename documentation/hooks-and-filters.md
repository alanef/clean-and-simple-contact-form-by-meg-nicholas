# Clean and Simple Contact Form - Hooks and Filters Documentation

This document describes all available hooks and filters in the Clean and Simple Contact Form plugin for developers who want to extend its functionality.

## Table of Contents

- [Form Submission Hooks](#form-submission-hooks)
- [Email Processing Hooks](#email-processing-hooks)
- [Data Filtering Hooks](#data-filtering-hooks)
- [Email Content Filters](#email-content-filters)
- [Spam Filtering](#spam-filtering)
- [Code Examples](#code-examples)

---

## Form Submission Hooks

These action hooks fire at different stages of form submission, allowing you to perform custom actions like logging, CRM integration, or analytics tracking.

### `cscf_form_submitted`

Fires after any successful form submission (standard, AJAX, or REST API).

**Parameters:**
- `$contact` (object) - The cscf_Contact object containing all form data

**Example:**
```php
add_action('cscf_form_submitted', function($contact) {
    // Log all submissions
    error_log('Form submitted from: ' . $contact->Email);
});
```

### `cscf_form_submitted_standard`

Fires only after successful standard (non-AJAX) form submission.

**Parameters:**
- `$contact` (object) - The cscf_Contact object

**Example:**
```php
add_action('cscf_form_submitted_standard', function($contact) {
    // Track standard form submissions
    do_action('my_analytics_event', 'contact_form', 'standard_submit');
});
```

### `cscf_form_submitted_ajax`

Fires only after successful AJAX form submission.

**Parameters:**
- `$contact` (object) - The cscf_Contact object

**Example:**
```php
add_action('cscf_form_submitted_ajax', function($contact) {
    // Handle AJAX-specific logic
    update_option('last_ajax_submission', current_time('mysql'));
});
```

### `cscf_form_submitted_rest`

Fires only after successful REST API form submission.

**Parameters:**
- `$contact` (object) - The cscf_Contact object

**Example:**
```php
add_action('cscf_form_submitted_rest', function($contact) {
    // Log REST API submissions
    $log_entry = [
        'type' => 'rest_api',
        'email' => $contact->Email,
        'timestamp' => current_time('mysql')
    ];
    update_option('cscf_api_log', $log_entry);
});
```

---

## Email Processing Hooks

These hooks allow you to perform actions before and after email sending.

### `cscf_before_send_email`

Fires immediately before attempting to send the email.

**Parameters:**
- `$contact` (object) - The cscf_Contact object

**Example:**
```php
add_action('cscf_before_send_email', function($contact) {
    // Add to queue for batch processing
    $queue = get_option('email_queue', []);
    $queue[] = [
        'to' => $contact->Email,
        'subject' => 'Contact Form Submission',
        'time' => current_time('mysql')
    ];
    update_option('email_queue', $queue);
});
```

### `cscf_after_send_email`

Fires immediately after attempting to send the email.

**Parameters:**
- `$contact` (object) - The cscf_Contact object
- `$result` (boolean) - Whether the email was sent successfully

**Example:**
```php
add_action('cscf_after_send_email', function($contact, $result) {
    if (!$result) {
        // Handle failed email
        error_log('Failed to send email to: ' . implode(', ', cscf_PluginSettings::RecipientEmails()));
        
        // Maybe try alternative method
        wp_mail_alternative($contact);
    }
}, 10, 2);
```

---

## Data Filtering Hooks

These filters allow you to modify form data before processing.

### `cscf_form_data`

Filters form data before it's processed (REST API only).

**Parameters:**
- `$data` (array) - The form data array
- `$post_id` (int|null) - The post ID where form was submitted
- `$is_rest_api` (boolean) - Whether this is a REST API request

**Returns:** Modified data array

**Example:**
```php
add_filter('cscf_form_data', function($data, $post_id, $is_rest_api) {
    // Add tracking information
    $data['source_post_id'] = $post_id;
    $data['submission_ip'] = $_SERVER['REMOTE_ADDR'];
    
    // Normalize phone numbers
    if (isset($data['phone-number'])) {
        $data['phone-number'] = preg_replace('/[^0-9+]/', '', $data['phone-number']);
    }
    
    return $data;
}, 10, 3);
```

---

## Email Content Filters

These filters allow you to modify the email content before sending.

### `cscf_email_emails`

Filters the recipient email addresses.

**Parameters:**
- `$emails` (array) - Array of recipient email addresses

**Returns:** Modified array of email addresses

**Example:**
```php
add_filter('cscf_email_emails', function($emails) {
    // Add CC based on form content
    if (strpos($_POST['cscf']['message'], 'urgent') !== false) {
        $emails[] = 'priority@example.com';
    }
    return $emails;
});
```

### `cscf_email_subject`

Filters the email subject line.

**Parameters:**
- `$subject` (string) - The email subject

**Returns:** Modified subject string

**Example:**
```php
add_filter('cscf_email_subject', function($subject) {
    // Add ticket number
    $ticket_num = get_option('cscf_ticket_counter', 1000) + 1;
    update_option('cscf_ticket_counter', $ticket_num);
    return sprintf('[Ticket #%d] %s', $ticket_num, $subject);
});
```

### `cscf_email_message`

Filters the email message body.

**Parameters:**
- `$message` (string) - The email message body

**Returns:** Modified message string

**Example:**
```php
add_filter('cscf_email_message', function($message) {
    // Add footer
    $footer = "\n\n---\nThis message was sent from the contact form on " . get_bloginfo('name');
    return $message . $footer;
});
```

### `cscf_email_header`

Filters the email headers.

**Parameters:**
- `$header` (string) - The email headers

**Returns:** Modified header string

**Example:**
```php
add_filter('cscf_email_header', function($header) {
    // Add custom headers
    $header .= "X-Mailer: CSCF Plugin\r\n";
    $header .= "X-Priority: 3\r\n";
    return $header;
});
```

---

## Spam Filtering

### `cscf_spamfilter`

Allows custom spam filtering logic.

**Parameters:**
- `$contact` (object) - The cscf_Contact object (passed by reference)

**Note:** Set `$contact->IsSpam = true` to mark as spam

**Example:**
```php
add_filter('cscf_spamfilter', function($contact) {
    // Check for common spam patterns
    $spam_words = ['viagra', 'cialis', 'casino', 'lottery'];
    $message_lower = strtolower($contact->Message);
    
    foreach ($spam_words as $word) {
        if (strpos($message_lower, $word) !== false) {
            $contact->IsSpam = true;
            break;
        }
    }
    
    // Check for too many links
    if (substr_count($contact->Message, 'http') > 3) {
        $contact->IsSpam = true;
    }
});
```

---

## Code Examples

### Example 1: CRM Integration

```php
// Send form data to HubSpot
add_action('cscf_form_submitted', function($contact) {
    $hubspot_api_key = 'your-api-key';
    $portal_id = 'your-portal-id';
    $form_guid = 'your-form-guid';
    
    $endpoint = "https://api.hsforms.com/submissions/v3/integration/submit/{$portal_id}/{$form_guid}";
    
    $data = [
        'fields' => [
            ['name' => 'email', 'value' => $contact->Email],
            ['name' => 'firstname', 'value' => $contact->Name],
            ['name' => 'message', 'value' => $contact->Message]
        ],
        'context' => [
            'pageUri' => get_permalink($contact->PostID),
            'pageName' => get_the_title($contact->PostID)
        ]
    ];
    
    wp_remote_post($endpoint, [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $hubspot_api_key
        ],
        'body' => json_encode($data)
    ]);
});
```

### Example 2: Webhook Integration

```php
// Send to Zapier webhook
add_action('cscf_form_submitted', function($contact) {
    $webhook_url = 'https://hooks.zapier.com/hooks/catch/123456/abcdef/';
    
    $data = [
        'name' => $contact->Name,
        'email' => $contact->Email,
        'message' => $contact->Message,
        'phone' => $contact->PhoneNumber,
        'consent' => $contact->ContactConsent,
        'timestamp' => current_time('c'),
        'source_url' => get_permalink($contact->PostID)
    ];
    
    wp_remote_post($webhook_url, [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode($data),
        'timeout' => 5
    ]);
});
```

### Example 3: Custom Logging

```php
// Create custom log table and log all submissions
add_action('cscf_form_submitted', function($contact) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'cscf_submissions';
    
    $wpdb->insert($table_name, [
        'email' => $contact->Email,
        'name' => $contact->Name,
        'message' => $contact->Message,
        'phone' => $contact->PhoneNumber,
        'consent' => $contact->ContactConsent ? 1 : 0,
        'post_id' => $contact->PostID,
        'spam_status' => $contact->IsSpam,
        'submitted_at' => current_time('mysql')
    ]);
});
```

### Example 4: Dynamic Email Routing

```php
// Route emails based on message content
add_filter('cscf_email_emails', function($emails) {
    $message = $_POST['cscf']['message'] ?? '';
    
    // Route to different departments
    if (stripos($message, 'support') !== false) {
        return ['support@example.com'];
    } elseif (stripos($message, 'sales') !== false) {
        return ['sales@example.com'];
    } elseif (stripos($message, 'billing') !== false) {
        return ['billing@example.com'];
    }
    
    return $emails; // Default recipients
});
```

### Example 5: Add to Mailing List

```php
// Add to Mailchimp
add_action('cscf_form_submitted', function($contact) {
    if (!$contact->ContactConsent) {
        return; // Only add if consent given
    }
    
    $api_key = 'your-mailchimp-api-key';
    $list_id = 'your-list-id';
    $data_center = 'us1'; // From your API key
    
    $url = "https://{$data_center}.api.mailchimp.com/3.0/lists/{$list_id}/members";
    
    $data = [
        'email_address' => $contact->Email,
        'status' => 'subscribed',
        'merge_fields' => [
            'FNAME' => $contact->Name
        ]
    ];
    
    wp_remote_post($url, [
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode('anystring:' . $api_key)
        ],
        'body' => json_encode($data)
    ]);
});
```

---

## Contact Object Properties

The `$contact` object passed to hooks contains these properties:

- `Name` - Sender's name
- `Email` - Sender's email address
- `ConfirmEmail` - Confirmed email address
- `Message` - The message content
- `EmailToSender` - Whether to send copy to sender
- `PhoneNumber` - Phone number (if enabled)
- `ContactConsent` - Consent checkbox value
- `PostID` - ID of the post/page containing the form
- `IsSpam` - Spam status
- `Errors` - Array of validation errors
- `RecaptchaPublicKey` - reCAPTCHA public key
- `RecaptchaPrivateKey` - reCAPTCHA private key