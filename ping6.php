<?php



$site = '2605:fb80:f000:f6b::2';
$log = './ping.log';
$cmd = "ping6 -c 1 $site";



echo "\r\n  测试 IPv6 网络是否可用 \r\n";
system($cmd);
file_put_contents($log,  "\r\n\r\n");

for($i=0; $i < 600; $i++){
    sleep(2);
    system("$cmd > $log");
    $str = file_get_contents($log);
    if (false == strpos($str, 'unreachable')){
        $et = date("y-m-d h:i:s",time());
        file_put_contents($log, "\r\n\r\n" . $et . "\r\n", FILE_APPEND);
        break;
    }
}

echo "\r\n\r\n    IPv6 网络可用 \r\n";

$st = date("y-m-d h:i:s",time());

for($i=0; $i < 600; $i++){
    sleep(3);
    system("$cmd > $log");
    $str = file_get_contents($log);
    if (false !== strpos($str, 'unreachable')){
        $et = date("y-m-d h:i:s",time());
        file_put_contents($log, "\r\n\r\n" . $et . "\r\n", FILE_APPEND);
        break;
    }
}
$et = date("y-m-d h:i:s",time());
$t = strtotime($et) - strtotime($st);
$str = "\r\n\r\n  " . $t/60 . ' 分钟之后，请求超时' . "\r\n\r\n";
echo $str;
file_put_contents($log, $str);

?>
