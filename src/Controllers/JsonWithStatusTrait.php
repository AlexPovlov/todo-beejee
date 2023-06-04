<?php

namespace App\Controllers;

use App\Core\Response;

trait JsonWithStatusTrait
{
    public function success($data = [], $message = "", $code = 200)
    {
        return Response::json([
            "success" => true,
            "data" => $data,
            "message" => $message
        ], $code);
    }

    public function failure($errors = [], $message = "", $code = 404)
    {
        return Response::json([
            "success" => false,
            "errors" => $errors,
            "message" => $message
        ], $code);
    }
}