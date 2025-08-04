<?php
/**
 * Test REST API functionality
 */

use PHPUnit\Framework\TestCase;

class RestApiTest extends TestCase {
    
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
        
        WP_Mock::userFunction( 'absint', [
            'times' => '0+',
            'return' => function($value) {
                return intval($value);
            }
        ] );
        
        WP_Mock::userFunction( 'apply_filters', [
            'times' => '0+',
            'return_arg' => 1  // Return the second argument (the value)
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
     * Test set_from_array method for REST API
     */
    public function testSetFromArray() {
        // Mock settings
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => []
        ] );
        
        $contact = new cscf_Contact();
        
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'confirm-email' => 'john@example.com',
            'message' => 'Test message',
            'phone-number' => '555-1234',
            'contact-consent' => true,
            'email-sender' => true
        ];
        
        $contact->set_from_array($data, 123, true);
        
        $this->assertEquals('John Doe', $contact->Name);
        $this->assertEquals('john@example.com', $contact->Email);
        $this->assertEquals('john@example.com', $contact->ConfirmEmail);
        $this->assertEquals('Test message', $contact->Message);
        $this->assertEquals('555-1234', $contact->PhoneNumber);
        $this->assertTrue($contact->ContactConsent);
        $this->assertTrue($contact->EmailToSender);
        $this->assertTrue($contact->IsRestApi);
        $this->assertEquals(123, $contact->PostID);
    }
    
    /**
     * Test REST API skips nonce validation
     */
    public function testRestApiSkipsNonceValidation() {
        // Mock settings
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => [
                'confirm-email' => true
            ]
        ] );
        
        // Set REQUEST_METHOD to POST but don't provide nonce
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [];
        
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // This should skip nonce check
        
        // Should pass validation even without nonce
        $this->assertTrue( $contact->IsValid() );
    }
    
    /**
     * Test REST API skips reCAPTCHA validation
     */
    public function testRestApiSkipsRecaptcha() {
        // Mock settings with reCAPTCHA enabled
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => [
                'recaptcha_public_key' => 'test_public',
                'recaptcha_private_key' => 'test_private',
                'confirm-email' => true
            ]
        ] );
        
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // This should skip reCAPTCHA
        $contact->RecaptchaPublicKey = 'test_public';
        $contact->RecaptchaPrivateKey = 'test_private';
        
        // Should pass validation even with reCAPTCHA configured
        $this->assertTrue( $contact->IsValid() );
        $this->assertArrayNotHasKey( 'recaptcha', $contact->Errors );
    }
    
}