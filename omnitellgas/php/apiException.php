<?php
/**
 * Created by PhpStorm.
 * User: yuris
 * Date: 16.01.2019
 * Time: 13:02
 */
class ApiException extends Exception {

    protected $httpResponseCode;

    public function __construct($message, $httpResponseCode = 400) {

        $this->httpResponseCode = $httpResponseCode == 0? 500 : $httpResponseCode;
        parent::__construct($message);
    }

    public function getHttpResponseCode(){
        return $this->httpResponseCode;
    }
}
