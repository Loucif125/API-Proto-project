<?php

require_once './Controller/AccountController.php';
require_once "./Controller/TaskListController.php";
require_once './Exception/RouterException.php';
class Router
{
    public function run()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $method = $_SERVER['REQUEST_METHOD'];

            if (isset($_GET['url'])) {
                //get route
                $url = explode('/', $_GET['url']);
                $nbParameterInUrl = count($url);

                //check if the routes contains /api/ in first parameter
                if ($url[0] != "api") {
                    throw new RouterException('path request need contains [api] in first parameter');
                }

                //save POST parameters in $_data
                $data = json_decode(file_get_contents('php://input'), true);

                //save header parameters in $_header
                $header = apache_request_headers();

                //call controllers by request method
                switch ($method) {
                    case 'GET':
                        return $this->methodGetRoutes($url, $nbParameterInUrl, $header, $data);
                        break;
                    case 'POST':
                        return $this->methodPostRoutes($url, $nbParameterInUrl, $header, $data);
                        break;
                    case 'DELETE':
                        return $this->methodDeleteRoutes($url, $nbParameterInUrl, $header, $data);
                        break;
                    case 'OPTIONS':
                        return new HttpResponseModel(200, '', 'OK');
                    default:
                        throw new RouterException('No Request Method Matches');
                }
            }
        }
        throw new RouterException('No routes matches or missing argument');
    }

    private function methodGetRoutes($urlParameterArray, $argumentCount, $headerData, $parameterData)
    {
        //GET' /api/authToken
        if ($argumentCount == 2 && $urlParameterArray[1] == "authToken") {
            return AccountController::getInstance()->login($headerData);
        }
        //'GET' /api/taskList
        elseif ($argumentCount == 2 && $urlParameterArray[1] == "taskList") {
            return TaskListController::getInstance()->getUserTaskLists($headerData);
        }
        //'GET' /api/taskList/{idList}
        elseif ($argumentCount == 3 && $urlParameterArray[1] == "taskList" && ctype_digit($urlParameterArray[2])) {
            return TaskListController::getInstance()->getUserTaskListById($headerData, $urlParameterArray[2]);
        }
        //'GET' /api/taskList/{taskListId}/tasks
        elseif ($argumentCount == 4 &&  $urlParameterArray[1] == "taskList" && ctype_digit($urlParameterArray[2]) && $urlParameterArray[3] == "tasks") {
            return TaskListController::getInstance()->getTasks($headerData, $urlParameterArray[2]);
        }
        throw new RouterException('No routes matches or missing argument');
    }

    private function methodPostRoutes($urlParameterArray, $argumentCount, $headerData, $parameterData)
    {
        //'POST' /api/user
        if ($argumentCount == 2 && $urlParameterArray[1] == "user") {
            return AccountController::getInstance()->signup($parameterData);
        }
        //POST /api/taskList
        elseif ($argumentCount == 2 && $urlParameterArray[1] == "taskList") {
            return TaskListController::getInstance()->createTaskList($headerData, $parameterData);
        }
        //'POST' /api/taskList/{taskListId}
        elseif ($argumentCount == 3 && $urlParameterArray[1] == "taskList" && ctype_digit($urlParameterArray[2])) {
            return TaskListController::getInstance()->updateTaskList($headerData, $urlParameterArray[2], $parameterData);
        }
        //'POST' /api/taskList/{taskListId}/task
        elseif ($argumentCount == 4 &&  $urlParameterArray[1] == "taskList" && ctype_digit($urlParameterArray[2]) && $urlParameterArray[3] == "task") {
            return TaskListController::getInstance()->createTask($headerData, $urlParameterArray[2], $parameterData);
        }
        //'POST' /api/task/{taskId}
        elseif ($argumentCount == 3 &&  $urlParameterArray[1] == "task" && ctype_digit($urlParameterArray[2])) {
            return TaskListController::getInstance()->updateTask($headerData, $urlParameterArray[2], $parameterData);
        }
        throw new RouterException('No routes matches or missing argument');
    }

    private function methodDeleteRoutes($urlParameterArray, $argumentCount, $headerData, $parameterData)
    {
        //'DELETE' /api/taskList/{taskListId}
        if ($argumentCount == 3 && $urlParameterArray[1] == "taskList" && ctype_digit($urlParameterArray[2])) {
            return TaskListController::getInstance()->deleteTaskList($headerData, $urlParameterArray[2]);
        }
        //'DELETE' /api/task/{taskId}
        elseif ($argumentCount == 3 && $urlParameterArray[1] == "task" && ctype_digit($urlParameterArray[2])) {
            return TaskListController::getInstance()->deleteTask($headerData, $urlParameterArray[2]);
        }
        throw new RouterException('No routes matches or missing argument');
    }
}
