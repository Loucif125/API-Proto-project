<?php

require_once "./Repository/TaskListRepository.php";
require_once "./Repository/TaskRepository.php";
require_once "./Exception/NotFoundException.php";

class TaskListService
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new TaskListService();
        return self::$instance;
    }

    public function createTaskList($title, $userId)
    {
        //check format of data
        $this->checkDataFormat($title);
        //create a TaskListModel
        $newTaskList = new TaskListModel();
        $newTaskList->setTaskList(0, $userId, $title);
        //Send List in database
        if ($newListDB = \TaskListRepository::getInstance()->createTaskList($newTaskList)) {
            return $newListDB;
        }
        throw new UnknownException('TaskList not created');
    }

    public function createTask($content, $userId, $taskListId) {
        //vérifier les droits de l'utilisateur sur la liste et l'éxistence de la liste
        $this->checkUserRightOnTaskList($taskListId,  $userId);

        //vérifier le format du contenu
        $this->checkDataFormat($content);

        //créer la nouvelle tâche
        $newTask = new TaskModel();
        $newTask->setTaskModel($userId,$taskListId, $content, "active");

        //envois des données en base de données
        $taskIsCreated = \TaskRepository::getInstance()->createTask($newTask);
        //retourner une réponse
        if ($taskIsCreated)
            return ($taskIsCreated);
        //si pas de réponse
        throw new UnknownException('Task not created');
    }
    public function updateTaskList($taskListId, $userId, $title)
    {
        $this->checkUserRightOnTaskList($taskListId,  $userId);
        //check format of data
        if ($this->checkDataFormat($title))
        {
            //Send List in database to update
            if ($updatedListDB = \TaskListRepository::getInstance()->updateTaskList($taskListId, $title)) {
                return $updatedListDB;
            };
        }
        throw new UnknownException('TaskList not updated');
    }

    public function updateTask($taskId, $userId, $data) {
        $this->checkUserRightOnTask($taskId,  $userId);

        $taskInDBToUpdate = $this->getTaskById($taskId, $userId);

        if (isset($data['tasklist_id'])) {
            if(!ctype_digit($data['tasklist_id'])){
                throw new FormatException('tasklist_id format not valid');
            }
            $this->checkUserRightOnTaskList($data['tasklist_id'], $userId);
            $taskInDBToUpdate->setTaskListId($data['tasklist_id']);
        }
        if (isset($data['status'])){
            if(!$this->checkValidStatus($data['status'])) {
                throw new FormatException('Status not valid');
            }
            $taskInDBToUpdate->setStatus($data['status']);
        }
        if (isset($data['content'])) {
            $this->checkDataFormat($data['content']);
            $taskInDBToUpdate->setContent($data['content']);
        }

        $success = \TaskRepository::getInstance()->updateTask($taskInDBToUpdate);
        if ($success)
            return $success;
        throw new UnknownException('Task not updated');
    }

    public function deleteTaskList($taskListId, $userId)
    {
        $this->checkUserRightOnTaskList($taskListId,  $userId);

        if ($deletedListDB = \TaskListRepository::getInstance()->deleteTaskList($taskListId)) {
            return $deletedListDB;
        };
        throw new UnknownException('TaskList not deleted');
    }

    public function deleteTask($taskId, $userId)
    {
        $this->checkUserRightOnTask($taskId,  $userId);

        if ($success = \TaskRepository::getInstance()->deleteTask($taskId)) {
            return $success;
        };
        throw new UnknownException('Task not deleted');
    }

    public function getTaskLists($userId) {
        $taskList = \TaskListRepository::getInstance()->getTaskList($userId);
        return $taskList;
    }

    public function getTaskById($taskId, $userId) {
        $task = \TaskRepository::getInstance()->getTaskById($taskId);
        if (!$task || !$task->getTaskId())
            throw new NotFoundException("Task not found");
        if ($task->getUserId() != $userId)
            throw new UnauthorizedException("Invalid_rights on this ressources");
        return $task;
    }

    public function getTaskListById($taskListId, $userId) {
        $taskList = \TaskListRepository::getInstance()->getTaskListById($taskListId);
        if (!$taskList || !$taskList->getTasklistId())
            throw new NotFoundException("TaskList ".$taskListId." not found");
        if ($taskList->getUserId() != $userId)
            throw new UnauthorizedException("Invalid_rights on this ressources");
        return $taskList;
    }

    public function getTasksByIdTaskList($taskListId, $userId) {
        //check user rights on this list
        $this->checkUserRightOnTaskList($taskListId,  $userId);

        //get all tasks in this listId
        $taskFounded = \TaskRepository::getInstance()->getAllTasksInList($taskListId);

        //return tasks
        if (is_array($taskFounded)) {
            return $taskFounded;
        }
        throw new UnknownException("Ressource not finded");
    }

    private function checkDataFormat($data) {

        if (empty($data) || $data == ""){
            throw new FormatException('Data not should be empty');
        }
        else if (!ctype_alnum(str_replace(" ", "", $data))) {
            throw new FormatException('Data need contains only alphanumeric characters, excepted space');
        }
        else if (strlen($data) < 1 || strlen($data) > 250) {
            throw new FormatException('The length of the data must be between 1 and 250 characters');
        }
        return true;
    }

    private function checkUserRightOnTaskList($taskListId, $userId) {
        $taskListInDB = $this->getTaskListById($taskListId, $userId);
        if ($taskListInDB){
            return true;
        }
        return false;
    }

    private function checkUserRightOnTask($taskId, $userId) {
        $taskInDB = $this->getTaskById($taskId, $userId);
        if ($taskInDB){
            return true;
        }
        return false;
    }

    private function checkValidStatus($status) {
        switch ($status) {
            case "active" :
                return true;
                break;
            case "done":
                return true;
                break;
            default:
                return false;
                break;
        }
        return false;
    }
}