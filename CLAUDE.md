# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is the **Clean and Simple Contact Form** WordPress plugin - a Bootstrap-styled AJAX contact form with reCAPTCHA support and spam filtering capabilities.

## Common Development Commands

### Code Quality Checks
- `composer check` - Run all PHP compatibility and coding standards checks
- `composer phpcs` - Run PHP CodeSniffer with security rules
- `composer compat:7.4` through `composer compat:8.3` - Test PHP compatibility for specific versions

### Build and Deploy
- `composer build` - Create distributable ZIP file in `zipped/` directory
- `composer update` - Update dependencies and regenerate POT file for translations

### Testing
- PHPUnit is configured but no test files exist yet in the plugin directory

## Architecture Overview

### Plugin Structure
The plugin follows an object-oriented architecture with clear separation of concerns:

- **Main Plugin File**: `clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php`
- **Core Classes** (in plugin root):
  - `class.cscf.php` - Main plugin controller
  - `class.cscf_contact.php` - Form data handling and email sending
  - `class.cscf_settings.php` - Settings page management
  - `class.cscf_pluginsettings.php` - Settings helper
  - `class.view.php` - View rendering system
  - `class.cscf_filters.php` - Spam filtering integration
- **AJAX Handler**: `ajax.php` - Handles asynchronous form submissions
- **Views**: `views/` directory contains PHP templates for form and settings
- **Shortcodes**: `shortcodes/` directory for `[cscf-contact-form]` implementation
- **Assets**: Separate directories for `css/`, `js/`, `fonts/`, and `images/`

### Key Integration Points
- **Google reCAPTCHA v2**: Optional spam prevention
- **Fullworks Anti Spam Pro**: Optional advanced spam filtering
- **Bootstrap**: CSS framework for styling
- **AJAX**: Optional client-side validation and submission
- **WordPress Mail**: Uses `wp_mail()` for sending emails

### Development Notes
- All user inputs are sanitized and escaped following WordPress best practices
- Supports 30+ languages with full internationalization
- Settings stored in WordPress options table
- Follows WordPress coding standards (WPCS)
- Uses `console.debug` instead of `console.log` for debug messages

## REST API Support

The plugin now includes REST API support for headless WordPress implementations:

### Endpoint
- **URL**: `POST /wp-json/cscf/v1/submit`
- **Authentication**: Requires WordPress user authentication (default capability: `edit_posts`)
- **Settings**: Enable in plugin settings under "REST API Settings"

### Request Format
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "confirm_email": "john@example.com",  // Required if confirm email is enabled
  "message": "Your message here",
  "phone_number": "+1234567890",        // Optional/required based on settings
  "contact_consent": true,              // Required if consent is enabled
  "email_sender": false,                // Optional: send copy to sender
  "post_id": 123                        // Optional: ID of page form was submitted from
}
```

### Response Format
Success:
```json
{
  "success": true,
  "message": "Message Sent"
}
```

Error:
```json
{
  "code": "validation_failed",
  "message": "Validation failed.",
  "data": {
    "status": 400,
    "errors": {
      "email": "Please enter a valid email address.",
      "message": "Please enter a message."
    }
  }
}
```

### Authentication
The REST API uses WordPress's built-in authentication. Users must be logged in and have the required capability (configurable in settings, default: `edit_posts`).

### Notes
- reCAPTCHA is bypassed for REST API submissions (authentication is used instead)
- All other validations and spam filtering still apply
- Form data is sanitized the same way as regular submissions

## Important Considerations
- Always check PHP compatibility before committing (minimum PHP 7.4)
- Run `composer phpcs` to ensure code meets security standards
- The plugin supports both AJAX and non-AJAX form submission modes
- Email recipient is set in WordPress Settings > General
- Text domain: `clean-and-simple-contact-form-by-meg-nicholas`
- REST API is disabled by default and must be enabled in settings