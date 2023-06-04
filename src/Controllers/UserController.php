<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Validators\AuthUserValidator;

class UserController extends Controller
{
    use JsonWithStatusTrait;

    public function profileAction()
    {
        $session = Request::session();
        
        return $this->success($session["user"]);
    }

    public function loginAction()
    {
        if (!empty(Request::post())) {
            $validator = new AuthUserValidator();

            if (!$validator->validate(Request::post())) {
                return $this->failure($validator->getErrors(), "", 422);
            }

            $data = $validator->getData();
            $user = $this->model->where(["login", $data["login"]])->first();

            if ($user) {
                if (password_verify($data["password"], $user["password"])) {

                    unset($user["password"]);
                    $token = $this->generateToken();

                    Request::setSession("token", $token);
                    Request::setSession("user", $user);

                    return $this->success(["user" => $user, "token" => $token]);
                }
            }

            return $this->failure(["password" => ["Не верный логин или пароль"]], "", 422);
        }
    }

    function generateToken()
    {
        $length = 32;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < $length; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $token;
    }

    public function logoutAction()
    {
        Request::truncreateSession();
        return $this->success();
    }
}