# VAT Validation package for PHP
[![Build Status](https://travis-ci.org/drahosistvan/vatvalidation.svg?branch=master)](https://travis-ci.org/drahosistvan/vatvalidation)
[![codecov](https://codecov.io/gh/drahosistvan/vatvalidation/branch/master/graph/badge.svg)](https://codecov.io/gh/drahosistvan/vatvalidation)

## Installation
Simply require the package via composer:
`composer require drahosistvan/vatvalidation`

## How to use
```php
$validation = new VatValidation();
$validation->validate('UK123321213');

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
$validation->validate('UK123321213');
var_dump($validation->toArray());
```