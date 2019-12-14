<?php

/**
 * 自动选择服务器.
 * 
 * 作者: yourshell.info <yourshell.info@gmail.com>
 * 版本: 0.2
 * 更新日期: 2011.12.08
 * 
 * 使用环境:
 *       服务器要求支持PHP,且PHP的网络函数 fsockopen() 或者 curl()
 *       没有被禁止，其他函数一般都支持
 *       客户端的浏览器需要支持JavaScript,一些低端的手机无法使用
 * 
 * 定义网址:若要服务器连上指定网站，需要在第二个 "autourl[i]+" 后
 * 面和那个单引号前面添加访问的网址，网址格式如下:
 * 
 * /loc/redirect.php?URL=http://www.google.com/
 * 或者是
 * /do/Qa_k/tttLy00yDNLx0X/
 * 
 * 这个脚本可以自由使用.
 */

function getpage($url){
    
     $temp = '';
    
     if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
         $url = @parse_url($url);
         $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
         if (!$fp){
             $http_code = '0';
             echo "$errstr ($errno)<br />\n";
             }else{
             $out = "GET $url[path] HTTP/1.1\r\n";
             $out .= "Host: $url[host]\r\n";
             $out .= "Connection: Close\r\n\r\n";
             fwrite($fp, $out);
             while (!feof($fp)){
                 $temp .= fgets($fp, 128);
                 }
             fclose($fp);
             $http_code = substr($temp, 9, 3);
             $temp = explode("\r\n\r\n", $temp, 2);
             $temp = $temp[1];
             }
         }
    else if(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
         $ch = curl_init($url);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $temp = curl_exec ($ch);
         $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         curl_close ($ch);
         }
    else{
         $temp = 'the hosting do not support curl or fsockopen() ';
         }
     if ($http_code >= 400){ // 400 - 600都是服务器错误
         return 'false';
         echo $temp;
         exit(0);
         }
     return $temp;
     }


function match_links($document){
     preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $document, $links);
     while(list($key, $val) = each($links[2])){
         if(!empty($val))
             $match[] = $val;
         }
     while(list($key, $val) = each($links[3])){
         if(!empty($val))
             $match[] = $val;
         }
     return array($match, $links[4]); //
     }

function dtwencodeurl($url){
     $code = array(
        'a' => 'v', 'b' => 'w', 'c' => 'x', 'd' => 'M', 'e' => 'N',
         'f' => '6', 'g' => 'y', 'h' => 'z', 'i' => 'A', 'j' => 'B',
         'k' => 'C', 'l' => 'D', 'm' => 'X', 'n' => 'Y', 'o' => '0',
         'p' => 'Z', 'q' => '1', 'r' => '2', 's' => '3', 't' => 'a',
         'u' => 'b', 'v' => 'c', 'w' => 't', 'x' => 'u', 'y' => 'E',
         'z' => 'F', '0' => '-', '1' => 'r', '2' => 's', '3' => 'U',
         '4' => 'V', '5' => 'W', '6' => '4', '7' => 'H', '8' => 'I',
         '9' => 'J', '.' => 'L', '/' => '/'
        );
    
    // $url = 'https://google.com';
    $url = str_replace('\\','/',$url);
    
    // 补充并提取协议
    $scheme = parse_url($url, PHP_URL_SCHEME);
     if (empty($scheme)) $url = 'http://' . $url;
     $url = explode('://', $url, 2);
     $scheme = strtolower($url[0]) . '/';
     $url = $url[1];
    
     // 变更HOST为小写
    if(strstr($url, '/')){
         $host = explode('/', $url, 2);
         $url = strtolower($host[0]) . '/' . $host[1];
         }else{
         $url = strtolower($url) . '/';
         }
    
     // 不改变文件名
     
    // 完成URL，模拟 /do/zaaZ3/
    $url = $scheme . $url;
     $n = strlen($url);
     $char = range('A', 'Z');
     $values = '';
     for ($i = 0; $i < $n; $i++){
         $key = $url[$i];
         if(in_array($key, $char)){
             $values .= $key;
             }else{
             $values .= $code[$key];
             }
         }
     $urls = '/do/' . $values;
    
     return $urls;
     }

