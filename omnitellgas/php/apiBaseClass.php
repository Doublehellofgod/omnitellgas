<?php
require_once('apiException.php');

class apiBaseClass {

    //Конструктор с возможными параметрами
    function __construct() {
    }

    function __destruct() {
    }

    //Создаем дефолтный JSON для ответов
    function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

}

?>
