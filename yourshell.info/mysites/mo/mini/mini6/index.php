<?php
date_default_timezone_set('Asia/Shanghai');
header('Content-Type: text/html; charset=gbk');
// ��ȡ�ļ��� $self = $_SERVER['PHP_SELF']; $pos = strrpos($self,'/',0) + 1; $self = substr($self,$pos,strlen($self));
$self = $_SERVER['SCRIPT_FILENAME'];
echo ' ����ʱ��: ' . date("Y F(N) j H:i:s", filectime($self)) . "\n<br />" ;
echo ' ��ǰʱ��: ' . date("Y F(N) j H:i:s", time()) . "\n<br />" ;
//$d = floor((time() - filectime($self))/(3600*24));
$h = floor((time() - filectime($self))/3600);
$i = floor(((time() - filectime($self)) - floor((time() - filectime($self))/3600)*3600)/60);
$s = (time() - filectime($self)) - floor((time() - filectime($self))/60)*60;

echo ' ����ʱ��: ' . $h . " Сʱ " . $i . " ���� " . $s . "��" ;

?>