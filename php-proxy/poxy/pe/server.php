<?php

function test($str) {
    //$str = '使用 Diffie-Hellman + xxtea 传输数据，如果看到了这些字符，说明代码运行正常。';
    $str = file_get_contents($_SERVER["REMOTE_ADDR"].".txt"); 
    echo $str;


    @unlink($_SERVER["REMOTE_ADDR"].".txt");
}

include('./rpc/phprpc_server.php');
$server = new PHPRPC_Server();
$server->add('test');
$server->setCharset('UTF-8');
$server->setDebugMode(true);
$server->start();


?>











