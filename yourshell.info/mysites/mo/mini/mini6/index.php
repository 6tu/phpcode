<?php
date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=gbk');
// 截取文件名 $self = $_SERVER['PHP_SELF']; $pos = strrpos($self,'/',0) + 1; $self = substr($self,$pos,strlen($self));
$self = $_SERVER['SCRIPT_FILENAME'];
echo ' 建立时间: ' . date("Y F(N) j H:i:s", filectime($self)) . "\n<br />" ;
echo ' 当前时间: ' . date("Y F(N) j H:i:s", time()) . "\n<br />" ;
//$d = floor((time() - filectime($self))/(3600*24));
$h = floor((time() - filectime($self))/3600);
$i = floor(((time() - filectime($self)) - floor((time() - filectime($self))/3600)*3600)/60);
$s = (time() - filectime($self)) - floor((time() - filectime($self))/60)*60;

echo ' 存在时长: ' . $h . " 小时 " . $i . " 分钟 " . $s . "秒" ;

?>