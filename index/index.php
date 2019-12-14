<?php
$ip_china = './china-ip.json';
$ip_remote = $_SERVER["REMOTE_ADDR"];

if(check_is_china_ip($ip_remote, $ip_china)) header('Location: index_cn.html');
else header('Location: index.html');

function check_is_china_ip($ip, $ipjson){
    $ip_addr = explode('.', $ip);
    if(count($ip_addr) < 4) return false;
    $a1 = (int)$ip_addr[0];
    $a2 = (int)$ip_addr[1];
    $a3 = (int)$ip_addr[2];
    $a4 = (int)$ip_addr[3];
    $s_china = file_get_contents($ipjson);
    $tb_china = json_decode($s_china, 1);
    unset($s_china);
    if(!isset($tb_china[$a1][$a2]) || count($tb_china[$a1][$a2]) == 0) return false;
    $a = $a3 * 256 + $a4;
    foreach($tb_china[$a1][$a2] as $d){
        if($a >= $d['s'] && $a <= $d['e']){
            return true;
        }
    }
    return false;
}

