<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class UserValidator {
    public static function validate($data) {
        $errors = [];

        if (!v::stringType()->notEmpty()->validate($data['name'] ?? '')) {
            $errors['name'] = 'Name is required';
        }

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address';
        }

        return $errors;
    }
}
