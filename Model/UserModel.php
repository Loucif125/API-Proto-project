<?php

/**
 * Class UserModel
 */
class UserModel
{

    private $userId = 0;
    private $username = null;
    private $password = null;
    private $token = null;

    public function serialize() {
        return array(
            'user_id' => $this->userId,
            'username' => $this->username,
            'password' => $this->password,
            'auth_token' => $this->token
        );
    }
    public function unserialize($pdoResults) {
        $this->setUserId($pdoResults['user_id']);
        $this->setUsername($pdoResults['username']);
        $this->setPassword($pdoResults['password']);
        $this->setToken($pdoResults['token']);
    }
    /**
     * @param $username
     * @param $password
     */

    public function setUserByRequest($pdoResults)
    {
        $this->setUserId($pdoResults['user_id']);
        $this->setUsername($pdoResults['username']);
        $this->setPassword($pdoResults['password']);
        $this->setToken($pdoResults['token']);
    }

    public function setUser($id, $username, $password, $token)
    {
        $this->setUserId($id);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setToken($token);
    }
    /**
     * @return null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param null $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

}