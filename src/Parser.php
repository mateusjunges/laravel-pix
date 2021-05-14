<?php

namespace Junges\Pix;

use Junges\Pix\Exceptions\InvalidPixKeyException;
use Junges\Pix\Exceptions\ParserException;

class Parser
{
    /**
     * @throws Exceptions\PixException
     * @throws ParserException
     */
    public static function parse(string $key): string
    {
        $type = self::getKeyType($key);

        switch ($type) {
            case Pix::RANDOM_KEY_TYPE:
                return $key;
            case Pix::CPF_KEY_TYPE:
                return self::parseCpf($key);
            case Pix::CNPJ_KEY_TYPE:
                return self::parseCnpj($key);
            case Pix::EMAIL_KEY_TYPE:
                return self::parseEmail($key);
            case Pix::PHONE_NUMBER_KEY_TYPE:
                return self::parsePhoneNumber($key);
            default:
                throw InvalidPixKeyException::invalidKeyType($type);
        }
    }

    public static function parseEmail(string $email): string
    {
        return str_replace(' ', '@', $email);
    }

    public static function parseDocument(string $document): string
    {
        return preg_replace('/[^\d]+/', '', $document);
    }

    public static function parseCpf(string $cpf): string
    {
        return self::parseDocument($cpf);
    }

    public static function parseCnpj(string $cnpj): string
    {
        return self::parseDocument($cnpj);
    }

    public static function parsePhoneNumber(string $phone): string
    {
        $phone = str_replace('+55', '', $phone);
        $phone = preg_replace('/[^\d]+/', '', $phone);

        return config('laravel-pix.country_phone_code', '+55').$phone;
    }

    public static function parseTransactionId(string $transaction_id): string
    {
        if (empty($transaction_id) || $transaction_id === '***') {
            return '***';
        }

        return preg_replace('/[^A-Za-z0-9]+/', '', $transaction_id);
    }

    /**
     * @throws ParserException
     */
    public static function getKeyType(string $key): string
    {
        if (Validator::validateRandom($key)) {
            return Pix::RANDOM_KEY_TYPE;
        }

        if (Validator::validateEmail($key)) {
            return Pix::EMAIL_KEY_TYPE;
        }

        if (Validator::validateCPF($key)) {
            return Pix::CPF_KEY_TYPE;
        }

        if (Validator::validateCnpj($key)) {
            return Pix::CNPJ_KEY_TYPE;
        }

        if (Validator::validatePhoneNumber($key)) {
            return Pix::PHONE_NUMBER_KEY_TYPE;
        }

        throw ParserException::cantParsePixKey();
    }
}
