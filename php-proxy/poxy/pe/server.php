<?php

function test($str) {
    //$str = 'ʹ�� Diffie-Hellman + xxtea �������ݣ������������Щ�ַ���˵����������������';
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











