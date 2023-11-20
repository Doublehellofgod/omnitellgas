<?php
require_once('apiSettings.php');
require_once('apiException.php');

class APIEngine
{

    private $apiActionName;
    private $apiActionParams;

    //Статичная функция для подключения API из других API при необходимости в методах
    static function getApiEngineByName($apiName)
    {
        require_once 'apiBaseClass.php';
        require_once $apiName . '.php';
        $apiClass = new $apiName();
        return $apiClass;
    }

    //Конструктор
    //$apiFunctionName - название API и вызываемого метода в формате apitest_helloWorld
    //$apiFunctionParams - JSON параметры метода в строковом представлении
    function __construct($apiActionName, $apiActionParams)
    {
        $this->apiActionParams = $apiActionParams;
        //Парсим на массив из двух элементов [0] - название API, [1] - название метода в API
        $this->apiActionName = explode('.', $apiActionName);
    }

    private function errorMessage($message = "Извините, сервис недоступен", $httpCode = 500)
    {
        header('Content-type: application/json; charset=UTF-8');
        http_response_code($httpCode);
        $retObject = json_decode('{}');
        $retObject->error = json_decode('{}');
        $retObject->error->message = $message;
        return json_encode($retObject);
    }

    //Вызов функции по переданным параметрам в конструкторе
    function callApiFunction()
    {
        $apiName = strtolower($this->apiActionName[0]);//название API проиводим к нижнему регистру
        
        //разрешаем только POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //проверим разрешен ли подключаемый класс API в настройках
            if (in_array($apiName, APISettings::$ALLOWED_API_CLASSES)) {
                if (file_exists($apiName . '.php')) {
                    $apiClass = APIEngine::getApiEngineByName($apiName);//Получаем объект API
                    try {
                        $apiReflection = new ReflectionClass($apiName);//Через рефлексию получем информацию о классе объекта
                        $functionName = $this->apiActionName[1];//Название метода для вызова
                        $apiReflection->getMethod($functionName);//Провераем наличие метода

                        return $apiClass->$functionName($this->apiActionParams);//Вызываем метод в API

                    } catch (ApiException $ex) {
                        return $this->errorMessage($ex->getMessage(), $ex->getHttpResponseCode());
                    } catch (Exception $ex) {
                        return $this->errorMessage($ex->getMessage(), 500);
                    }

                } else {
                    //Если запрашиваемый API не найден
                    return $this->errorMessage("File with API class is not found", 500);
                }
            } else {
                return $this->errorMessage("API class $apiName is not allowed", 405);
            }
        } else {
            return $this->errorMessage("Request method should be POST", 400);
        }
        return $this->errorMessage("This should never happen", 500);
    }
}

?>
