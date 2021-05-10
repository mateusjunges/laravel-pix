<?php

namespace Junges\Pix\Tests;

use Junges\Pix\Exceptions\ParserException;
use Junges\Pix\Parser;
use Junges\Pix\Pix;

class ParserTest extends TestCase
{
    public function test_it_can_get_the_correct_key_type()
    {
        $cpf = "497.442.110-75";
        $randon = "7d9f0335-8dcc-4054-9bf9-0dbd61d36906";
        $cnpj = "07.949.599/0001-53";
        $phoneNumber = "+5542999999999";
        $email = "mateus@junges.dev";

        $this->assertEquals(Pix::CPF_KEY_TYPE, Parser::getKeyType($cpf));
        $this->assertEquals(Pix::RANDOM_KEY_TYPE, Parser::getKeyType($randon));
        $this->assertEquals(Pix::CNPJ_KEY_TYPE, Parser::getKeyType($cnpj));
        $this->assertEquals(Pix::PHONE_NUMBER_KEY_TYPE, Parser::getKeyType($phoneNumber));
        $this->assertEquals(Pix::EMAIL_KEY_TYPE, Parser::getKeyType($email));
    }

    public function test_it_throws_parser_exception_with_invalid_keys()
    {
        $this->expectException(ParserException::class);

        Parser::getKeyType("");
    }
}