<?php

namespace VatValidation\Tests;

use PHPUnit_Framework_TestCase;
use VatValidation\Exceptions\ImmutableDataException;
use VatValidation\Exceptions\InvalidObjectPropertyException;
use VatValidation\Exceptions\WrongVatNumberFormatException;
use VatValidation\VatValidation;

class ValidationTest extends PHPUnit_Framework_TestCase
{
    /** @test * */
    public function it_has_a_validate_method()
    {
        $this->assertTrue(
            method_exists(new VatValidation(), 'validate'),
            'validate method not exists in VatValidation Class'
        );
    }

    /** @test * */
    public function it_sets_the_proper_values_based_on_vies_webservice()
    {
        $valid = new VatValidation();
        $valid->validate('HU66861328');
        $this->assertEquals($valid->validated, true);
        $this->assertEquals($valid->countryCode, 'HU');
        $this->assertEquals($valid->vatNumber, '66861328');
        $this->assertEquals($valid->valid, true);

        $invalid = new VatValidation();
        $invalid->validate('HU66861328123123');
        $this->assertEquals($invalid->validated, true);
        $this->assertEquals($invalid->countryCode, 'HU');
        $this->assertEquals($invalid->vatNumber, '66861328123123');
        $this->assertEquals($invalid->valid, false);
    }

    /** @test
     * @dataProvider validVatNumbers
     */
    public function it_checks_proper_vat_number_formats($number)
    {
        $object = new VatValidation();
        $this->assertTrue(
            $this->callProtectedMethod($object, 'formatVatNumber', [$number])
        );
    }

    public function validVatNumbers()
    {
        return [
            ['HU66861328'],
            ['CY123123N'],
            ['CY99000232S'],
            ['ESN1233210D'],
            ['IE123321EH'],
            ['IE453345K'],
            ['IE4E5433H'],
        ];
    }

    /** @test
     * @dataProvider invalidVatNumbers
     */
    public function it_throws_exception_if_number_format_is_invalid($number)
    {
        $this->expectException(WrongVatNumberFormatException::class);
        $object = new VatValidation();
        $this->callProtectedMethod($object, 'formatVatNumber', [$number]);
    }

    public function invalidVatNumbers()
    {
        return [
            ['1234567'],
            ['ASDFGHY'],
            ['AS123A'],
        ];
    }

    /** @test * */
    public function it_has_to_array_method()
    {
        $validation = new VatValidation();
        $this->assertTrue(
            method_exists($validation, 'toArray'),
            'toArray method not exists in VatValidation Class'
        );
        $this->assertArrayHasKey('countryCode', $validation->toArray());
    }

    /** @test * */
    public function it_tracks_that_webservice_was_called()
    {
        $validation = new VatValidation();
        $this->assertFalse($validation->validated);

        $validation->validate('HU66861328');
        $this->assertTrue($validation->validated);
    }

    /** @test * */
    public function it_prevent_external_data_manipulation()
    {
        $this->expectException(ImmutableDataException::class);
        $validation = (new VatValidation())->validate('HU66861328');

        $validation->countryCode = 1;
    }

    /** @test * */
    public function it_throws_exception_if_invalid_property_is_accessed()
    {
        $this->expectException(InvalidObjectPropertyException::class);
        $validation = (new VatValidation())->validate('HU66861328');

        $validation->invalidProperty;
    }

    public function callProtectedMethod($object, $name, array $args)
    {
        $class = new \ReflectionClass($object);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }
}
