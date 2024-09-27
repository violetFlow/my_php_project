<?php

namespace App\Validators;

use Respect\Validation\Validator as v;

class UserValidator
{
    /**
     * ユーザーを１人取得するアクションのパラメータをチェックする
     * @param $id ユーザーID
     * @return $errors エラーメッセージ
     */
    public static function readOneValidate($id)
    {
        $errors = [];

        if (!v::stringType()->notEmpty()->validate($id ?? '')) {
            $errors['id'] = 'id is required';
        }

        return $errors;
    }

    /**
     * ユーザーを１人作成するアクションのパラメータをチェックする
     * @param $data ユーザー情報
     * @return $errors エラーメッセージ
     */
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

    /**
     * ユーザーを１人更新するアクションのパラメータをチェックする
     * @param $id ユーザーID
     * @param $data ユーザー情報
     * @return $errors エラーメッセージ
     */
    public static function updateValidate($id, $data)
    {
        $errors = [];

        if (!v::stringType()->notEmpty()->validate($id ?? '')) {
            $errors['id'] = 'id is required';
        }

        if (!v::stringType()->notEmpty()->validate($data['name'] ?? '')) {
            $errors['name'] = 'Name is required';
        }

        if (!v::email()->validate($data['email'] ?? '')) {
            $errors['email'] = 'Invalid email address';
        }

        return $errors;
    }

    /**
     * ユーザーを１人削除するアクションのパラメータをチェックする
     * @param $id ユーザーID
     * @return $errors エラーメッセージ
     */
    public static function deleteValidate($id)
    {
        $errors = [];

        if (!v::stringType()->notEmpty()->validate($id ?? '')) {
            $errors['id'] = 'id is required';
        }

        return $errors;
    }

    /**
     * ログインアクションのパラメータをチェックする
     * @param $data ユーザー情報
     * @return $errors エラーメッセージ
     */
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
