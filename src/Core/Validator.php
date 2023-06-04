<?php

namespace App\Core;

use DateTime;

class Validator
{
    protected $errors = [];

    protected $fields = [];

    protected $data = [];

    public function getErrors()
    {
        return $this->errors;
    }

    public function getData()
    {
        return $this->data;
    }

    public function rules()
    {
        return [];
    }

    public function addError($key, $data)
    {
        if (isset($this->errors[$key])) {
            $this->errors[$key] = array_merge($this->errors[$key], [$data]);
        } else {
            $this->errors[$key] = [$data];
        }
    }

    public function validate($data)
    {
        foreach ($this->rules() as $key => $rule) {
            foreach ($rule as $value) {
                switch ($value) {
                    case 'required':
                        if (isset($data[$key])) {
                            if (!empty($data[$key])) {
                                $this->data[$key] = $data[$key];
                            } else {
                                $this->addError($key, "Поле {$this->fields[$key]} не должно быть пустым");
                            }
                        } else {
                            $this->addError($key, "Поле {$this->fields[$key]} обязательно");
                        }
                        break;
                    case 'email':
                        if (!filter_var($data[$key], FILTER_VALIDATE_EMAIL)) {
                            $this->addError($key, "E-mail адрес {$data[$key]} указан не верно");
                        } else {
                            $this->data[$key] = $data[$key];
                        }
                        break;
                    case 'date':
                        if (!$this->validateDate($data[$key])) {
                            $this->addError($key, "Формат даты должен быть Y-m-d");
                        } else {
                            $this->data[$key] = $data[$key];
                        }
                        break;
                }

            }

        }

        if (count($this->errors) == 0)
            return true;

        return false;
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}