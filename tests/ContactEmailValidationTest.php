<?php
/**
 * Test email validation functionality
 */

use PHPUnit\Framework\TestCase;

class ContactEmailValidationTest extends TestCase {
    
    /**
     * Set up WP_Mock for each test
     */
    public function setUp(): void {
        parent::setUp();
        WP_Mock::setUp();
        
        // Mock get_option which is used by cscf_PluginSettings
        WP_Mock::userFunction( 'get_option', [
            'times' => '0+',
            'return' => []
        ] );
        
        // Mock sanitization functions used in constructor
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
     * Test that valid email passes validation
     */
    public function testValidEmailPassesValidation() {
        // Create contact and set properties directly
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // Skip nonce check
        
        // Test that validation passes
        $this->assertTrue( $contact->IsValid() );
        $this->assertEmpty( $contact->Errors );
    }
    
    /**
     * Test that invalid email fails validation
     */
    public function testInvalidEmailFailsValidation() {
        // Create contact with invalid email
        $contact = new cscf_Contact();
        $contact->Email = 'invalid-email';
        $contact->ConfirmEmail = 'invalid-email';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // Skip nonce check
        
        // Test that validation fails
        $this->assertFalse( $contact->IsValid() );
        
        // Test that error is set for email field
        $this->assertArrayHasKey( 'email', $contact->Errors );
    }
    
    /**
     * Test that empty email fails validation  
     */
    public function testEmptyEmailFailsValidation() {
        // Create contact with empty email
        $contact = new cscf_Contact();
        $contact->Email = '';
        $contact->ConfirmEmail = '';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // Skip nonce check
        
        // Test that validation fails
        $this->assertFalse( $contact->IsValid() );
        
        // Test that error is set for email field
        $this->assertArrayHasKey( 'email', $contact->Errors );
    }
    
    /**
     * Test various invalid email formats
     * 
     * @dataProvider invalidEmailProvider
     */
    public function testVariousInvalidEmailFormats( $email ) {
        $contact = new cscf_Contact();
        $contact->Email = $email;
        $contact->ConfirmEmail = $email;
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->IsRestApi = true; // Skip nonce check
        
        // If email is not empty, it should fail with invalid format
        if ( ! empty( $email ) ) {
            $this->assertFalse( $contact->IsValid() );
            $this->assertArrayHasKey( 'email', $contact->Errors );
        }
    }
    
    /**
     * Provide invalid email addresses for testing
     */
    public function invalidEmailProvider() {
        return [
            ['@example.com'],
            ['test@'],
            ['test..double@example.com'],
            ['test@example'],
            ['test @example.com'],
            ['test@.com'],
        ];
    }
}