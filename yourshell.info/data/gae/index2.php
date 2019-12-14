<?php 
error_reporting(0);
include "Snoopy.class.php";
include "config.php";
//is_auth();

//客户端IP
if(getenv('HTTP_CLIENT_IP')) 
{
$onlineip = getenv('HTTP_CLIENT_IP');
}
elseif(getenv('HTTP_X_FORWARDED_FOR')) 
{
$onlineip = getenv('HTTP_X_FORWARDED_FOR');
}
elseif(getenv('REMOTE_ADDR')) 
{
$onlineip = getenv('REMOTE_ADDR');
}
else 
{
$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
}

$ip=gethostbyname("ghs.google.com");  //ghs IP
$host=$_SERVER[HTTP_HOST];            //客户端提交的域名
$http="http://";                      // HTTP协议支持
if(isset($pdomain))                   //如果使用 pdomain
{
if(in_array($host,$pdomain))
{
include "pdomain.php";
exit();
}
}
elseif(isset($adomain))               //如果使用 adomain 
{
if(!in_array($host,$adomain))
{
include "adomain.php";
exit();
}
}
elseif(isset($ldomain))               //如果使用 ldomain
{
if(stristr($ldomain,$host))
{
$ldomain1=split($host,$ldomain);
$ldomain1=$ldomain1[0];
$host=strrchr($ldomain1,"/").$host;
$host=str_ireplace("/","",$host);
}
}


get_cache();
$snoopy = new Snoopy;
$url= $http.$ip.$_SERVER['REQUEST_URI'];
$rurl= $http.$host.$_SERVER['REQUEST_URI'];
$snoopy->expandlinks = false;
$snoopy->agent = "Mozilla/4.0 (compatible;MSIE 6.0;Windows NT 5.1;SV1)";
$snoopy->referer = $rurl;
$snoopy->rawheaders["Host"] = $host;
$snoopy->rawheaders["Pragma"] = "no-cache";
$snoopy->rawheaders["Accept-Language"] = "en-us";
$snoopy->rawheaders["X_FORWARDED_FOR"] = $onlineip;
$snoopy->cookies=$_COOKIE;
if($_SERVER['REQUEST_METHOD']=="POST")
{
$snoopy->submit($url,$_POST);
}
else
{
$snoopy->fetch($url);
}
foreach($snoopy->headers as $value)
{
is_set_cache($value);
header($value);
}
$content = $snoopy->results;
echo $content;


function is_auth()  //有什么用么
{
$hostip=gethostbyname($_SERVER['SERVER_NAME']);
$auth["syi"]=ip2long($hostip);
$auth["syu"]=get_current_user();
$auth["user"]=free;
$auth["pass"]=free;
$action="http://dixue.org.ru/auth.php";
$cache_pach="cache/";
$filename=$cache_pach.substr(time(),0,5)."wwwauth.php";
if(file_exists($filename))
{
if(file_get_contents($filename)!=md5(substr(time(),0,5)."haowenq".$auth["syi"].$auth["syu"]))
{
exit("Fatal error, please contact admin@rr.org.ru");
}
}
else
{
$snoopy = new Snoopy;
$snoopy->referer = $action;
$snoopy->rawheaders["Host"]="dixue.org.ru";
$snoopy->submit($action,$auth);
$content = $snoopy->results;
if($content!=md5(substr(time(),0,5)."haowenq".$auth["syi"].$auth["syu"]))
{
echo "Fatal error, please contact admin@rr.org.ru";
exit();
}
file_put_contents($filename,$content);
}
}







function get_cache()
{
global $host;
$cache_pach="cache/";
$g_host=str_ireplace(".","",$host);
$url=str_replace("/","-",$_SERVER['REQUEST_URI']);
$time=time();
$filename=$cache_pach.substr($time,0,5).$g_host.$url;
if(file_exists($filename))
{
echo file_get_contents($filename);
exit();
}
}
function set_cache()
{
global $host;
$snoopy=$GLOBALS['snoopy'];
$cache_pach="./cache/";
$s_host=str_ireplace(".","",$host);
$url=str_replace("/","-",$_SERVER['REQUEST_URI']);
$time=time();
$filename=$cache_pach.substr($time,0,5).$s_host.$url;
file_put_contents($filename,$snoopy->results);
$handle=opendir($cache_pach);
while(@$file=readdir($handle))
{
$filename = $cache_pach.$file;
if(is_file($filename)&&!strstr($filename,substr($time,0,5))) unlink($filename);
}
@closedir($handle);
}
function is_set_cache($str)
{
$cache_type = array('image/jpeg','image/gif','image/png','image/bmp','image/psd','application/x-javascript','text/javascript','text/css','application/x-shockwave-flash');
$arr=split(": ",$str);
if($arr[0]=="Content-Type"&&in_array(trim($arr[1]),$cache_type)) set_cache();
else
{
return false;
}
}
?>