<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Validator;

class ValidatorTest extends TestCase
{
    public function test_it_can_validate_random_keys()
    {
        $this->assertTrue(Validator::validateRandom($this->randomKey));
        $this->assertFalse(Validator::validateRandom($this->cpfKey));
        $this->assertFalse(Validator::validateRandom($this->cnpjKey));
        $this->assertFalse(Validator::validateRandom($this->emailKey));
        $this->assertFalse(Validator::validateRandom($this->phoneNumberKey));
    }

    public function test_it_can_validate_email_keys()
    {
        $this->assertTrue(Validator::validateEmail($this->emailKey));
        $this->assertFalse(Validator::validateEmail($this->randomKey));
        $this->assertFalse(Validator::validateEmail($this->cpfKey));
        $this->assertFalse(Validator::validateEmail($this->cnpjKey));
        $this->assertFalse(Validator::validateEmail($this->phoneNumberKey));
    }

    public function test_it_can_validate_cpf_keys()
    {
        $this->assertTrue(Validator::validateCPF($this->cpfKey));
        $this->assertFalse(Validator::validateCPF($this->randomKey));
        $this->assertFalse(Validator::validateCPF($this->cnpjKey));
        $this->assertFalse(Validator::validateCPF($this->phoneNumberKey));
        $this->assertFalse(Validator::validateCPF($this->emailKey));
    }

    public function test_it_can_validate_cnpj_keys()
    {
        $this->assertTrue(Validator::validateCNPJ($this->cnpjKey));
        $this->assertFalse(Validator::validateCNPJ($this->randomKey));
        $this->assertFalse(Validator::validateCNPJ($this->cpfKey));
        $this->assertFalse(Validator::validateCNPJ($this->phoneNumberKey));
        $this->assertFalse(Validator::validateCNPJ($this->emailKey));
    }

    public function test_it_can_validate_phone_number_keys()
    {
        $this->assertTrue(Validator::validatePhoneNumber($this->phoneNumberKey));
        $this->assertFalse(Validator::validatePhoneNumber($this->randomKey));
        $this->assertFalse(Validator::validatePhoneNumber($this->cnpjKey));
        $this->assertFalse(Validator::validatePhoneNumber($this->emailKey));
    }
}
