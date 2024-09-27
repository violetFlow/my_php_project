<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class CommonValidator
{
    /**
     * 挨拶アクションのパラメータをチェックする
     * @param $name 挨拶する相手
     * @return $errors エラーメッセージ
     */
    public static function helloValidate($name)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->length(1, 15)->validate($name)) {
            $errors['name'] = 'Name is required';
        }

        return $errors;
    }

    /**
     * 暗号化アクションのパラメータをチェックする
     * @param $encryptTxt 暗号化するテキスト
     * @return $errors エラーメッセージ
     */
    public static function encryptValidate($encryptTxt)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->validate($encryptTxt)) {
            $errors['encryptTxt'] = 'encryptTxt is required';
        }

        return $errors;
    }

    /**
     * 複合化アクションのパラメータをチェックする
     * @param $decryptTxt 複合化する暗号化テキスト
     * @return $errors エラーメッセージ
     */
    public static function decryptValidate($decryptTxt)
    {
        $errors = [];
        if (!v::stringType()->notEmpty()->noWhitespace()->validate($decryptTxt)) {
            $errors['decryptTxt'] = 'decryptTxt is required';
        }

        return $errors;
    }
}
