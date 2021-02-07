<?php

require_once "./Repository/UserRepository.php";

class AuthenticationService
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new AuthenticationService();
        return self::$instance;
    }

    public function createUser($username, $password)
    {
        //check the format and availability of data
        if ($this->checkUsernameFormat($username) && $this->checkPasswordFormat($password) &&
            $this->checkAvailabilityUserName($username)) {
            $newUser = new UserModel();
            $newUser->setUser(null, $username, $this->encodePassword($password), $this->generateToken());
            if (UserRepository::getInstance()->createUser($newUser)) {
                return $newUser;
            }
        }
        throw new Exception('bad format for username or password');
    }

    public function login($username, $password)
    {
        //check the format of data
        if ($this->checkUsernameFormat($username) && $this->checkPasswordFormat($password)) {
            $userFind = $this->getUserByUsername($username);
            //check user data with data in database
            if ($userFind->getUsername() == $username && $userFind->getPassword() === $this->encodePassword($password)) {
                //return token
                return $userFind->getToken();
            }
        }
        throw new UnauthorizedException('invalid_credentials');
    }

    private function checkUserNameFormat($username)
    {
        if (empty($username) || $username == "") {
            throw new FormatException('Username not should be empty');
        } elseif (!ctype_alnum($username)) {
            throw new FormatException('Username need contains only alphanumeric characters');
        } elseif (strlen($username) < 2 || strlen($username) > 30) {
            throw new FormatException('The length of the username must be between 2 and 30 characters');
        }
        return true;
    }

    private function checkPasswordFormat($password)
    {
        if (!empty($password) && $password != "") {
            if (strlen($password) < 6 || strlen($password) > 30) {
                throw new FormatException('Password must contain between 6 and 12 characters !');
            } elseif (!preg_match("#[0-9]+#", $password)) {
                throw new FormatException('Password must contain at least 1 Number !');
            } elseif (!preg_match("#[A-Z]+#", $password)) {
                throw new FormatException('Password must contain ct least 1 Capital Letter ');
            } elseif (!preg_match("#[a-z]+#", $password)) {
                throw new FormatException('Password must contain at least 1 Lowercase Letter !');
            } elseif (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
                throw new FormatException('Password must contain at least 1 Special Character !');
            }
            return true;
        }
    }

    private function checkAvailabilityUserName($username)
    {
        $userFindName = null;
        $userFind = $this->getUserByUsername($username);
        if ($userFind) {
            $userFindName = $userFind->getUsername();
        }
        if ($userFindName == $username) {
            throw new ConflictException('This username is already used ! Please login or choose another');
        }
        return true;
    }

    private function getUserByUsername($username)
    {
        $userFind = UserRepository::getInstance()->getUserByUsername($username);
        if (!$userFind) {
            throw new UnauthorizedException('User not found');
        }
        return $userFind;
    }

    public function checkUserAuthentification($header)
    {
        if (!isset($header['auth_token'])) {
            throw new UnauthorizedException('Auth_token not define in header');
        }
        $userFind = $this->getUserByToken($header['auth_token']);
        if (!$userFind) {
            throw new UnauthorizedException('498 - invalid auth_token');
        }
        return $userFind;
    }

    public function getUserByToken($auth_token)
    {
        $userFind = UserRepository::getInstance()->getUserByToken($auth_token);
        if (!$userFind) {
            throw new UnauthorizedException('Bad_credential, token not recognize');
        }
        return $userFind;
    }

    private function encodePassword($password)
    {
        $encodePassword = sha1($password);
        return $encodePassword;
    }

    public function remove($username) {

        return null;
    }

    private function generateToken()
    {
        return ($this->Salt());
    }

    private function Salt()
    {
        return substr(strtr(base64_encode(hex2bin($this->RandomToken(32))), '+', '.'), 0, 44);
    }

    private function RandomToken($length = 32)
    {
        if (!isset($length) || intval($length) <= 8) {
            $length = 32;
        }
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length));
        }
        if (function_exists('mcrypt_create_iv')) {
            return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length));
        }
    }
}
