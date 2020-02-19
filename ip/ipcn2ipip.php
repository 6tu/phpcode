<?php

set_time_limit(0);
error_reporting(7);
ini_set('memory_limit', '2G');

function __autoload($class){
    if(strpos($class, 'ipip\db') !== FALSE){
        require_once __DIR__ . 'ipdb/vendor/ipip/db/src/' . $class . '.php';
    }
}

$local = '';
$db = 'cmcc';
if(empty($db)) $db = 'cu';
if(empty($local)) $local = '宁夏';
if($db == 'cu'){
    $ipfn = 'e:\ipcn\20201219\isproutes-cu.txt';
    $newdb =  'e:\ipcn\isproutes-cu_cityinfo.txt';
    $localdb = 'e:\ipcn\cu_local.txt';
}
if($db == 'ct'){
    $ipfn = 'e:\ipcn\20201219\isproutes-ct.txt';
    $newdb =  'e:\ipcn\isproutes-ct_cityinfo.txt';
    $localdb = 'e:\ipcn\ct_local.txt';
}
if($db == 'cmcc'){
    $ipfn = 'e:\ipcn\20201219\isproutes-cmcc.txt';
    $newdb =  'e:\ipcn\isproutes-cmcc_cityinfo.txt';
    $localdb = 'e:\ipcn\cmcc_local.txt';
}
if($db == 'edu'){
    $ipfn = 'e:\ipcn\20201219\isproutes-edu.txt';
    $newdb =  'e:\ipcn\isproutes-edu_cityinfo.txt';
    $localdb = 'e:\ipcn\edu_local.txt';
}

$ipcn = file_get_contents($ipfn);
// $ipcn = "1.2.8.0/24\r\n1.24.0.0/13\r\n";
$ipcn = str_replace("\r", '', $ipcn);
$ipcn = str_replace("\n", "\r\n", $ipcn);
$ipcn_array = explode("\r\n", $ipcn);

$ip_info = '';
$local_info = '';
foreach($ipcn_array as $ip){
    if(empty($ip)) continue;
    if(strpos($ip, '.') == FALSE) continue;
    $ip_arr = explode('/', $ip);
    if($ip_arr[1] < 9){
        echo $ip . "\r\n";
        continue;
    }

    $p = 24 - $ip_arr[1];
    $c = pow(2, $p);
    if($ip_arr[1] >= 24) $c = 1;

    $ip_ex = explode('.', $ip_arr[0]);

    for($i = 0;$i < $c;$i++){
		//echo $i . "\r\n";
        $ip_c = $ip_ex[2] + $i;
        if($ip_c < 256){
            $newip = $ip_ex[0] . '.' . $ip_ex[1] . '.' . $ip_c . '.' . $ip_ex[3];
        }else{
            $ip_b = $ip_ex[1] + intval(($i + $ip_ex[2]) / 256);;
            $ip_c = ($i + $ip_ex[2]) % 256;
            $newip = $ip_ex[0] . '.' . $ip_b . '.' . $ip_c . '.' . $ip_ex[3];
        }

        $city = new ipip\db\City('e:\ipdb\ipipfree.ipdb');
        $cityinfo = $city -> find($newip, 'CN');
        $ip_info .= $newip . '/24' . "\t" . $cityinfo[1] . "\t" . $cityinfo[2] . "\r\n";
        if($cityinfo[1] == $local){
            $local_info .= $newip . '/24' . "\t" . $cityinfo[1] . "\t" . $cityinfo[2] . "\r\n";
        }
    }
}
file_put_contents($newdb, $ip_info);
file_put_contents($localdb, $local_info);

