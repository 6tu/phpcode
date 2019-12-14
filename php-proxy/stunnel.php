<?php
$stunneldir ='C:/Program Files/stunnel/';
$datadir = 'f:/htdocs/data/';
$log = $datadir."dtw.log";

$stop = 'taskkill -F -IM  stunnel.exe';
exec($stop, $output, $status);
$cmd = "wget -b -P $datadir -o $log  http://yourshell.info/p/";
exec($cmd, $output, $status);

preg_match("'pid (.+).'s",$output[0],$match);
$PID = $match[1];
$i = 1;
while ($i <= 30) {
    $stat = is_running($PID);
    sleep(3);
    if($stat == 0) break;
    $i++;
    }
$dtw = file_get_contents($datadir.'index.html');
preg_match("'var autourl=new Array\(\)(.+)function auto's", $dtw, $ip);
$ip = str_replace('autourl[1]="','connect = ',$ip[1]);
$ip = preg_replace("/autourl.*?\=\"/is", ";connect = ", $ip);
$ip = str_replace("\r\n",'',$ip);
$ip = str_replace('"',":443\r\n",$ip);

$conf = file($stunneldir.'stunnel.conf');
$conf[50] = str_replace($conf[50],"$ip;$conf[50]",$conf);
$nconf = join($conf[50]);
file_put_contents($stunneldir.'stunnel.conf',$nconf);
@unlink($log);
@unlink($datadir.'index.html');

$start = $stunneldir."stunnel.exe";
exec($start, $output, $status);

function is_running($PID){
    if(PHP_OS == 'WINNT'){
       exec("tasklist |find \"$PID\"", $ProcessState);
       return(count($ProcessState) >= 1); 
       }else{
       exec("ps $PID", $ProcessState);
       return(count($ProcessState) >= 2);    
       }  
    }