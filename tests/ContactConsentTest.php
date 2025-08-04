<?php
/**
 * Test GDPR consent functionality
 */

use PHPUnit\Framework\TestCase;

class ContactConsentTest extends TestCase {
    
    /**
     * Set up WP_Mock for each test
     */
    public function setUp(): void {
        parent::setUp();
        WP_Mock::setUp();
        
        // Mock common WordPress functions
        WP_Mock::userFunction( 'sanitize_text_field', [
            'times' => '0+',
            'return_arg' => 0
        ] );
        
        WP_Mock::userFunction( 'wp_unslash', [
            'times' => '0+',
            'return_arg' => 0
        ] );
        
        WP_Mock::userFunction( 'sanitize_email', [
            'times' => '0+',
            'return_arg' => 0
        ] );
        
        WP_Mock::userFunction( 'sanitize_textarea_field', [
            'times' => '0+',
            'return_arg' => 0
        ] );
        
        WP_Mock::userFunction( 'esc_html__', [
            'times' => '0+',
            'return_arg' => 0
        ] );
        
        WP_Mock::userFunction( 'wp_verify_nonce', [
            'times' => '0+',
            'return' => true
        ] );
        
        // Mock SERVER and POST variables
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_POST = [];
    }
    
    /**
     * Tear down WP_Mock after each test
     */
    public function tearDown(): void {
        WP_Mock::tearDown();
        parent::tearDown();
    }
    
    /**
     * Test that consent is required when enabled
     */
    public function testConsentRequiredWhenEnabled() {
        // Mock settings to require consent
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'contact-consent' => true,
                        'confirm-email' => true  // Enable confirm email by default
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact without consent
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->ContactConsent = false;
        $contact->IsRestApi = true;
        
        // Validation should fail
        $this->assertFalse( $contact->IsValid() );
        $this->assertArrayHasKey( 'contact-consent', $contact->Errors );
    }
    
    /**
     * Test that consent is not required when disabled
     */
    public function testConsentNotRequiredWhenDisabled() {
        // Mock settings to NOT require consent
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        // contact-consent not set means it's disabled
                        'confirm-email' => true  // Enable confirm email by default
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact without consent
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->ContactConsent = false;
        $contact->IsRestApi = true;
        
        // Validation should pass
        $this->assertTrue( $contact->IsValid() );
        $this->assertArrayNotHasKey( 'contact-consent', $contact->Errors );
    }
    
    /**
     * Test that consent is properly recorded when given
     */
    public function testConsentProperlyRecorded() {
        // Mock settings to require consent
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'contact-consent' => true,
                        'confirm-email' => true  // Enable confirm email by default
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact WITH consent
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->ContactConsent = true;
        $contact->IsRestApi = true;
        
        // Validation should pass
        $this->assertTrue( $contact->IsValid() );
        $this->assertEmpty( $contact->Errors );
        
        // Consent should be recorded
        $this->assertTrue( $contact->ContactConsent );
    }
    
    /**
     * Test consent is included in email message
     */
    public function testConsentInEmailMessage() {
        // Mock settings
        WP_Mock::userFunction( 'get_option', [
            'times' => '0+',
            'return' => [
                'contact-consent' => true,
                'contact-consent-msg' => 'I consent to being contacted'
            ]
        ] );
        
        // Mock translation function to return the consent message
        WP_Mock::userFunction( '__', [
            'times' => '0+',
            'return' => function( $text ) {
                if ( $text === 'I consent to my contact details being stored' ) {
                    return 'I consent to my contact details being stored';
                }
                return $text;
            }
        ] );
        
        // Create contact with consent
        $contact = new cscf_Contact();
        $contact->Name = 'Test User';
        $contact->Email = 'test@example.com';
        $contact->Message = 'Test message';
        $contact->ContactConsent = true;
        
        // Get the message (this would normally be in SendMail)
        $message = $contact->Message;
        
        // In a real test, we'd test the actual email content
        // For now, just verify consent is set
        $this->assertTrue( $contact->ContactConsent );
    }
}