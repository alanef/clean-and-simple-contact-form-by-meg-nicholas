<?php
/**
 * Test edge cases for phone validation
 */

use PHPUnit\Framework\TestCase;

class PhoneValidationEdgeCasesTest extends TestCase {
    
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
     * Test that short valid phone numbers with special chars work when mandatory
     */
    public function testShortPhoneWithSpecialCharsWhenMandatory() {
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
        
        // Create contact with exactly 8 chars including special chars
        $contact = new cscf_Contact();
        $contact->Email = 'test@example.com';
        $contact->ConfirmEmail = 'test@example.com';
        $contact->Name = 'Test User';
        $contact->Message = 'Test message';
        $contact->PhoneNumber = '(123)456'; // 8 chars
        $contact->IsRestApi = true;
        
        // Should pass - has valid format and minimum length
        $this->assertTrue( $contact->IsValid() );
    }
    
    /**
     * Test mixed valid characters
     */
    public function testMixedValidCharacters() {
        // Mock settings
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // Test various valid formats
        $validNumbers = [
            '+1 (555) 123-4567',
            '+44 20 7946 0958',
            '(02) 9876 5432',
            '555-1234',
            '+33-1-42-86-82-00',
            '1 800 555 1234'
        ];
        
        foreach ($validNumbers as $number) {
            $contact = new cscf_Contact();
            $contact->Email = 'test@example.com';
            $contact->ConfirmEmail = 'test@example.com';
            $contact->Name = 'Test User';
            $contact->Message = 'Test message';
            $contact->PhoneNumber = $number;
            $contact->IsRestApi = true;
            
            $this->assertTrue( $contact->IsValid(), "Phone number '$number' should be valid" );
        }
    }
    
    /**
     * Test that error message is specific for format vs length
     */
    public function testErrorMessageSpecificity() {
        // Mock settings - phone mandatory
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
        
        // Test invalid format
        $contact1 = new cscf_Contact();
        $contact1->Email = 'test@example.com';
        $contact1->ConfirmEmail = 'test@example.com';
        $contact1->Name = 'Test User';
        $contact1->Message = 'Test message';
        $contact1->PhoneNumber = '123@456#890';
        $contact1->IsRestApi = true;
        
        $this->assertFalse( $contact1->IsValid() );
        $this->assertStringContainsString( 'can only contain numbers', $contact1->Errors['phone-number'] );
        
        // Test too short
        $contact2 = new cscf_Contact();
        $contact2->Email = 'test@example.com';
        $contact2->ConfirmEmail = 'test@example.com';
        $contact2->Name = 'Test User';
        $contact2->Message = 'Test message';
        $contact2->PhoneNumber = '1234567';
        $contact2->IsRestApi = true;
        
        $this->assertFalse( $contact2->IsValid() );
        $this->assertStringContainsString( 'minimum 8 characters', $contact2->Errors['phone-number'] );
    }
    
    /**
     * Test spaces and formatting variations
     */
    public function testSpacesAndFormatting() {
        // Mock settings
        WP_Mock::userFunction( 'get_option', [
            'times' => '1+',
            'return' => function($option_name, $default = []) {
                if ($option_name === 'cscf_options') {
                    return [
                        'phone-number' => true,
                        'confirm-email' => true
                    ];
                }
                return $default;
            }
        ] );
        
        // These should all be valid
        $validFormats = [
            '  555-1234  ',  // Leading/trailing spaces (will be sanitized)
            '555  -  1234',  // Multiple spaces
            '(555)    1234', // Multiple spaces after parenthesis
        ];
        
        foreach ($validFormats as $number) {
            $contact = new cscf_Contact();
            $contact->Email = 'test@example.com';
            $contact->ConfirmEmail = 'test@example.com';
            $contact->Name = 'Test User';
            $contact->Message = 'Test message';
            $contact->PhoneNumber = $number;
            $contact->IsRestApi = true;
            
            $result = $contact->IsValid();
            $this->assertTrue( $result, "Phone number '$number' should be valid" );
        }
    }
}