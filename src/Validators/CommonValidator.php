<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class CommonValidator
{
    public static function helloValidate($name)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->length(1, 15)->validate($name)) {
            $errors['name'] = 'Name is required';
        }

        return $errors;
    }

    public static function encryptValidate($encryptTxt)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->validate($encryptTxt)) {
            $errors['encryptTxt'] = 'encryptTxt is required';
        }

        return $errors;
    }

    public static function decryptValidate($decryptTxt)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->validate($decryptTxt)) {
            $errors['decryptTxt'] = 'decryptTxt is required';
        }

        return $errors;
    }
}
