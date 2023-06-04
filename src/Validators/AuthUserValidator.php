<?php

namespace App\Validators;

use App\Core\Validator;

class AuthUserValidator extends Validator
{
    protected $fields = [
        "login" => "логин",
        "password" => "пароль"
    ];

    public function rules()
    {
        return [
            "login" => ["required"],
            "password" => ["required"],
        ];
    }
}