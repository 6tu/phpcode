<?php


$URL='http://cmdedpublicssh.appspot.com/sign';
$post_data['content'] = "tor@spam.la";
$post_data['submit'] = "ok";
$referrer="";
// parsing the given URL
$URL_Info=parse_url($URL);
// Building referrer
if($referrer=="") // if not given use this script as referrer
$referrer=$_SERVER["SCRIPT_URI"];
 
// making string from $data
foreach($post_data as $key=>$value)
$values[]="$key=".urlencode($value);
 
$data_string=implode("&",$values);
// Find out which port is needed - if not given use standard (=80)
if(!isset($URL_Info["port"]))
$URL_Info["port"]=80;
// building POST-request:
$request.="POST ".$URL_Info["path"]." HTTP/1.1\n";
$request.="Host: ".$URL_Info["host"]."\n";
$request.="Referer: $referrer\n";
$request.="Content-type: application/x-www-form-urlencoded\n";
$request.="Content-length: ".strlen($data_string)."\n";
$request.="Connection: close\n";
$request.="\n";
$request.=$data_string."\n";
$fp = fsockopen($URL_Info["host"],$URL_Info["port"]);
fputs($fp, $request);
while(!feof($fp)) {
    $result .= fgets($fp, 128);
}
fclose($fp);

if (strstr($result,'All done! Please check your mailbox.')){
$urlmail = 'http://spam.la/?f=tor';

echo file_get_contents($urlmail);

}

















