<?php
#ע���ٷ����أ�http://opm-server-mirror.googlecode.com/files/opm_php.zip������ֻ������һЩ�޸ģ��������ݸ��á�
#���Ҫαװ��������һ����ת�����������ļ����·���һ���ļ���Ϊ"hide.txt"��

$h["host"]='server4.operamini.com';
#Զ�̷�����

$h["accept"]=$_SERVER["HTTP_ACCEPT"];
$h["connection"]=$_SERVER["HTTP_CONNECTION"];
$h["ua"]=$_SERVER['HTTP_USER_AGENT'];
#������Զ�̷��������͵�Header���˴��������û��˷��͵�����ֱ�Ӵ��ݡ�

if($_SERVER['REQUEST_METHOD']=='GET'){
if(file_exists("hide.txt")){
header("HTTP/1.1 301 Moved Permanently");
header("Location: http://translate.google.cn/");
exit;
}
$available=False;
echo "<html><body>cURL: ";
if(function_exists("curl_init")){$available=True;echo "Enabled";}else{echo "Disabled";}
echo "<br/>Stream_context_create: ";
if(function_exists("stream_context_create")){$available=True;echo "Enabled";}else{echo "Disabled";}
echo "<br/>Fsockopen: ";
if(function_exists("fsockopen")){$available=True;echo "Enabled";}else{echo "Disabled";}
echo "<br/>Allow_url_fopen: ";
if(ini_get("allow_url_fopen")=="1"){echo "Yes";}else{if(!function_exists("curl_init")){$available=False;}echo "No";}
echo "<br/>*File_get_contents: ";
if(function_exists("file_get_contents")){echo "Enabled";}else{$available=False;echo "Disabled";}
if($available==False){echo "<hr/>Sorry! Server Inaccessible.</html>";}else{echo "<hr/>Congratulation! Server Accessible.</html>";}
}else{
error_reporting(0);
if(function_exists("curl_init")){

#curl��ʽ��ʼ
$curlInterface=curl_init();
$headers[]='Connection: '.$h["connection"];
$headers[]='Accept: '.$h["accept"];
$headers[]='User-Agent: '.$h["ua"];
curl_setopt_array($curlInterface,array(CURLOPT_POST=>1,CURLOPT_URL=>"http://".$h["host"],CURLOPT_POSTFIELDS=>file_get_contents('php://input'),CURLOPT_HTTPHEADER=>$headers));
header('Content-Type: application/octet-stream');
header('Cache-Control: private, no-cache');
curl_exec($curlInterface);
curl_close($curlInterface);
#curl��ʽ����

}elseif(function_exists("fsockopen") and ini_get("allow_url_fopen")=="1"){

#fsockopen��ʽ��ʼ
$fp=fsockopen($h["host"],"80",$e["n"],$e["s"],60);
$h["post"]=file_get_contents("php://input");
fputs($fp,"POST / HTTP/1.1\r\nHost: ".$h["host"]."\r\nAccept: ".$h["accept"]."\r\nContent-length: ".strlen($h["post"])."\r\nUser-Agent: ".$h["ua"]."\r\nConnection: ".$h["connection"]."\r\n\r\n".$h["post"]."\r\n\r\n");
$body_start==False;
while(!feof($fp)){
$str=fgets($fp);
if($body_start==False and ($str=="\n" or $str=="\r\n")){$body_start=True;}elseif($body_start==False){header(str_replace("\n","",str_replace("\r","",$str)));}else{echo $str;}
}
#fsockopen��ʽ����

}elseif(function_exists("stream_context_create") and ini_get("allow_url_fopen")=="1"){

#file_get_contents��ʽ��ʼ
$data="Connection: ".$h["connection"]."\r\nAccept: ".$h["accept"]."\r\nUser-Agent: ".$h["ua"];
$con["http"]=array("timeout"=>60,"method"=>"POST","header"=>$data,"content"=>file_get_contents('php://input'));
header('Content-Type: application/octet-stream');
header('Cache-Control: private, no-cache');
echo file_get_contents("http://".$h["host"],FALSE,stream_context_create($con));
}
#file_get_contents��ʽ����

}
exit;