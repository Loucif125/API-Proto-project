<?php

require_once './Repository/PdoHelper.php';
require_once './Model/TaskListModel.php';

class TaskListRepository extends PdoHelper
{
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new TaskListRepository();
        return self::$instance;
    }

    public function createTaskList($taskListModel) {
        $request =  parent::getPdo()->prepare("INSERT INTO tasklist (`tasklist_id`, `user_id`, `title`) VALUES (?, ?, ?)");
        $success = $request->execute(array($taskListModel->getTaskListId(), $taskListModel->getUserId(), $taskListModel->getTitle()));
         return $success;
    }

    public function updateTaskList($taskListId, $title) {
        $request =  parent::getPdo()->prepare("UPDATE `tasklist` SET `title` = :t WHERE `tasklist`.`tasklist_id` = :id");
        $request->bindParam(":t",$title);
        $request->bindParam(":id",$taskListId);
        $success = $request->execute();
        return $success;
    }

    public function deleteTaskList($taskListId) {
        $request =  parent::getPdo()->prepare("DELETE FROM `tasklist` WHERE `tasklist`.`tasklist_id` = :id");
        $request->bindParam(":id",$taskListId);
        $success = $request->execute();
        return $success;
    }

    public function getTaskList($userId) {
        //request
        $request =  parent::getPdo()->prepare("SELECT * FROM `tasklist` WHERE user_id LIKE :u");
        $request->bindParam(":u",$userId);
        $request->execute();

        //preparation of the data obtained before return
        $pdoresults = $request->fetchAll();
        $taskListArray = [];
        foreach ($pdoresults as $key => $taskList) {
            $newTaskList = new TaskListModel();
            $newTaskList->unserialize($taskList);
            $taskListArray[$key] = $newTaskList->serialize();
        }
        return $taskListArray;
    }

    public function getTaskListById($taskListId) {
        //request
        $request =  parent::getPdo()->prepare("SELECT * FROM `tasklist` WHERE `tasklist_id` = :t");
        $request->bindParam(":t",$taskListId);
        $request->execute();

        //preparation of the data obtained before return
        $pdoresults = $request->fetchAll();
        $newTaskList = new TaskListModel();
        if ($pdoresults != null && isset($pdoresults[0])) {
            $newTaskList->unserialize($pdoresults[0]);
        }
        return $newTaskList;
    }

}