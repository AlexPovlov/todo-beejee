<?php

namespace App\Core;

class Request
{
    private $get;
    private $post;
    private $session;

    private static function prepare_data($data)
    {
        $new_data = [];

        foreach ($data ?? [] as $key => $value) {

            $key = self::prepare_string($key);

            if (is_array($value)) {
                $value = self::prepare_data($value);
            } else {
                if ($value) {
                    $value = self::prepare_string($value);
                }
            }

            $new_data[$key] = $value;
        }

        return $new_data;
    }

    public static function prepare_string($string)
    {
        return trim(htmlspecialchars($string, ENT_QUOTES), " ");
    }

    public static function get()
    {
        return self::prepare_data($_GET);
    }

    public static function post()
    {
        $data = $_POST;

        if (in_array("application/json", self::headers()) and empty($data) and $_SERVER["REQUEST_METHOD"] != "GET") {
            $data = json_decode(file_get_contents('php://input'), true);
        }

        return self::prepare_data($data);
    }

    public static function cookie()
    {
        return self::prepare_data($_COOKIE);
    }

    public static function session()
    {
        return self::prepare_data($_SESSION);
    }

    public static function setSession($key, $data)
    {
        return $_SESSION[$key] = $data;
    }

    public static function truncreateSession()
    {
        session_destroy();
        $_SESSION = [];
    }

    public static function headers()
    {
        return explode(", ", self::prepare_string($_SERVER["HTTP_ACCEPT"]));
    }
}