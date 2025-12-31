=== Contact Form Clean and Simple ===
Contributors: alanfuller, fullworks
Donate Link: https://ko-fi.com/wpalan
Requires at least: 5.6
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl.html
Tags: contact, form, contact form, feedback form, bootstrap
Tested up to: 6.9
Stable tag: 4.12.1

A clean and simple contact form with flexible CSS framework support.


== Description ==
A clean and simple AJAX contact form with Google reCAPTCHA, flexible CSS framework support, spam filtering, and REST API support for headless WordPress implementations.

*   **Clean**: all user inputs are stripped in order to avoid cross-site scripting (XSS) vulnerabilities.

*   **Simple**: AJAX enabled validation and submission for immediate response and guidance for your users (can be switched off).

*   **Flexible Styling**: Choose your CSS framework - Bootstrap (default), Theme Native (inherits your theme's styles), or Minimal (semantic classes for complete custom styling).

*   **REST API Support**: Enable headless WordPress implementations to submit forms via authenticated REST API endpoints.


This is a straightforward contact form for your WordPress site. There is very minimal set-up
required. Simply install, activate, and then place the short code **[cscf-contact-form]** on your web page.

A standard set of input boxes are provided, these include Email Address, Name, Message and a nice big ‘Send Message’ button.

When your user has completed the form an email will be sent to you containing your user’s message.
To reply simply click the ‘reply’ button on your email client.
The email address used is the one you have set up in WordPress under ‘Settings’ -> ‘General’, so do check this is correct.

To help prevent spam all data is scanned can be scanned with Fullworks Anti Spam Pro.
For this to work you must have the [Fullworks Anti Spam Pro Plugin](https://fullworksplugins.com/products/anti-spam/ "Fullworks Anti Spam Pro") installed and activated.

Fullworks Anti Spam Pro will also log all your messages, categorized  as spam or not, automatically.

For added piece of mind this plugin also allows you to add a ‘**reCAPTCHA**’.
This adds a picture of a couple of words to the bottom of the contact form.
Your user must correctly type the words before the form can be submitted, and in so doing, prove that they are human.

= Why Choose This Plugin? =
Granted there are many plugins of this type in existence already. Why use this one in-particular?

Here’s why:

*   Minimal setup. Simply activate the plugin and place the shortcode [cscf-contact-form] on any post or page.

*   **Safe**. All input entered by your user  is stripped back to minimise as far as possible the likelihood of any
malicious user attempting to inject a script into your website.
If the Fullworks Anti Spam Pro plugin is activated all form data will be scanned for spam.
You can turn on reCAPTCHA to avoid your form being abused by bots, however Fullworks Anti Spam Pro will do this without reCAPTCHA.

*   **Ajax enabled**. You have the option to turn on AJAX (client-side) validation and submission which gives your users an immediate response when completing the form without having to wait for the page to refresh.

*   The form can **integrate seamlessly into your website**. Turn off the plugin’s default css style sheet so that your theme’s style sheet can be used instead.

*   **Flexible CSS styling**: Choose from Bootstrap, Modern (with dark mode), Theme Native, or Minimal styling modes to match your site's design.

*   This plugin will only link in its jQuery file where it’s needed, it **will not impose** itself on every page of your whole site!

*   Works with the **latest version of WordPress**.

*   Original plugin written by an **experienced PHP programmer**, Megan Nicholas, the code is rock solid, safe, and rigorously tested as standard practice.

*   **Headless WordPress ready**. REST API support allows you to submit forms from decoupled frontends, mobile apps, or any external application with proper authentication.

Hopefully this plugin will fulfil all your needs.

== PHP 8 Ready ==

Tested on PHP 8.3


== Installation ==
There are two ways to install:

1. Click the ‘Install Now’ link from the plugin library listing to automatically download and install.

2. Download the plugin as a zip file. To install the zip file simply double click to extract it and place the whole folder in your wordpress plugins folder, e.g. [wordpress]/wp-content/plugins where [wordpress] is the directory that you installed WordPress in.

Then visit the plugin page on your wordpress site and click ‘Activate’ against the ‘Clean and Simple Contact Form’ plugin listing.

To place the contact form on your page use the shortcode [cscf-contact-form]

== How to Use ==
Unless you want to change messages or add reCAPTCHA to your contact form then this plugin will work out of the box without any additional setup.

Important: Check that you have an email address set-up in your WordPress ‘Settings’->’General’ page. This is the address that the plugin will use to send the contents of the contact form.

To add the contact form to your WordPress website simply place the shortcode [cscf-contact-form] on the post or page that you wish the form to appear on.

**If you have Jetpack plugin installed disable the contact form otherwise the wrong form might display.**

== Additional Settings ==
This plugin will work out of the box without any additional setup. You have the option to change the default messages that are displayed to your user and to add reCAPTCHA capabilities.

Go to the settings screen for the contact form plugin.

You will find a link to the setting screen against the entry of this plugin on the ‘Installed Plugins’ page.

Here is a list of things that you can change

*   **Message**: The message displayed to the user at the top of the contact form.

*   **Message Sent Heading**: The message heading or title displayed to the user after the message has been sent.

*   **Message Sent Content**: The message content or body displayed to the user after the message has been sent.

*   **CSS Framework**: Choose how the form is styled:
    - **Bootstrap (Default)**: Uses Bootstrap CSS classes for full Bootstrap compatibility. Best for themes already using Bootstrap.
    - **Modern (Card style)**: A beautiful, opinionated modern design with card-style layout, large inputs, and CSS variables for easy customization. Includes automatic dark mode support.
    - **Theme Native**: Uses minimal classes with WordPress's wp-element-button for the submit button. The form inherits your theme's native form styles.
    - **Minimal**: Uses semantic CSS classes only (cscf-field, cscf-input, etc.) for complete custom styling control.

*   **Use this plugin's default stylesheet**: The plugin comes with a default style sheet to make the form look nice for your user. Untick this if you want to use your theme's stylesheet instead. The default stylesheet will simply not be linked in. This option is most relevant when using the Bootstrap CSS framework.

*   **Use client side validation (Ajax)**: When ticked the contact form will be validated and submitted on the client giving your user instant feedback if they have filled the form in incorrectly. If you wish the form to be validated and submitted only to the server then untick this option.

*   **Use reCAPTCHA**: Tick this option if you wish your form to have a reCAPTCHA box. ReCAPTCHA helps to avoid spam bots using your form by checking that the form filler is actually a real person. To use reCAPTCHA you will need to get a some special keys from google https://www.google.com/recaptcha/admin/create. Once you have your keys enter them into the Public key and Private key boxes

*   **reCAPTCHA Public Key**: Enter the public key that you obtained from here.

*   **reCAPTCHA Private Key**: Enter the private key that you obtained from here.

*   **reCAPTCHA Theme**: Here you can change the reCAPTCHA box theme so that it fits with the style of your website.

*   **Recipient Emails**: The email address where you would like all messages to be sent.
    This will default to the email address you have specified under 'E-Mail Address' in your WordPress General Settings.
    If you want your mail sent to a different address then enter it here.
    You may enter multiple email addresses by clicking the '+' button.

*   **Confirm Email Address**: Email confirmation is now optional. To force your user to re-type their email address tick 'Confirm Email Address'.
    It is recommended that you leave this option on. If you turn this option off your user will only have to enter their email address once,
    but if they enter it incorrectly you will have no way of getting back to them!

*   **Email Subject**: This is the email subject that will appear on all messages. If you would like to set it to something different then enter it here.

*   **Override 'From' Address**: If you tick this and then fill in the 'From Address:' box then all email will be sent from the given address NOT from the email address given by the form filler.

*   **Option to allow enquiry to email themselves a copy of the message.

*   **Contact consent**: This option allows you to be GDPR compliant by adding a 'Consent to contact' check box at the bottom of the form.

*   **Enable REST API**: Turn on REST API support to allow headless WordPress implementations to submit forms.

*   **Required User Capability**: Set the minimum WordPress user capability required to use the REST API (default: edit_posts).


== REST API for Headless WordPress ==

This plugin includes REST API support, making it perfect for headless WordPress implementations, mobile applications, and decoupled frontend frameworks like React, Vue.js, or Angular.

= Enabling REST API =

1. Go to the plugin settings page
2. Find the "REST API Settings" section
3. Check "Enable REST API"
4. Set the required user capability (default: edit_posts)
5. Save your settings

= API Endpoint =

**POST** `/wp-json/cscf/v1/submit`

= Authentication =

The REST API requires WordPress user authentication. Users must be logged in and have the capability specified in settings (default: edit_posts).

For headless implementations, you can use:
- Application Passwords (WordPress 5.6+)
- JWT Authentication plugins
- OAuth plugins
- Basic Authentication (development only)

= Request Format =

Send a POST request with JSON body:

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "confirm_email": "john@example.com",
  "message": "Your message here",
  "phone_number": "+1234567890",
  "contact_consent": true,
  "email_sender": false,
  "post_id": 123
}
```

**Required fields:**
- `name`: Sender's name
- `email`: Sender's email address
- `message`: The message content

**Optional fields:**
- `confirm_email`: Required if email confirmation is enabled in settings
- `phone_number`: Required if phone number is set as mandatory in settings
- `contact_consent`: Required if contact consent is enabled in settings
- `email_sender`: Set to true to send a copy to the sender
- `post_id`: The ID of the page/post where the form would normally be displayed

= Response Format =

**Success Response (200):**
```json
{
  "success": true,
  "message": "Message Sent"
}
```

**Validation Error Response (400):**
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

**Authentication Error Response (401):**
```json
{
  "code": "rest_forbidden",
  "message": "Authentication required.",
  "data": {
    "status": 401
  }
}
```

= Example Implementation =

**JavaScript (fetch API):**
```javascript
const formData = {
  name: "John Doe",
  email: "john@example.com",
  confirm_email: "john@example.com",
  message: "This is a test message from the REST API"
};

fetch('https://yoursite.com/wp-json/cscf/v1/submit', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer YOUR_AUTH_TOKEN'
  },
  body: JSON.stringify(formData)
})
.then(response => response.json())
.then(data => {
  if (data.success) {
    console.log('Message sent successfully!');
  } else {
    console.error('Validation errors:', data.data.errors);
  }
});
```

= Important Notes =

- REST API is disabled by default for security
- reCAPTCHA is bypassed for REST API submissions (authentication provides security)
- All other form validations and spam filtering still apply
- Form submissions via REST API are processed identically to regular submissions
- Email notifications work the same way as standard form submissions


== Screenshots ==
1. Contact Form With reCAPTCHA
2. Contact Form Without reCAPTCHA
3. Message Sent
4. Contact Form Options Screen
5. Place this shortcode on your post or page to deploy

== Demo ==
Demo site coming soon.

== Frequently Asked Questions ==
= I get a message to say that the message could not be sent =

If you get this message then you have a general problem with email on your server. This plugin uses Wordpress's send mail function.
So a problem sending mail from this plugin indicates that Wordpress as a whole cannot send email.
Contact your web host provider for help, or use an SMTP plugin to use a third party email service.

= I don't receive the email =

* Check the recipient email on your settings screen, is it correct?
* Check in your spam or junk mail folder
* For Gmail check in 'All Mail', the email might have gone straight to archive
* Try overriding the 'From' email address in the settings screen. Use an email address you own or is from your own domain

= Why is a different contact form displayed? =

You may have a conflict with another plugin. Either deactivate the other contact form plugin, if you don't need it, or use
this alternative short code on your webpage - `[cscf-contact-form]`.
This problem often occurs when Jetpack plugin is installed.

= How do I display the contact form on my page/post? =

To put the contact form on your page, add the text:
`[cscf-contact-form]`

The contact form will appear when you view the page.

= When I use the style sheet that comes with the plugin my theme is affected =

It is impossible to test this plugin with all themes. Styling incompatibilities can occur. In this case, switch off the default stylesheet on the settings
screen so you can add your own styles to your theme's stylesheet.

= Can I have this plugin in my own language? =

Yes, I am currently building up translation files for this plugin.
If your language is not yet available you are very welcome to translate it.

= How do I change the text box sizes? =

When using Bootstrap (the default), text box widths use up 100% of the available width.
This makes the form responsive to all types of media. If you want to have a fixed width for the form you can put some styling around the shortcode:
`<div style="width:600px;">[cscf-contact-form]</div>`

= How do I make the form match my theme's style? =

You have several options:

1. **Modern mode**: Select "Modern (Card style)" for a beautiful, ready-to-use design that can be customized with CSS variables.

2. **Theme Native mode**: Change the CSS Framework setting to "Theme Native". This uses minimal CSS classes and lets your theme's native form styles take over.

3. **Minimal mode**: Change the CSS Framework setting to "Minimal" for complete custom styling control using semantic classes like cscf-field, cscf-input, cscf-button.

4. **Custom CSS**: Keep Bootstrap mode but disable "Use the plugin default stylesheet" and add your own CSS rules.

5. **Developer filters**: Use the `cscf_css_classes` and `cscf_css_class` filters to customize the CSS classes used by the form.

= How do I customize the Modern style? =

The Modern style uses CSS variables that you can override in your theme. Add this to your theme's CSS:

`
:root {
  --cscf-primary: #0066cc;       /* Button and focus colors */
  --cscf-radius: 0.75rem;        /* Border radius */
  --cscf-border: #d1d5db;        /* Border color */
  --cscf-error: #dc2626;         /* Error color */
}
`

Full list of variables available in the cscf-modern.css file. Dark mode is automatically supported via `prefers-color-scheme` or by adding the `.dark` class to your HTML element.

= Can I have multiple forms? =

Currently you may only have one contact form per page. You CAN however put the contact form on more than one page using the same shortcode.
Note that making changes to the settings will affect all implementations of the plugin across your site.

= Will this work with other plugins that use Google reCAPTCHA? =
Yes it will. HOWEVER, you cannot have more than one reCAPTCHA on a page. This is a constraint created by Google.
So for example, if your 'Contact Me' page has comments below it,
the reCAPTCHA for the contact form will be displayed correctly but not in the comments form below.
The comments form will never validate due to no supplied reCAPTCHA code.

== Changelog ==

[Full Change History](https://fullworksplugins.com/docs/clean-and-simple-contact-form/usage-clean-and-simple-contact-form/change-log-3/)
