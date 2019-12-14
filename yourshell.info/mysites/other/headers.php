<?php
if(!isset($_GET['url'])){
print<<<JS
<script type="text/javascript" src="pe/js/encode.js"></script><script language="javascript" >
function autojs(){
document.form1.url.value=str2hex(window.btoa(document.form1.url.value)); 
document.form.submit();
}</script>
JS;
$html = '<br /><br /><br /><center><form name="form1" method="get" action="./headers.php" onsubmit="autojs();" ><input name="url" type="text"  size="60" style="width:460px">'
      . '<input type="submit" value="提 交"></form></center>';
echo $html;
exit;
}else{

$url = base64_decode(hex2str($_GET['url']));
$url_sub = @parse_url($url);
if(!isset($url_sub['scheme'])) $url = 'http://'. $url;
$headers = getpage($url,$referer='',$header=1);
echo '<br /><br /><br /><pre>' .$headers[0]. '</pre>';
echo '<a href="./headers.php">返回</a>';
}

function str2hex($s)
{
    $r = "";
    $hexes = array ("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");
    for ($i = 0;$i < strlen($s);$i++)
    $r .= ($hexes [(ord($s{$i}) >> 4)] . $hexes [(ord($s{$i}) & 0xf)]);
    return $r;
    }

function hex2str($s)
{
    $r = "";
    for ($i = 0;$i < strlen($s);$i += 2)
    {
        $x1 = ord($s{$i});
        $x1 = ($x1 >= 48 && $x1 < 58) ? $x1-48 : $x1-97 + 10;
        $x2 = ord($s{$i+1});
        $x2 = ($x2 >= 48 && $x2 < 58) ? $x2-48 : $x2-97 + 10;
        $r .= chr((($x1 << 4) & 0xf0) | ($x2 & 0x0f));
        }
    return $r;
    }

function getpage($url,$referer='',$header=0){

    if(!$_SERVER['HTTP_ACCEPT_LANGUAGE']){
        $lang = 'en';
        }else{
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

    $url = @parse_url($url);
    if(isset($url['query'])) $url_get = $url['path'] . '?' . $url['query'];
    elseif(isset($url['path'])) $url_get = $url['path'] ;
    else $url_get = '/';
    if (empty($referer)) $referer = $url['host'];   
    $temp = '';
    
    if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
        $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
        if (!$fp){
	    $http_code = '404';
            return array('false','');
            }else{
            $out = "GET $url_get HTTP/1.0\r\n";
            $out .= "Host: $url[host]\r\n";
            $out .= "Accept-Language: $lang\r\n";
            $out .= "Referer: $referer \r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            while (!feof($fp)){
                $temp .= fgets($fp, 128);
                if($header == 1 && strstr($temp,"\r\n\r\n")) break;
                }
            fclose($fp);
            $http_code = substr($temp, 9, 3);
			
            $temp = explode("\r\n\r\n", $temp, 2);
            $temp = array($temp[0],$temp[1]);
            }
        }elseif(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $referer); 
	if($header == 1) curl_setopt($ch, CURLOPT_NOBODY, 1);  
        $temp = curl_exec ($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
	$temp = explode("\r\n\r\n", $temp, 2);
        $temp = array($temp[0],$temp[1]);
        }else{
        $http_code = '0';
        $temp = array('header: No','the hosting do not support curl or fsockopen()');
        }
    if ($http_code >= 400){ // 400 - 600都是服务器错误
        return array('false','');
        // exit(0);
    }else{
        return $temp;
        }
    }