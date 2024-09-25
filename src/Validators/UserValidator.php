<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class UserValidator
{
    public static function createValidate($data)
    {
        $errors = [];

        if (!v::stringType()->notEmpty()->validate($data['name'] ?? '')) {
            $errors['name'] = 'Name is required';
        }

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address';
        }

        if (!v::stringType()->notEmpty()->validate($data['password' ?? ''])) {
            $errors['password'] = 'Invalid password';
        }

        return $errors;
    }

    public static function loginValidate($data)
    {
        $errors = [];

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address';
        }

        if (!v::stringType()->notEmpty()->validate($data['password' ?? ''])) {
            $errors['password'] = 'Invalid password';
        }

        return $errors;
    }
}
