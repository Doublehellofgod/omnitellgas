<?php

/**
 * Created by PhpStorm.
 * User: yuris
 * Date: 16.01.2019
 * Time: 14:26
 */
function transfer($st)
{
    $inc_state=array();
    $inc_state['1']='Успешное переключение на ';
    $inc_state['2']='Неспешное переключение на ';
    $inc_state['3']='Принята информация для ';
    return $inc_state[$st];
}
require_once("httpClient.php");
require_once('apiSettings.php');
class general extends apiBaseClass
{
    function send($params)
    {
        //error_log(print_r($_SERVER['HTTP_X_XSRF_TOKEN'], true));
        session_start();
            //$message=$_GET['message'];
            $message= isset($params->message) ? $params->message : '';
        //проверяем есть ли в заголовках запроса правильный CSRF токен.
        if (isset($_SERVER['HTTP_X_XSRF_TOKEN']) && isset($_SESSION['csrf_token']) && $_SERVER['HTTP_X_XSRF_TOKEN'] == $_SESSION['csrf_token']) {
            date_default_timezone_set("Europe/Moscow");
            $time=date("d F Y")." ".date("H:i:s");
            $phone= isset($params->phone) ? $params->phone : '';
            $fio=isset($params->fio) ? $params->fio : '';
            $org=isset($params->org) ? $params->org : '';
            $themecall=isset($params->themecall) ? $params->themecall : '';
            $comment=isset($params->comment) ? $params->comment : '';

            $callerid=isset($params->callerid) ? $params->callerid : '';
            $calledid=isset($params->calledid ) ? $params->calledid :'';
            $operator=isset($params->operator ) ? $params->operator : '';
            $inc_state=isset($params->inc_state) ? $params->inc_state : '';
           
            $name=isset($params->name) ? $params->name : '';
            $idinlist=isset($params->idinlist) ? $params->idinlist : '';

            $number=isset($params->number) ? $params->number : '';
            $email=isset($params->email) ? $params->email : '';

            $text="Добрый день. Поступил звонок в ВС Omnitell с номера: $callerid; \r\n"
            ."На номер: $calledid\r\n"
            ."ФИО обратившегося: $fio;\r\n"
            ."Организация обратившегося: $org;\r\n"
            ."Тема звонка: $themecall; \r\n"
            ."Комменатрий оператора: $comment;\r\n"
            ."Контактный номер: $phone \r\n"
            ."".transfer($inc_state)."$name; \r\n"
            ."Оператор: $operator; \r\n"
            ."Дата/время: $time.; \r\n";

            
            $default_email = "popov.vs@omnitell.ru";

            if($_SERVER['HTTP_HOST']=='localhost:4200')
            {
                require_once '../../htdocs/phpMailer/PHPMailerAutoload.php';
                $email='zhadan.sv@omnitell.ru';
            }
            
            else
            {
                require_once '../../../classes/phpMailer/PHPMailerAutoload.php';

            }
            $m = new PHPMailer();
            $m->isSendmail();
             $m->Timeout = 10;
                    $m->isSMTP();  
                    // Set mailer to use SMTP
                    $m->Host = 'smtp.yandex.ru';  // Specify main and backup SMTP servers
                    $m->SMTPAuth = true;                               // Enable SMTP authentication
                    $m->Username = 'oktell_info@omnitell.ru';                 // SMTP username
                    $m->Password = 'M6s7aQK';                           // SMTP password
                    $m->SMTPSecure = 'ssl';         
                    $m->Port = 465;                                    // TCP port to connect to
            $m->CharSet = 'utf-8';
            $m->From = 'oktell_info@omnitell.ru';
            $m->FromName = 'Omnitell';
            $m->addAddress($email);
            $m->isHTML(false);
            $m->Subject = "Поступил звонок в ВС Omnitell с номера: $phone";
            $m->Body = $text;

            if ($m->send()) {
                $message="Сообщение специалисту успешно отправлено!";
                HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec',$idinlist,urlencode($message));
                $data = array("result"=> $message);
                // отдаем обратно объект
                print json_encode($data);
                }
            else{
                $message='Ошибка при попытке отправки сообщения!';
                HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec',$idinlist,urlencode($message));
                $data = array("result"=> $message);
                print json_encode($data);
                }

        } 
        else {
            throw new ApiException('Не авторизован', 403);
            }
    }

    function check($params)
    {
        session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = hash("sha1", rand() . time() . rand());
        }
        setcookie('XSRF-TOKEN', $_SESSION['csrf_token'], null, '/', null, null, false);

        header('Content-type: application/json; charset=UTF-8');
        $data = array("result"=> "success");
                print json_encode($data);
    }

    function remote_switchcall($params)
    {
        session_start();
        if (isset($_SERVER['HTTP_X_XSRF_TOKEN']) && isset($_SESSION['csrf_token']) && $_SERVER['HTTP_X_XSRF_TOKEN'] == $_SESSION['csrf_token'])
        {
            $user=isset($params->user) ? $params->user : '';
            $number=isset($params->number) ? $params->number : '';
            $idinlist=isset($params->idinlist) ? $params->idinlist : '';
            $data = HttpClient::OktellRemote("wp_switchcall","number",$number,"user",$user);  
            
            if ($data->httpCode != 500) {
                HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec',$idinlist,urlencode("Перевод звонка"));
                $datares = array("result"=> $data->httpCode);
                print json_encode($datares);
            }
            else{
             throw new ApiException($data->response, $data->httpCode);
            }
        }
        else{
            throw new ApiException('Не авторизован', 403);
        }
    }

    function remote_flash($params)
    {
        session_start();
        if (isset($_SERVER['HTTP_X_XSRF_TOKEN']) && isset($_SESSION['csrf_token']) && $_SERVER['HTTP_X_XSRF_TOKEN'] == $_SESSION['csrf_token'])
        {
            $user=isset($params->user) ? $params->user : '';
            $idinlist=isset($params->idinlist) ? $params->idinlist : '';
            $data = HttpClient::OktellRemote("wp_flash","user",$user,"mode","abort");
            if ($data->httpCode != 500) {
                HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec',$idinlist,urlencode("Звонок вернулся"));
                $datares = array("result"=> $data->httpCode);
                print json_encode($datares);
            }
            else{
             throw new ApiException($data->response, $data->httpCode);
            }
        }
        else{
            throw new ApiException('Не авторизован', 403);
        }    

    }
    function sendterm($params)
    {
        session_start();
        if (isset($_SERVER['HTTP_X_XSRF_TOKEN']) && isset($_SESSION['csrf_token']) && $_SERVER['HTTP_X_XSRF_TOKEN'] == $_SESSION['csrf_token']) {
            $text=isset($params->text) ? $params->text : '';
            $idinlist=isset($params->idinlist) ? $params->idinlist : '';
            $code = HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec',$idinlist,urlencode("Аварийно ".$text));
            if ($code->httpCode == 200) {
                $message = "Комментарий добавлен!";
                $data = array("status"=>"success","result"=> $message);
                // отдаем обратно объект
                print json_encode($data);
            }
            else{
                $message = "Ошибка при попытке добавить комментарий!";
                $data = array("status"=>"error","error"=> $message);
                // отдаем обратно объект
                print json_encode($data);
            }
            
        }else{
            throw new ApiException('Не авторизован', 403);
        }  
    }

}