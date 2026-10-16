<!-- tooling:start (managed by wordpress-plugin-boilerplate/tooling - do not edit by hand) -->
# Contact Form Clean and Simple - Development Guide

Tooling in this repository is standardised across the Fullworks free plugins. The master
description lives in
[wordpress-plugin-boilerplate/CLAUDE.md](https://github.com/alanef/wordpress-plugin-boilerplate/blob/main/CLAUDE.md).
**Fix tooling problems there first, then roll out** with its `bin/sync-tooling.sh`; never
hand-edit the managed files listed there.

## This repository

| | |
|---|---|
| Plugin directory | `clean-and-simple-contact-form-by-meg-nicholas/` |
| Main file | `clean-and-simple-contact-form-by-meg-nicholas/clean-and-simple-contact-form-by-meg-nicholas.php` |
| Default branch | `main` |
| WordPress.org slug | `clean-and-simple-contact-form-by-meg-nicholas` |
| wp-env ports | dev `8500`, tests `8501` |
| Version locations | plugin header `Version:`, `readme.txt` `Stable tag:` and `CSCF_VERSION_NUM` in the main file |

CI fails when the version locations disagree.

## Commands

```bash
composer install && npm install   # first time
composer run check                # PHPCompatibility + WordPress security sniffs
npm run start                     # wp-env (dev :8500, tests :8501, admin/password)
npm test                          # PHPUnit inside the wp-env tests container
npm test -- --filter Foo          # pass PHPUnit args through
composer run build                # zipped/clean-and-simple-contact-form-by-meg-nicholas-free.zip via wp dist-archive
```

## Changelog and versions

One changelog: `clean-and-simple-contact-form-by-meg-nicholas/changelog.txt`, wp.org style, shipped in the zip and published
to the KB by `update-changelog.sh` where `.env.wordpress` exists. `readme.txt` may link to it
or repeat recent entries, but is not the source. Every user-visible change adds a typed bullet
under `= Unreleased =` at the top, in the same commit:

```
= Unreleased =
* Fixed: what was wrong, and for whom
* Added: / Changed: / Deprecated: / Removed: / Security:
```

While developing, carry a prerelease suffix (`1.4.0-alpha.1`, bump only the `N`); release.yml
refuses to publish one. Releasing drops it.

## Release

Always through the commands, never by hand:

```
/tk-release X.Y.Z    # scripts/release/bump-version.sh: every version location + changelog, read back
                     # scripts/release/check-dependencies.sh: shipped composer deps block, tooling warns
                     # then commit the release ("Release X.Y.Z") and push
                     # scripts/release/run-tests-and-mark.sh X.Y.Z: CI's gates locally (wp-env running)
                     # commit .sdlc/releases/X.Y.Z/ evidence and push
/tk-deploy live      # .claude/commands/deploy-live.md: preconditions, confirm, tag X.Y.Z, watch CI
```

`/tk-release` runs the test hook before you commit, so run it **again after** the release
commit: its marker names the commit, and `/tk-deploy live` refuses it if plugin code changed
since. The tag runs `Build Release`,
which re-runs the checks, creates the GitHub release with the zip attached, then deploys to
whatever the plugin's `Type:` calls for.

## Where a release goes

`Build Release` reads a `Type:` header from the plugin's `readme.txt` and deploys
accordingly. Every plugin gets a GitHub release; `Type:` only decides what else:

| `readme.txt` | GitHub | WordPress.org | Freemius |
|---|---|---|---|
| *(no `Type:` header)* | yes | | |
| `Type: free` | yes | yes | |
| `Type: freemium` | yes | yes | yes |
| `Type: premium` | yes | | yes |

Omitting the header is the safe default -- a plugin is never published anywhere
public by accident. A header that is present but unrecognised **fails the
release**, because that is a typo rather than a choice, and silently falling back
to GitHub-only is how a plugin quietly stops reaching WordPress.org for months.
The resolved targets are echoed in the job log and the run summary.

With no `Type:` header the plugin also **updates itself from its GitHub releases** (managed
`includes/class-github-updater.php` and `Update URI:` header, both added by the sync). Adding a
`Type:` and re-syncing removes them, since WordPress.org forbids off-site updates.

WordPress.org deploys need the `SVN_USERNAME` and `SVN_PASSWORD` repository
secrets; the step fails loudly if they are missing or the SVN tag already exists.
<!-- tooling:end -->

# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is the **Clean and Simple Contact Form** WordPress plugin - a Bootstrap-styled AJAX contact form with reCAPTCHA support and spam filtering capabilities.

## Git Workflow

- **Always develop on the `develop` branch** - never commit directly to `main`
- When ready to release, merge `develop` into `main`
- Create version tags on `main` (e.g., `git tag 4.12.2 && git push origin 4.12.2`)
- After tagging, sync develop with main: `git push origin main:develop --force`

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

# Update the Changelog:

After updating changelog.txt, run:
update-changelog.sh
The script (update-changelog.sh) is available globally in PATH from the dev_scripts project.

The script will:

Load credentials from .env.wordpress
Convert changelog.txt to HTML (uses pandoc if available)
Update the BasePress article via custom API
Display the URL to view changes
Note: The script uses the BasePress custom API endpoint (/wp-json/basepress/v1/article/) which is configured in the BasePress API plugin on the site.

