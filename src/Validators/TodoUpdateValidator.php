<?php

namespace App\Validators;

use App\Core\Validator;

class TodoUpdateValidator extends Validator
{
    protected $fields = [
        "title" => "задача",
        "email" => "E-mail",
        "name" => "имя"
    ];

    public function rules()
    {
        return [
            "title" => ["required"],
            "email" => ["required", "email"],
            "name" => ["required"],
        ];
    }
}