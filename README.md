# VAT Validation package for PHP
[![Build Status](https://travis-ci.org/drahosistvan/vatvalidation.svg?branch=master)](https://travis-ci.org/drahosistvan/vatvalidation)
[![codecov](https://codecov.io/gh/drahosistvan/vatvalidation/branch/master/graph/badge.svg)](https://codecov.io/gh/drahosistvan/vatvalidation)
[![Latest Stable Version](https://poser.pugx.org/drahosistvan/vatvalidation/v/stable)](https://packagist.org/packages/drahosistvan/vatvalidation)
[![Total Downloads](https://poser.pugx.org/drahosistvan/vatvalidation/downloads)](https://packagist.org/packages/drahosistvan/vatvalidation)
[![StyleCI](https://styleci.io/repos/109401111/shield?branch=master)](https://styleci.io/repos/109401111)
[![License](https://poser.pugx.org/drahosistvan/vatvalidation/license)](https://packagist.org/packages/drahosistvan/vatvalidation)

## Installation
Simply require the package via composer:
`composer require drahosistvan/vatvalidation`

## How to use
```php
$validation = new VatValidation();
$validation->validate('HU123321213');

if ($validation->valid) {
    print $validation->name;
    print $validation->address;
    print $validation->countryCode;
    print $validation->vatNumber;
    print $validation->valid;
}
```
It has a `toArray` method, so you can cast the validation data to an array.

```php
$validation = new VatValidation();
$validation->validate('HU123321213');
var_dump($validation->toArray());
```