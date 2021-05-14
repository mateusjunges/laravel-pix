<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Exceptions\ParserException;
use Junges\Pix\Parser;
use Junges\Pix\Pix;

class ParserTest extends TestCase
{
    public function test_it_can_get_the_correct_key_type()
    {
        $this->assertEquals(Pix::CPF_KEY_TYPE, Parser::getKeyType($this->cpfKey));
        $this->assertEquals(Pix::RANDOM_KEY_TYPE, Parser::getKeyType($this->randomKey));
        $this->assertEquals(Pix::CNPJ_KEY_TYPE, Parser::getKeyType($this->cnpjKey));
        $this->assertEquals(Pix::PHONE_NUMBER_KEY_TYPE, Parser::getKeyType($this->phoneNumberKey));
        $this->assertEquals(Pix::EMAIL_KEY_TYPE, Parser::getKeyType($this->emailKey));
    }

    public function test_it_throws_parser_exception_with_invalid_keys()
    {
        $this->expectException(ParserException::class);

        Parser::getKeyType('');
    }
}
