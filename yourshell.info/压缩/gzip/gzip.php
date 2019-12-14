<?php
if(isset($_SERVER['HTTP_ACCEPT_ENCODING']) && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')){
ob_start('ob_gzip');
}else{
ob_start();
}

function ob_gzip($_response_body){
        header('Content-Encoding: deflate');    
        header("Vary: Accept-Encoding");    
        header("Content-Length: ".strlen($_response_body)); 
        $_response_body = gzdeflate($_response_body, 9);
        return $_response_body;
}
?>