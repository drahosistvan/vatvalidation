<?php

namespace VatValidation;

use ArrayAccess;
use SoapClient;
use VatValidation\Exceptions\WrongVatNumberFormatException;

class VatValidation implements ArrayAccess
{
    const WSDL_URL = "http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl";

    public $countryCode;
    public $vatNumber;
    public $valid = false;
    public $validated = false;
    public $name;
    public $address;

    private $client;

    function __construct($client = null)
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

    private function formatVatNumber($number)
    {
        $pattern = "/^(AT|B[GE]|C[YZ]|D[EK]|E[ELS]|F[IR]|GB|H[RU]|I[ET]|L[TUV]|MT|NL|P[LT]|RO|S[EIK])([A-Z]|[0-9]+$)/";
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
            "valid"       => $this->valid,
            "countryCode" => $this->countryCode,
            "vatNumber"   => $this->vatNumber,
            "name"        => $this->name,
            "address"     => $this->address,
            "validated"   => $this->validated,
        ];
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return isset($this->$offset) ? $this->$offset : null;
    }
}