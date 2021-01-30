<?php

require_once "./Model/TaskListModel.php";
require_once "./Model/TaskModel.php";
require_once "./Service/TaskListService.php";
require_once "./Service/AuthenticationService.php";

class TaskListController
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance) {
            return self::$instance;
        }
        self::$instance = new TaskListController();
        return self::$instance;
    }

    public function createTaskList($header, $data)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        if (!isset($data['title'])) {
            throw new FormatException('title of new list not define in parameter');
        }

        TaskListService::getInstance()->createTaskList($data['title'], $userFound->getUserId());

        return new HttpResponseModel('201', 'Content-Type: application/json', "New list created");
    }

    public function createTask($header, $idTaskList, $data)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        if (!isset($data['content'])) {
            throw new FormatException('Content of new task not define in parameter');
        }

        TaskListService::getInstance()->createTask($data['content'], $userFound->getUserId(), $idTaskList);

        return new HttpResponseModel('201', 'Content-Type: application/json', "New task created");
    }

    public function getTasks($header, $idTaskList)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        $tasksFound = TaskListService::getInstance()->getTasksByIdTaskList($idTaskList, $userFound->getUserId());

        return new HttpResponseModel('200', 'Content-Type: application/json', $tasksFound);
    }

    public function updateTaskList($header, $taskListId, $data)
    {
        if (!isset($data['title'])) {
            throw new FormatException('new title of list not define in parameter');
        }
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        TaskListService::getInstance()->updateTaskList($taskListId, $userFound->getUserId(), $data['title']);

        return new HttpResponseModel('200', 'Content-Type: application/json', "taskList n°" . $taskListId . " has been updated");
    }

    public function updateTask($header, $taskId, $data)
    {
        if (!isset($data['id_tasklist']) && !isset($data['content']) && !isset($data['status'])) {
            throw new FormatException('no data in parameter to update');
        }
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        TaskListService::getInstance()->updateTask($taskId, $userFound->getUserId(), $data);

        return new HttpResponseModel('200', 'Content-Type: application/json', "Task n°" . $taskId . " has been updated");
    }

    public function deleteTaskList($header, $taskListId)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        TaskListService::getInstance()->deleteTaskList($taskListId, $userFound->getUserId());

        return new HttpResponseModel('200', 'Content-Type: application/json', "taskList n°" . $taskListId . " has been deleted");
    }

    public function deleteTask($header, $taskId)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        TaskListService::getInstance()->deleteTask($taskId, $userFound->getUserId());

        return new HttpResponseModel('200', 'Content-Type: application/json', "taskList n°" . $taskId . " has been deleted");
    }

    public function getUserTaskLists($header)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        $taskLists = TaskListService::getInstance()->getTaskLists($userFound->getUserId());

        return new HttpResponseModel('200', 'Content-Type: application/json', $taskLists);
    }
    public function getUserTaskListById($header, $taskListId)
    {
        $userFound = AuthenticationService::getInstance()->checkUserAuthentification($header);

        $taskList = TaskListService::getInstance()->getTaskListById($taskListId, $userFound->getUserId());

        return new HttpResponseModel('200', 'Content-Type: application/json', $taskList->serialize());
    }
}
