# Clean and Simple Contact Form - CSS Styling Guide

This guide covers all the CSS styling options available in the Clean and Simple Contact Form plugin, including the new CSS Framework selection and how to customize the Modern style using CSS variables.

## Table of Contents

- [CSS Framework Options](#css-framework-options)
- [Modern Style - CSS Variables](#modern-style---css-variables)
- [Color Customization](#color-customization)
- [Spacing and Layout](#spacing-and-layout)
- [Typography](#typography)
- [Dark Mode](#dark-mode)
- [Developer Filters](#developer-filters)
- [Complete Examples](#complete-examples)

---

## CSS Framework Options

The plugin offers four CSS framework options, selectable from **Settings > Contact Form > Styling and Validation**:

### Bootstrap (Default)

The classic option for backward compatibility. Uses Bootstrap CSS classes:

- Best for themes already using Bootstrap
- Uses classes like `form-control`, `btn`, `form-group`
- Enable/disable the bundled stylesheet with "Use the plugin default stylesheet"

### Modern (Card style)

A beautiful, opinionated design with CSS variables for easy customization:

- Card-style container with subtle shadow
- Large, comfortable input fields
- Automatic dark mode support
- Fully customizable via CSS variables
- No external dependencies

### Theme Native

Minimal classes that let your theme's styles take over:

- Uses WordPress's `wp-element-button` for the submit button
- Form inherits your theme's native form styles
- Best for themes with strong form styling

### Minimal (Semantic only)

Semantic CSS classes for complete custom styling:

- Uses classes like `cscf-field`, `cscf-input`, `cscf-button`
- No styling applied - you provide all CSS
- Maximum flexibility for custom designs

---

## Modern Style - CSS Variables

The Modern style uses CSS custom properties (variables) that you can override in your theme. This allows complete customization without editing plugin files.

### Basic Customization

Add this to your theme's `style.css` or a custom CSS plugin:

```css
:root {
    /* Primary color - buttons and focus rings */
    --cscf-primary: #0066cc;

    /* Border radius - roundness of corners */
    --cscf-radius: 0.75rem;

    /* Border color */
    --cscf-border: #d1d5db;

    /* Error color */
    --cscf-error: #dc2626;
}
```

### All Available Variables

Here's the complete list of CSS variables you can customize:

#### Colors

```css
:root {
    /* Primary button and focus colors */
    --cscf-primary: #18181b;
    --cscf-primary-hover: #27272a;

    /* Background colors */
    --cscf-background: #ffffff;
    --cscf-foreground: #09090b;
    --cscf-muted: #f4f4f5;
    --cscf-muted-foreground: #71717a;

    /* Form elements */
    --cscf-border: #e4e4e7;
    --cscf-input-bg: #ffffff;
    --cscf-ring: #18181b;

    /* States */
    --cscf-error: #ef4444;
    --cscf-error-bg: #fef2f2;
    --cscf-success: #22c55e;
}
```

#### Spacing

```css
:root {
    --cscf-spacing-xs: 0.25rem;   /* 4px */
    --cscf-spacing-sm: 0.5rem;    /* 8px */
    --cscf-spacing-md: 1rem;      /* 16px */
    --cscf-spacing-lg: 1.5rem;    /* 24px */
    --cscf-spacing-xl: 2rem;      /* 32px */
}
```

#### Border Radius

```css
:root {
    --cscf-radius-sm: 0.375rem;   /* 6px - small elements */
    --cscf-radius: 0.5rem;        /* 8px - inputs, buttons */
    --cscf-radius-lg: 0.75rem;    /* 12px - card container */
}
```

#### Typography

```css
:root {
    --cscf-font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    --cscf-font-size-sm: 0.875rem;     /* 14px */
    --cscf-font-size-base: 1rem;       /* 16px */
    --cscf-font-size-lg: 1.125rem;     /* 18px */
    --cscf-line-height: 1.5;
    --cscf-font-weight-medium: 500;
    --cscf-font-weight-semibold: 600;
}
```

#### Shadows

```css
:root {
    --cscf-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --cscf-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
    --cscf-shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}
```

#### Transitions

```css
:root {
    --cscf-transition: 150ms cubic-bezier(0.4, 0, 0.2, 1);
}
```

---

## Color Customization

### Brand Color Matching

Match the form to your brand colors:

```css
:root {
    /* Blue brand */
    --cscf-primary: #2563eb;
    --cscf-primary-hover: #1d4ed8;
    --cscf-ring: #3b82f6;
}
```

```css
:root {
    /* Green brand */
    --cscf-primary: #059669;
    --cscf-primary-hover: #047857;
    --cscf-ring: #10b981;
}
```

```css
:root {
    /* Purple brand */
    --cscf-primary: #7c3aed;
    --cscf-primary-hover: #6d28d9;
    --cscf-ring: #8b5cf6;
}
```

### Soft/Muted Style

For a softer, less contrasting look:

```css
:root {
    --cscf-primary: #6b7280;
    --cscf-primary-hover: #4b5563;
    --cscf-border: #e5e7eb;
    --cscf-shadow: none;
}
```

### High Contrast

For better accessibility:

```css
:root {
    --cscf-primary: #000000;
    --cscf-primary-hover: #1a1a1a;
    --cscf-border: #000000;
    --cscf-foreground: #000000;
}
```

---

## Spacing and Layout

### Compact Form

Reduce spacing for a tighter layout:

```css
:root {
    --cscf-spacing-lg: 1rem;
    --cscf-spacing-xl: 1.5rem;
    --cscf-spacing-md: 0.75rem;
}

#cscf .cscf-input,
#cscf .cscf-textarea {
    padding: var(--cscf-spacing-sm) var(--cscf-spacing-md);
}
```

### Larger Form

Increase spacing for a more spacious feel:

```css
:root {
    --cscf-spacing-lg: 2rem;
    --cscf-spacing-xl: 3rem;
}

#cscf .cscf-input,
#cscf .cscf-textarea {
    padding: 1.25rem 1rem;
    font-size: 1.125rem;
}
```

### Full Width (Remove Card)

Make the form span full width without the card container:

```css
#cscf.cscfBlock {
    max-width: none;
    border: none;
    box-shadow: none;
    padding: 0;
    background: transparent;
}
```

### Custom Width

Set a specific form width:

```css
#cscf.cscfBlock {
    max-width: 40rem;  /* 640px */
}
```

---

## Typography

### Custom Font

Use a different font family:

```css
:root {
    --cscf-font-family: "Inter", system-ui, sans-serif;
}
```

### Larger Text

Increase font sizes:

```css
:root {
    --cscf-font-size-sm: 1rem;
    --cscf-font-size-base: 1.125rem;
    --cscf-font-size-lg: 1.25rem;
}
```

---

## Dark Mode

The Modern style automatically supports dark mode in two ways:

### 1. Automatic (prefers-color-scheme)

The form automatically adapts to the user's system preference:

```css
/* This is built-in - no action needed */
@media (prefers-color-scheme: dark) {
    :root:not(.light) {
        --cscf-primary: #fafafa;
        --cscf-background: #09090b;
        /* ... other dark mode colors */
    }
}
```

### 2. Manual (.dark class)

Add the `.dark` class to your `<html>` or `<body>` element:

```html
<html class="dark">
```

### Customizing Dark Mode Colors

Override the dark mode colors:

```css
.dark,
@media (prefers-color-scheme: dark) {
    :root:not(.light) {
        --cscf-primary: #60a5fa;
        --cscf-primary-hover: #93c5fd;
        --cscf-background: #111827;
        --cscf-border: #374151;
    }
}
```

### Disable Dark Mode

Force light mode regardless of system preference:

```css
:root {
    --cscf-primary: #18181b;
    --cscf-background: #ffffff;
    /* Set all other variables to light mode values */
}

@media (prefers-color-scheme: dark) {
    :root {
        /* Override with same light mode values */
        --cscf-primary: #18181b;
        --cscf-background: #ffffff;
    }
}
```

---

## Developer Filters

For advanced customization, use PHP filters to modify CSS classes:

### cscf_css_classes

Override all CSS class mappings:

```php
add_filter('cscf_css_classes', function($classes, $framework) {
    // Completely replace classes for any framework
    if ($framework === 'modern') {
        $classes['button'] = 'my-custom-button-class';
        $classes['input'] = 'my-custom-input-class';
    }
    return $classes;
}, 10, 2);
```

### cscf_css_class

Override individual element classes:

```php
add_filter('cscf_css_class', function($class, $element, $framework) {
    // Add custom class to all buttons
    if ($element === 'button') {
        return $class . ' my-additional-class';
    }
    return $class;
}, 10, 3);
```

### Available Class Keys

- `form_group` - Field wrapper
- `form_group_error` - Error state for field wrapper
- `input` - Text inputs
- `textarea` - Textarea element
- `checkbox` - Checkbox input
- `button` - Submit button
- `help_text` - Error message text
- `input_group` - Input group wrapper (Bootstrap)
- `input_addon` - Input addon (Bootstrap icons)
- `text_error` - General error text

---

## Complete Examples

### Example 1: Minimal Black & White

```css
:root {
    --cscf-primary: #000000;
    --cscf-primary-hover: #333333;
    --cscf-background: #ffffff;
    --cscf-border: #000000;
    --cscf-radius: 0;
    --cscf-radius-lg: 0;
    --cscf-shadow: none;
}
```

### Example 2: Rounded & Colorful

```css
:root {
    --cscf-primary: #ec4899;
    --cscf-primary-hover: #db2777;
    --cscf-ring: #f472b6;
    --cscf-radius: 1rem;
    --cscf-radius-lg: 1.5rem;
    --cscf-border: #fce7f3;
    --cscf-shadow-lg: 0 20px 25px -5px rgb(236 72 153 / 0.1);
}

#cscf.cscfBlock {
    border: 2px solid var(--cscf-primary);
}
```

### Example 3: Corporate Professional

```css
:root {
    --cscf-font-family: "IBM Plex Sans", system-ui, sans-serif;
    --cscf-primary: #0f62fe;
    --cscf-primary-hover: #0353e9;
    --cscf-background: #f4f4f4;
    --cscf-border: #8d8d8d;
    --cscf-radius: 0;
    --cscf-radius-lg: 0;
}

#cscf.cscfBlock {
    border-top: 3px solid var(--cscf-primary);
}
```

### Example 4: Glass Effect (Glassmorphism)

```css
:root {
    --cscf-background: rgba(255, 255, 255, 0.8);
    --cscf-border: rgba(255, 255, 255, 0.3);
    --cscf-input-bg: rgba(255, 255, 255, 0.5);
}

#cscf.cscfBlock {
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
```

### Example 5: Using with Tailwind

If your theme uses Tailwind, you can use Tailwind's CSS variables:

```css
:root {
    --cscf-primary: rgb(var(--color-primary));
    --cscf-background: rgb(var(--color-background));
    --cscf-border: rgb(var(--color-border));
}
```

---

## Troubleshooting

### Styles Not Applying

1. Make sure "Modern (Card style)" is selected in the CSS Framework setting
2. Check that your custom CSS is loaded after the plugin CSS
3. Use browser DevTools to inspect which styles are being applied

### Dark Mode Not Working

1. Check if your theme adds a `.light` class that prevents dark mode
2. Verify the dark mode media query is not being overridden
3. Try adding the `.dark` class manually to test

### CSS Variables Not Working

1. Ensure you're defining variables in `:root` (not a nested selector)
2. Check for typos in variable names
3. Verify your browser supports CSS custom properties (all modern browsers do)

---

## CSS Class Reference

For the Minimal and Theme Native frameworks, these semantic classes are available:

| Class | Element |
|-------|---------|
| `.cscf-field` | Field wrapper |
| `.cscf-field--error` | Field with error |
| `.cscf-input` | Text input |
| `.cscf-textarea` | Textarea |
| `.cscf-checkbox` | Checkbox |
| `.cscf-button` | Submit button |
| `.cscf-error-message` | Error text |
| `.cscf-input-group` | Input group wrapper |
| `.cscf-input-addon` | Input addon |