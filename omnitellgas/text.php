<?php
require_once("php/httpClient.php");
require_once('php/apiSettings.php');
//define("OKTELL_WEBAPI_URL","http://webapi:!Qaz@Wsx12@ok.omnitell.ru/execsvcscriptplain?name=webapi_gas&async=0&timeout=10&startparam1=update_rec");
// echo "Hellow";
// echo $_GET['text']
// define("OKTELL_WEBAPI_URL","http://webapi:!Qaz@Wsx12@ok.omnitell.ru/execsvcscriptplain?name=webapi_gas&async=0&timeout=10&startparam1=update_rec");
// $arrContextOptions=array(
//     "ssl"=>array(
//         "verify_peer"=>false,
//         "verify_peer_name"=>false,
//     ),
// );   

// file_get_contents(OKTELL_WEBAPI_URL, false, stream_context_create($arrContextOptions));
// echo "hellow";
HttpClient::OktellExecSvcScriptPlain(APISettings::$OKTELL_API_NAME,'update_rec','$idinlist','urlencode($message)');
?>