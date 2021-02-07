<?php

require_once './Service/AuthenticationService.php';
require_once './Model/HttpResponseModel.php';

class AccountController
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new AccountController();
        return self::$instance;
    }

    public function signup($data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new FormatException('Username or password not define');
        }

        $newUser = AuthenticationService::getInstance()->createUser($data['username'], $data['password']);
        if ($newUser) {
            $array['auth_token'] = $newUser->getToken();
            return new HttpResponseModel('201', 'Content-Type: application/json', $array);
        }
    }

    public function login($data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new FormatException('Bad username or password');
        }

        $token = AuthenticationService::getInstance()->login($data['username'], $data['password']);
        if ($token) {
            $array['auth_token'] = $token;
            return new HttpResponseModel('200', 'Content-Type: application/json', $array);
        }
    }

    public function remove($data)
    {
        if (!isset($data['username']) || !isset($data['password'])) {
            throw new FormatException('Bad username or password');
        }

        //Remove user account
        $token = AuthenticationService::getInstance()->login($data['username'], $data['password']);
        if ($token) {
            AuthenticationService::getInstance()->remove($data['username']);
        }
    }
}
