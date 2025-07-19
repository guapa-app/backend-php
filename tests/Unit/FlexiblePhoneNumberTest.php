<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\FlexiblePhoneNumber;
use App\Helpers\Common;
use App\Models\Setting;

class FlexiblePhoneNumberTest extends TestCase
{
    public function test_phone_number_validation_accepts_valid_formats()
    {
        $rule = new FlexiblePhoneNumber();

        $validNumbers = [
            '966512345678',      // International format
            '+966512345678',     // With plus
            '0512345678',        // Local format with 0
            '512345678',         // Local format without 0
            '+1234567890',       // International number (non-KSA)
            '1234567890',        // Regular number
            '+44 20 7946 0958',  // UK number with spaces
        ];

        foreach ($validNumbers as $number) {
            $validator = $this->app['validator']->make(
                ['phone' => $number],
                ['phone' => $rule]
            );

            $this->assertTrue($validator->passes(), "Failed for: {$number}");
        }
    }

    public function test_phone_number_validation_rejects_invalid_formats()
    {
        $rule = new FlexiblePhoneNumber();

        $invalidNumbers = [
            'abc12345678',       // Contains letters
            '',                  // Empty
            '123',               // Too short
            '123456789012345678901234567890', // Too long
            'abc',               // Only letters
            '!@#$%^&*()',        // Special characters only
        ];

        foreach ($invalidNumbers as $number) {
            $validator = $this->app['validator']->make(
                ['phone' => $number],
                ['phone' => $rule]
            );

            $this->assertTrue($validator->fails(), "Should fail for: {$number}");
        }
    }

    public function test_ksa_number_detection()
    {
        $rule = new FlexiblePhoneNumber();

        $ksaNumbers = [
            '966512345678',
            '+966512345678',
            '0512345678',
            '512345678',
        ];

        foreach ($ksaNumbers as $number) {
            $validator = $this->app['validator']->make(
                ['phone' => $number],
                ['phone' => $rule]
            );

            $validator->passes(); // Run validation to set the flag
            $this->assertTrue($rule->isKsaNumber(), "Should detect KSA number: {$number}");
            $this->assertTrue($rule->isPreferred(), "Should be preferred: {$number}");
        }

        $nonKsaNumbers = [
            '+1234567890',
            '1234567890',
            '+44 20 7946 0958',
            '+971501234567', // UAE number
        ];

        foreach ($nonKsaNumbers as $number) {
            $validator = $this->app['validator']->make(
                ['phone' => $number],
                ['phone' => $rule]
            );

            $validator->passes(); // Run validation to set the flag
            $this->assertFalse($rule->isKsaNumber(), "Should not detect as KSA number: {$number}");
            $this->assertFalse($rule->isPreferred(), "Should not be preferred: {$number}");
        }
    }

    public function test_normalization_for_ksa_numbers()
    {
        $rule = new FlexiblePhoneNumber();

        $testCases = [
            '966512345678' => '966512345678',      // Already normalized
            '+966512345678' => '966512345678',     // Remove plus
            '0512345678' => '966512345678',        // Add country code, remove leading 0
            '512345678' => '966512345678',         // Add country code
        ];

        foreach ($testCases as $input => $expected) {
            $validator = $this->app['validator']->make(
                ['phone' => $input],
                ['phone' => $rule]
            );

            $validator->passes(); // Run validation to set the flag
            $this->assertEquals($expected, $rule->getNormalizedNumber($input), "Failed for: {$input}");
        }

        // Test non-KSA numbers return as-is
        $nonKsaNumbers = [
            '+1234567890',
            '1234567890',
            '+44 20 7946 0958',
        ];

        foreach ($nonKsaNumbers as $number) {
            $validator = $this->app['validator']->make(
                ['phone' => $number],
                ['phone' => $rule]
            );

            $validator->passes(); // Run validation to set the flag
            $this->assertEquals($number, $rule->getNormalizedNumber($number), "Should return as-is for: {$number}");
        }
    }
}
