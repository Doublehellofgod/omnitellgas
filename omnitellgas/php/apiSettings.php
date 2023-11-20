<?php
class APISettings {

    //разрешенные файлы (классы) с API
    public static  $ALLOWED_API_CLASSES = array('general');

    public static $OKTELL_CLIENT_TIMEOUT = 30;
    public static $OKTELL_CLIENT_PROTO = 'https';
    public static $OKTELL_CLIENT_HOST = 'ok.omnitell.ru';
    public static $OKTELL_CLIENT_PORT = '443';
    public static $OKTELL_CLIENT_LOGIN = 'webapi';
    public static $OKTELL_CLIENT_PASSWORD = '!Qaz@Wsx12';

    public static $RECAPTCHA_SECRET_KEY = '';
    
    public static $OKTELL_API_NAME='webapi_gas';
}
?>
