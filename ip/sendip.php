<?php
function get_onlineip() {
    $ch = curl_init('http://iframe.ip138.com/ic.asp');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $a  = curl_exec($ch);
    preg_match('/\[(.*)\]/', $a, $ip);
    return @$ip[1];
 }

$ip = get_onlineip();
$oldip = file_get_contents('D:\xampp\htdocs\ip.txt');
if($oldip == $ip){
exit();     //echo 'done';
}else{
file_put_contents('D:\xampp\htdocs\ip.txt',$ip);
$headers = 'From: sf <safeboat@sina.com>';
mail("395636344@qq.com","My IP",$ip,$headers);
}
?>