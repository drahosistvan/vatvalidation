<?php
namespace VatValidation\Tests;

use PHPUnit_Framework_TestCase;
use VatValidation\Exceptions\WrongVatNumberFormatException;
use VatValidation\VatValidation;

class ValidationTest extends PHPUnit_Framework_TestCase
{
    /** @test **/
    public function it_has_a_validate_method()
    {
        $this->assertTrue(
            method_exists(new VatValidation(), 'validate'),
            'validate method not exists in VatValidation Class'
        );
    }

    /** @test **/
    public function it_can_casted_to_array()
    {
        $st = (new VatValidation())->validate('HU66861328');
        $this->assertEquals($st['countryCode'], 'HU');

        $this->assertTrue(isset($st['countryCode']));
        $this->assertTrue(isset($st['vatNumber']));
        $this->assertTrue(isset($st['name']));
        $this->assertTrue(isset($st['address']));
        $this->assertTrue(is_array(array($st)));
    }


    /** @test **/
    public function it_sets_the_proper_values_based_on_vies_webservice()
    {

    }

    /** @test **/
    public function it_throws_exception_if_number_format_is_invalid()
    {
        $this->expectException(WrongVatNumberFormatException::class);
        (new VatValidation())->validate('asd');
    }

    /** @test **/
    public function it_has_to_array_method()
    {
        $validation = new VatValidation();
        $this->assertTrue(
            method_exists($validation, 'toArray'),
            'toArray method not exists in VatValidation Class'
        );
        $this->assertArrayHasKey('countryCode', $validation->toArray());
    }

    /** @test **/
    public function it_tracks_that_webservice_was_called()
    {
        $validation = new VatValidation();
        $this->assertFalse($validation->validated);

        $validation->validate('HU66861328');
        $this->assertTrue($validation->validated);
    }
}
