<?php

namespace App\Validators;

use App\Core\Validator;

class TodoPostValidator extends Validator
{
    protected $fields = [
        "title" => "задача",
        "email" => "E-mail",
        "name" => "имя",
        "date" => "дата"
    ];

    public function rules()
    {
        return [
            "title" => ["required"],
            "email" => ["required", "email"],
            "name" => ["required"],
            "date" => ["required", "date"],
        ];
    }
}