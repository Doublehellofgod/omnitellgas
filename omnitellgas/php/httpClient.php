<?php
/**
 * Created by PhpStorm.
 * User: yuris
 * Date: 16.01.2019
 * Time: 14:53
 */

require_once('apiSettings.php');

class HttpClient
{

    private static function initCurl()
    {
        $ch = curl_init();
        $options = array(CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => true
        );
        curl_setopt_array($ch, $options);
        return $ch;
    }

    public static function OktellExecSvcScriptPlain($name, $startparam1 = "", $startparam2 = "", $startparam3 = "", $startparam4 = "", $startparam5 = "", $async = 0, $timeout = -1)
    {
        if ($timeout == -1) {
            $timeout = APISettings::$OKTELL_CLIENT_TIMEOUT;
        }

        $ch = self::initCurl();

        curl_setopt($ch, CURLOPT_TIMEOUT, APISettings::$OKTELL_CLIENT_TIMEOUT + 5);
        curl_setopt($ch, CURLOPT_PORT, APISettings::$OKTELL_CLIENT_PORT);
        curl_setopt($ch, CURLOPT_USERPWD, APISettings::$OKTELL_CLIENT_LOGIN . ":" . APISettings::$OKTELL_CLIENT_PASSWORD);
        curl_setopt($ch, CURLOPT_URL,
            APISettings::$OKTELL_CLIENT_PROTO . "://" . APISettings::$OKTELL_CLIENT_HOST
            . "/execsvcscriptplain?name=" . $name
            . "&startparam1=" . $startparam1
            . "&startparam2=" . $startparam2
            . "&startparam3=" . $startparam3
            . "&startparam4=" . $startparam4
            . "&startparam5=" . $startparam5
            . "&async=" . $async
            . "&timeout=" . $timeout);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $returnObj = new stdClass();
        $returnObj->response = $response;
        $returnObj->httpCode = $httpCode;
        return $returnObj;
    }
    public static function OktellRemote($name, $startparam1 = "", $startparam2 = "", $startparam3 = "", $startparam4 = "")
    {
        if ($timeout == -1) {
            $timeout = APISettings::$OKTELL_CLIENT_TIMEOUT;
        }

        $ch = self::initCurl();

        curl_setopt($ch, CURLOPT_TIMEOUT, APISettings::$OKTELL_CLIENT_TIMEOUT + 5);
        curl_setopt($ch, CURLOPT_PORT, APISettings::$OKTELL_CLIENT_PORT);
        curl_setopt($ch, CURLOPT_USERPWD, APISettings::$OKTELL_CLIENT_LOGIN . ":" . APISettings::$OKTELL_CLIENT_PASSWORD);
        curl_setopt($ch, CURLOPT_URL,
            APISettings::$OKTELL_CLIENT_PROTO . "://" . APISettings::$OKTELL_CLIENT_HOST
            . "/$name?$startparam1=" . $startparam2
            . "&$startparam3=" . $startparam4);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);


        $returnObj = new stdClass();
        $returnObj->response = $response;
        $returnObj->httpCode = $httpCode;
        return $returnObj;
    }

}
