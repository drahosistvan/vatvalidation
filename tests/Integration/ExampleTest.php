<?php

class ExampleTest extends PHPUnit_Framework_TestCase
{
    public function testExample()
    {
        new \VatValidation\VatValidation();
        $this->assertEquals(1, 1);
    }

}
