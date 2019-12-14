<?php
header("Content-type: text/html; charset=GBK");
$new_version = '0.62';
$Update = 'Update: 20110531';

if(!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
    $lang = 'en';
    }else{
    preg_match('/^([a-z\-]+)/i', @$_SERVER["HTTP_ACCEPT_LANGUAGE"], $matches);
    $lang = $matches[1];
    }
date_default_timezone_set('Asia/Shanghai');
$time = date('Ymd His');

    $cinfo = 'DATA: ' . $time . "\r\n" . 'REMOTE_ADDR: ' . @$_SERVER['REMOTE_ADDR'] . "\r\n" . 'REFERER: ' . @$_SERVER['HTTP_REFERER'] . "\r\n" . 'LANGUAGE: ' . $lang . 'USER_AGENT: ' . @$_SERVER['HTTP_USER_AGENT'] . "\r\n\r\n";
if(!isset($_GET['peurl'])){
    $surl = 'http://REFERER version=0.61';
    }else{
    $surl = base64_decode($_GET['peurl']);
    }

if(strstr($lang,'zh')){
$out = '<br />检测到有 <a href="http://eproxy.sf.net"> 新版本</a>(' . $Update . ') ，欢迎试用';
}else{
$out = '<br />welcome to use <a href="http://eproxy.sf.net"> New Version</a>(' . $Update . ') ,please';
}

$version = explode('version=', $surl);
if($new_version > $version[1]){
    echo utf8togbk(utf2html(gbktoutf8($out)));
    }
$clintinfo = 'cinfo.log';
$info = @file_get_contents($clintinfo);
if(strstr($info, $surl) == false){
    $info = $cinfo . $surl . "\r\n***************\r\n" . $info;
    file_put_contents($clintinfo, $info);
    }

function utf8togbk($str){
    if(extension_loaded('mbstring')){
        $str = mb_convert_encoding($str, 'GBK', 'UTF-8');
        }else if(extension_loaded('iconv')){
        $str = iconv('UTF-8', 'GBK//IGNORE//TRANSLIT', $str);
        }
	return $str;
    }

function gbktoutf8($str){
    if(extension_loaded('mbstring')){
        $str = mb_convert_encoding($str, 'UTF-8', 'GBK');
        }else if(extension_loaded('iconv')){
        $str = iconv('GBK', 'UTF-8//IGNORE//TRANSLIT', $str);
        }
	return $str;
    }
function utf2html($str){ // UTF8转成HTML实体
    $ret = "";
    $max = strlen($str);
    $last = 0;
    for ($i = 0;$i < $max;$i++){
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1 >> 5 == 6){
            $ret .= substr($str, $last, $i - $last);
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i});
            $c2 &= 63;
            $c2 |= (($c1 & 3) << 6);
            $c1 >>= 2;
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";";
            $last = $i + 1;
            }
        elseif ($c1 >> 4 == 14){
            $ret .= substr($str, $last, $i - $last);
            $c2 = ord($str{++$i});
            $c3 = ord($str{++$i});
            $c1 &= 15;
            $c2 &= 63;
            $c3 &= 63;
            $c3 |= (($c2 & 3) << 6);
            $c2 >>= 2;
            $c2 |= (($c1 & 15) << 4);
            $c1 >>= 4;
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';';
            $last = $i + 1;
            }
        }
    $str = $ret . substr($str, $last, $i);
    return $str;
    }
?>