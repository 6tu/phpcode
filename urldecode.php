<?php
$str = "strongswan-ikev2服务器配置";
echo urlencode($str);
echo "<br>\r\n"; 

$str = 'strongswan-ikev2?%9C%8D?%8A??%99??%85%8D置';
$str2 = urldecode($str);
echo $str2;
echo "<br>\r\n"; 
$str = mb_convert_encoding($str, "UTF-8", "gb2312");
echo $str;



?>