function jsb64encode(){
    /**
     * Interfaces:
     * b64 = window.btoa(data);
     * data = window.atob(b64);
     */
    
     $b64en = 'if (typeof(btoa) == "undefined") {' .
     'btoa = function() {' .
     'var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/".split("");' .
     'return function(str) {' .
     'var out, i, j, len, r, l, c;' .
     'i = j = 0;' .
     'len = str.length;' .
     'r = len % 3;' .
     'len = len - r;' .
     'l = (len / 3) << 2;' .
     'if (r > 0) {' .
     'l += 4;' .
     '}' .
     'out = new Array(l);' .
    
     'while (i < len) {' .
     'c = str.charCodeAt(i++) << 16 |' .
     'str.charCodeAt(i++) << 8  |' .
     'str.charCodeAt(i++);' .
     'out[j++] = base64EncodeChars[c >> 18]' .
     '+ base64EncodeChars[c >> 12 & 0x3f]' .
     '+ base64EncodeChars[c >> 6  & 0x3f]' .
     '+ base64EncodeChars[c & 0x3f] ;' .
     '}' .
     'if (r == 1) {' .
     'c = str.charCodeAt(i++);' .
     'out[j++] = base64EncodeChars[c >> 2]' .
     '+ base64EncodeChars[(c & 0x03) << 4]' .
     '+ "==";' .
     '}' .
     'else if (r == 2) {' .
     'c = str.charCodeAt(i++) << 8 |' .
     'str.charCodeAt(i++);' .
     'out[j++] = base64EncodeChars[c >> 10]' .
     ' + base64EncodeChars[c >> 4 & 0x3f]' .
     ' + base64EncodeChars[(c & 0x0f) << 2]' .
     ' + "=";' .
     '}' .
     'return out.join("");' .
     '}' .
     '}();' .
     '}';
    
     return $b64en;
     }

// -----------------main--------------------//
// ############提交表单############//

if(empty($_GET['url']) && !isset($_GET['url'])){
    
     $body = '<script language="javascript" >' . "\r\n" . jsb64encode() . 'function autojs(){ document.form.url.value=window.btoa(document.form.url.value); ' . 'document.form.submit();}' . "\n" . '</script>' . "\n\n" . '<title>dtw proxy</title></head><body onload="document.getElementById(' . "'url_box'" . ').focus()"><center>';
    
     $form = '<form name="form" method="get" action="' . $_SERVER['PHP_SELF'] . '"  onsubmit="autojs();" >' .
     'Web Address <input id="url_box" name="url" type="text" value="http://www.google.com/" size=60 />&nbsp;' .
     '<input type="submit" value="浏览任意网站" title="" >' .
     '</form></center></body></html>';
     echo $body . $form;
     exit;
     }

header("Content-type: text/html; charset=GBK");
$url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');
//$url = 'http://127.0.0.1/y.htm';
$temp = getpage($url);
$temp_proxy = explode('<div id="content_list_right1">', $temp);
$temp_proxy = $temp_proxy[1] . $temp_proxy[2];
$temp_proxy = str_replace('http://', '', $temp_proxy);
$proxy = match_links($temp_proxy);
$link = '';
foreach ($proxy[0] as $key => $value){
     $value = 'https://' . gethostbyname($value);
     $link .= "\r\n<br />" . '<a href="' . $value . '">' . $value . '</a>';
     }
$n = count($proxy[0]);

echo '<html><head><meta http-equiv="content-type" content="text/html;charset=gb2312">' . "\r\n" .
 '<script language="javascript" >' . "\r\n" .
 'i=1' . "\r\n" .
 'var autourl=new Array()' . "\r\n";

for($i = 0 ; $i < $n ; $i++){
     $j = $i + 1;
     $proxy[0][$i] = str_replace("//", '\\', $proxy[0][$i]);
     $proxy[0][$i] = str_replace("/", '', $proxy[0][$i]);
     $proxy[0][$i] = str_replace('\\', '//', $proxy[0][$i]);
     $proxy[0][$i] = gethostbyname($proxy[0][$i]);
     echo "autourl[$j]=\"" . $proxy[0][$i] . "\"\r\n";
     }
$url = $_GET['url'];
$url = dtwencodeurl(base64_decode($url));

echo 'function auto(url) ' .
 '{ ' .
 'if(i) ' .
 '{ ' .
 'i=0; ' .
 'top.location=url ' .
 '}} ' .
 'function run() ' .
 '{ ' .
 'for(var i=1; ' .
 'i<autourl.length;i++) ' .
 'document.write("<img src=http://"+autourl[i]+" width=1 height=1 onerror=auto(\'https://"+autourl[i]+"' . $url . '\')>")  ' .
 '} ' .
 'run() ' . "\r\n" .
 '</script>' . "\r\n";

echo '</head><title>请稍等......</title><body>';
// echo $link.'<br /> <br />出现证书提示点“<b>是</b>”，可能连续点几次';
echo '</body></html>';

?> 