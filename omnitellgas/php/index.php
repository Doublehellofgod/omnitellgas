<?php

/***
 * Идея подсмотрена здесь: https://habr.com/ru/post/143317/
 */
//phpinfo();

if (isset($_GET['action'])) {
    require_once 'apiEngine.php';

    $params = json_decode('{}');
    if($_SERVER["CONTENT_TYPE"] == "application/json"){
        $inputJSON = file_get_contents('php://input');
        $params = json_decode($inputJSON);
    }
    else {
        foreach ($_POST as $key => $value){
            $params->$key = $value;
        }
    }


    $APIEngine = new APIEngine($_GET['action'], $params);
    echo $APIEngine->callApiFunction();

} else {
    header('Content-type: application/json; charset=UTF-8');
    http_response_code(400);
    $jsonError->error->message = 'No action defined';
    echo json_encode($jsonError);
}
?>
