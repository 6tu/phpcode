<?php

$home = '/opt/lampp/htdocs';
$url = 'http://www.baidu.com';
$tmp = '/opt/lampp/htdocs/tmp';
$rnd = md5(time());

$xvfb = 'xvfb-run --server-args="-screen 0, 1200x5050x24 "';
$topng = " $home/cutycapt --url=$url --out=$tmp/$rnd.png";
$cmd = $xvfb.$topng;
/*
//exec($cmd,$out,$ret);

//echo '<pre>';
//print_r($out);
//echo $ret.'<br>';

if($ret==0){
echo '<img src=./tmp/'.$rnd.'.png>';
}else{
echo 'false';
}
*/

$xvfb = 'xvfb-run --server-args="-screen 0, 1200x5050x24"';

// "/usr/bin/sudo -u root $path2mkpicture/xvfb.sh .........., 

shell_exec ("/bin/sh $home/xvfb.sh \"$home/cutycapt\" \"$tmp\" \"$rnd\" \"$url\" \"$xvfb\"")








?>
