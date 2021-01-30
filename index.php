<?php

require_once './Router/Router.php';
require_once './Model/UserModel.php';
require_once './Exception/FormatException.php';
require_once './Exception/ConflictException.php';
require_once './Exception/UnauthorizedException.php';
require_once './Exception/UnknownException.php';

$HttpResponse = null;

function isAssoc(array $arr)
{
    if (array() === $arr) {
        return false;
    }
    return array_keys($arr) !== range(0, count($arr) - 1);
}

try {
    $router = new Router();
    $HttpResponse = $router->run();
} catch (Exception $e) {
    $HttpResponse = new HttpResponseModel($e->getHttpCode(), 'Content-Type: application/json', $e->getMessage());
} finally {
    header_remove();
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Headers: Accept, auth_token, Content-Type, password, username, Authorization, Cache-Control, Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
    header($HttpResponse->getHeader());

    http_response_code($HttpResponse->getCode());
    if (is_array($HttpResponse->getMessage()) || is_object($HttpResponse->getMessage())) {
        if (!isAssoc($HttpResponse->getMessage())) {
            echo json_encode(array("value" => $HttpResponse->getMessage()), JSON_NUMERIC_CHECK);
        } else {
            echo json_encode($HttpResponse->getMessage(), JSON_NUMERIC_CHECK);
        }
    } else {
        $array['message'] = $HttpResponse->getMessage();
        echo json_encode($array);
    }
}
