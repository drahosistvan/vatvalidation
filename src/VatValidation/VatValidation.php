<?php

namespace VatValidation;

use SoapClient;
use VatValidation\Exceptions\ImmutableDataException;
use VatValidation\Exceptions\InvalidObjectPropertyException;
use VatValidation\Exceptions\WrongVatNumberFormatException;

class VatValidation
{
    const WSDL_URL = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';

    private $countryCode;
    private $vatNumber;
    private $valid = false;
    private $validated = false;
    private $name;
    private $address;
    private $client;

    public function __construct($client = null)
    {
        $this->client = $client ?: new SoapClient(self::WSDL_URL, ['exceptions' => true]);
    }

    public function validate($number)
    {
        if ($this->formatVatNumber($number)) {
            $this->callWebService();
        }

        return $this;
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        throw new InvalidObjectPropertyException('This property is not exists in this object.');
    }

    public function __set($name, $value)
    {
        throw new ImmutableDataException('This data is only readable');
    }

    private function formatVatNumber($number)
    {
        $pattern = '/^(AT|BE|BG|CY|CZ|DE|DK|EE|ES|FI|FR|GB|GR|HR|HU|IE|IT|LT|LU|LV|MT|NL|PL|PT|RO|SE|SI|SK)[A-Z0-9]{6,20}$/';
        $number = strtoupper($number);

        if (preg_match($pattern, $number)) {
            $this->countryCode = substr($number, 0, 2);
            $this->vatNumber = substr($number, 2, strlen($number) - 2);

            return true;
        }

        throw new WrongVatNumberFormatException();
    }

    private function callWebService()
    {
        $response = $this->client->checkVat([
            'countryCode' => $this->countryCode,
            'vatNumber'   => $this->vatNumber,
        ]);

        $this->validated = true;

        if ($response->valid) {
            return $this->processResponse($response);
        }

        return $this;
    }

    private function processResponse($response)
    {
        $this->valid = true;
        $this->name = $response->name;
        $this->address = $response->address;
    }

    public function toArray()
    {
        return [
            'valid'       => $this->valid,
            'countryCode' => $this->countryCode,
            'vatNumber'   => $this->vatNumber,
            'name'        => $this->name,
            'address'     => $this->address,
            'validated'   => $this->validated,
        ];
    }
}
