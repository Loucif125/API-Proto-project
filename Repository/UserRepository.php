<?php

require_once './Repository/PdoHelper.php';

class UserRepository extends PdoHelper
{
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new UserRepository();
        return self::$instance;
    }

    public function createUser($user) {
        //request
        $request =  parent::getPdo()->prepare("INSERT INTO `user` (`user_id`, `username`, `password`, `token`) VALUES (?, ?, ?, ?)");
        $result = $request->execute(array($user->getUserId(), $user->getUsername(), $user->getPassword(), $user->getToken()));
        return $result;
    }

    public function getUserByUsername($username)
    {
        //request
        $request = parent::getPdo()->prepare("SELECT * FROM `user` WHERE `username` LIKE :u ");
        $request->bindParam(':u', $username);
        $request->execute();

        //preparation of the data obtained before return
        $pdoresults = $request->fetchAll();
        $user = new UserModel();
        if ($pdoresults != null && isset($pdoresults[0])) {
            $user->setUserByRequest($pdoresults[0]);
        }
        return $user;
    }

    public function getUserByToken($token)
    {
        //request
        $request = parent::getPdo()->prepare("SELECT * FROM `user` WHERE `token` LIKE :t ");
        $request->bindParam(':t', $token);
        $request->execute();

        //preparation of the data obtained before return
        $pdoresults = $request->fetchAll();
        $user = new UserModel();
        if ($pdoresults != null && isset($pdoresults[0])) {
            $user->setUserByRequest($pdoresults[0]);
            return $user;
        }
        else
            return null;
    }
}