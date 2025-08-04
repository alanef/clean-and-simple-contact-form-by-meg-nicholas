<?php
/**
 * Test phone number validation functionality
 */

use PHPUnit\Framework\TestCase;

class PhoneNumberValidationTest extends TestCase {
    
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
     * Test phone number is optional when not required
     */
    public function testPhoneNumberOptionalWhenNotRequired() {
        // Mock settings - phone enabled but not mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        // phone-number-mandatory not set means it's not required
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact without phone number
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = '';
        $contact->IsRestApi = true;
        
        // Validation should pass
        $this->assertTrue( $contact->IsValid() );
        $this->assertArrayNotHasKey( 'phone-number', $contact->Errors );
    }
    
    /**
     * Test invalid phone format is rejected even when optional
     */
    public function testInvalidPhoneFormatRejectedWhenOptional() {
        // Mock settings - phone enabled but not mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        // phone-number-mandatory not set means it's not required
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact with invalid phone format
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = '12www2';
        $contact->IsRestApi = true;
        
        // Validation should fail
        $this->assertFalse( $contact->IsValid() );
        $this->assertArrayHasKey( 'phone-number', $contact->Errors );
        $this->assertStringContainsString( 'can only contain numbers', $contact->Errors['phone-number'] );
    }
    
    /**
     * Test phone number is required when mandatory
     */
    public function testPhoneNumberRequiredWhenMandatory() {
        // Mock settings - phone enabled AND mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'phone-number-mandatory' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact without phone number
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = '';
        $contact->IsRestApi = true;
        
        // Validation should fail
        $this->assertFalse( $contact->IsValid() );
        $this->assertArrayHasKey( 'phone-number', $contact->Errors );
        $this->assertStringContainsString( 'enter your phone number', $contact->Errors['phone-number'] );
    }
    
    /**
     * Test phone length validation when mandatory
     */
    public function testPhoneLengthValidationWhenMandatory() {
        // Mock settings - phone enabled AND mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'phone-number-mandatory' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact with too short phone number
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = '1234567'; // 7 chars, too short
        $contact->IsRestApi = true;
        
        // Validation should fail
        $this->assertFalse( $contact->IsValid() );
        $this->assertArrayHasKey( 'phone-number', $contact->Errors );
        $this->assertStringContainsString( 'minimum 8 characters', $contact->Errors['phone-number'] );
    }
    
    /**
     * Test valid phone numbers pass validation
     * 
     * @dataProvider validPhoneProvider
     */
    public function testValidPhoneNumbers( $phoneNumber ) {
        // Mock settings - phone enabled AND mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'phone-number-mandatory' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact with valid phone
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = $phoneNumber;
        $contact->IsRestApi = true;
        
        // Validation should pass (phone must be at least 8 characters)
        $this->assertTrue( $contact->IsValid() );
    }
    
    /**
     * Test invalid phone numbers fail validation
     * 
     * @dataProvider invalidPhoneProvider
     */
    public function testInvalidPhoneNumbers( $phoneNumber ) {
        // Mock settings - phone enabled AND mandatory
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'phone-number-mandatory' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Create contact with invalid phone
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = $phoneNumber;
        $contact->IsRestApi = true;
        
        // Validation should fail (phone must be at least 8 characters)
        $this->assertFalse( $contact->IsValid() );
    }
    
    /**
     * Provide valid phone numbers
     */
    public function validPhoneProvider() {
        return [
            ['12345678'],              // Minimum 8 chars
            ['123-456-7890'],          // US format with dashes
            ['+1 (555) 123-4567'],     // International with formatting
            ['0044 20 7123 4567'],     // UK international
            ['+33 1 42 86 82 00'],     // French format
            ['(555) 555-5555'],        // US with parentheses
        ];
    }
    
    /**
     * Provide invalid phone numbers
     */
    public function invalidPhoneProvider() {
        return [
            ['12www2'],     // Contains invalid characters
            ['phone'],      // Not a number
            ['123@456'],    // Contains @ symbol
            ['test-phone'], // Contains letters
            ['555.1234'],   // Contains period (not allowed)
        ];
    }
}