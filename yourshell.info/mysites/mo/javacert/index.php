<?php
$store_dir = "up/"; //上传文件存放目录
$url_dir = dirname('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) . '/';

if(!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
     $lang = 'en';
     }else{
     preg_match('/^([a-z\-]+)/i', @$_SERVER["HTTP_ACCEPT_LANGUAGE"], $matches);
     $lang = $matches[1];
     }
if(strstr($lang, 'ru')){
    header("Location: http://simak.ru/javacert/");
    }
if(!strstr($lang, 'zh') && !strstr($lang, 'ru')){
    header("Location: http://mamun7.wen9.com/javacertfier.html");
    }
/**************************************************************/

if(empty($_POST)){
    echo html_form();
    exit;
    }
// jar_url有效，提取 URL 并生成有效的 URL
if(!empty($_POST['jar_url'])){
    
    $jar_url = htmlspecialchars(addslashes(trim(ltrim($_POST['jar_url']))));
    $checkurl = array(str_replace(' ', '%20', $jar_url));
    $_url = new urlcheck();
    if($_url -> check($checkurl[0]) !== 1){
        echo "<center><b>无效的网址</b></center><br><br>";
        echo html_form();
        exit;
        }
    
    $jar_url_x = pathinfo($jar_url);
    $jar_name = $jar_url_x['basename'];
    $jar_name_extension = strtolower($jar_url_x['extension']);
    /**
     * if($jar_name_extension !== '.jar'){
     * echo "<center><b>无效的网址</b></center><br><br>";
     * echo html_form();
     * exit;
     * }
     */
    if(strstr($jar_url, ' ')){
        $jar_name = str_replace(' ', '_', $jar_name);
        $jar_url = str_replace(' ', '%20', $jar_url);
        $jar_contents = @getpage($jar_url);
        if($jar_contents == 'false'){
            echo "<center><b>无效的网址</b></center><br><br>";
            echo html_form();
            exit;
            }
        file_put_contents($store_dir . $jar_name, $jar_contents);
        $jar_url = $url_dir . $store_dir . $jar_name;
        $md5 = md5_file($store_dir . $jar_name);
        $log_contents = '';
        $log_contents = @file_get_contents('jar.log');
        if(!strstr($log_contents, $md5)){
            $log = 'url: ' . $jar_url . '   md5: ' . $md5 . "\r\n";
            file_put_contents('jar.log', $log_contents . $log);
            }
        }
    }

// jar_file有效，处理上传文件
elseif(empty($_POST['jar_url']) && !empty($_FILES['jar_file'])){
    @$jar_file = $_FILES['jar_file']['tmp_name'];
    @$jar_file_name = $_FILES['jar_file']['name'];
    $jar_file_name = str_replace(' ', '_', $jar_file_name);
     $fiearr = explode(".", $jar_file_name);
     $key = count($fiearr)-1;
     $jar_fie_extend = $fiearr[$key];
     $jar_fie_extend = strtolower($jar_fie_extend);
    if('jar' !== $jar_fie_extend){
        echo "<center><b>只允许上传 .jar 文件</b></center><br><br>";
        echo html_form();
        exit;
        }
    $jar_name = $store_dir . $jar_file_name;
    $accept_overwrite = 1;
    if (file_exists($jar_name) && !$accept_overwrite){
        echo "<center><b>文件重名，请在 " .$store_dir. " 查找，或者修改文件名后上传</b></center><br><br>";
        echo html_form();
        exit;
        }
    if (!move_uploaded_file($jar_file, $jar_name)){
        exit;
        }
    $jar_url = $url_dir . $jar_name;
    $md5 = @md5_file($jar_name);
    $log_contents = '';
    $log_contents = @file_get_contents('jar.log');
    if(!strstr($log_contents, $md5)){
        $log = 'url: ' . $jar_url . '   md5: ' . $md5 . "\r\n";
        file_put_contents('jar.log', $log_contents . $log);
        }
    @chmod($jar_name, 0755);
    $uf = $_FILES['jar_file'];
    $size = round((($uf['size']) / 1024), 2);
    echo "<a href=" . $jar_url . ">" . $jar_url . "</a> 大小:" . $size . "KB\r\n<br>";
    }
else{
    echo "<center><b>无法处理数据 *有效网址和上传文件不能同时使用，且不能有中文</b></center><br><br>";
    echo html_form();
    exit;    
    }

// 到此生成了有效的 JAR文件,下来就是把POST数据连接在一起GET过去，之后用fget取得返回值
$phone = $_POST['phone'];

if($phone == 'sie'){
    $phone_cert = '<a href="' .$url_dir. 'certs/sie.zip">';
    }elseif($phone == 'nokia'){
    $phone_cert = '<a href="' .$url_dir. 'certs/nokia.zip">';
    }else{
    $phone_cert = '<a href="' .$url_dir. 'certs/se.zip">';
    }

$get_data = '';
foreach ($_POST as $key => $value){
    $get_data .= $key . '=' . $value . '&';
    }

$url = 'http://simak.ru/?' . $get_data;
$referer = 'http://simak.ru/javacert/';
$contents = getpage($url,$referer);

if(strstr($contents, 'ERROR')){
    echo html_error($phone_cert);
    exit;
    }
if(strstr($contents, 'JAR<br/><a href="/javacert/dl')){
   preg_match("'<div class=\"header_v\"><a href=\"/javacert/dl(.+)<b>1000</b>'s",$contents,$match); //字串
   $match[1] = '<a href="/javacert/dl'.$match[1];
   preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $match[1], $links); // 链接
   preg_match("'<b>(.+)</b>'s",$match[1],$size);  // 大小 $size[0]
   $jad = 'JAD: <a href="' .$links[4][0]. '">' .$links[4][0]. '</a><br/>复制: <input size="60" type="text" value="' .$links[4][0]. '"/><br/><br/>';
   $jar = 'JAR: <a href="' .$links[4][1]. '">' .$links[4][1]. '</a><br/>复制: <input size="60" type="text" value="' .$links[4][1]. '"/><br/><br/>';
   $size = 'JAR文件大小: ' .$size[0];

   $response_url = $jad.$jar.$size;
   echo html_ok($phone_cert,$response_url);
   exit;
   }

function getpage($url,$referer){
    if (empty($referer)){
        $referer = $_SERVER[HTTP_REFERER];   
        }
    $url = @parse_url($url);
    if(isset($url['query'])){
        $url_get = $url['path'] . '?' . $url['query'];
        }else{
        $url_get = $url['path'];
        }
    $temp = '';
    
    if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
        $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
        if (!$fp){
            $http_code = '0';
            echo "$errstr ($errno)<br />\n";
            }else{
            $out = "GET $url_get HTTP/1.0\r\n";
            $out .= "Host: $url[host]\r\n";
            $out .= "Referer: $referer \r\n";
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
        }elseif(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec ($ch);
        $http_code = getpageinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        }else{
        $http_code = '0';
        $temp = 'the hosting do not support curl or fsockopen() ';
        }
    if ($http_code >= 400){ // 400 - 600都是服务器错误
        return 'false';
        echo $temp;
        exit(0);
        }else{
        return $temp;
        }
    }


function html_form(){

print<<<PART0
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='zh-cn'><head>
<meta http-equiv="Cache-Control" content="private,max-age=60"/>
PART0;
echo _style();
print<<<PART1
<title>在线java签署证书</title></head>
<body><center><h3>JAVA 签署证书</h3>
PART1;
    echo '<form enctype="multipart/form-data" action="' . $_SERVER['PHP_SELF'] . '" method="post" >';
print<<<PART2
<div>您的手机型号<br />
<table class="bt1">
    <tr><td class="small"><input type="radio" name="phone" value="sie" /></td><td class="main">西门子</td></tr>
    <tr><td class="small"><input type="checkbox" name="phone1" value="sg"/></td><td class="main">SGold</td></tr>
    <tr><td class="small"><input type="radio" name="phone" value="nokia" checked="checked"/></td><td class="main">诺基亚 (<span class="red_note">使用EXP(Darkman)证书</span>)</td></tr>
    <tr><td class="small"><input type="radio" name="phone" value="se"/></td><td class="main">索尼爱立信 (<span class="red_note">使用HALMER证书</span>)</td></tr>
</table>
<br />MIDlet的权限<br />
<table class="bt1">
    <tr><td class="small"><input type="checkbox" name="FILE_SYSTEM" value="1"/ checked="checked"></td><td class="main">文件系统 - 读/写文件</td></tr>
    <tr><td class="small"><input type="checkbox" name="INTERNET" value="1"/ checked="checked"></td><td class="main">互联网 - 互联网接入</td></tr>
    <tr><td class="small"><input type="checkbox" name="BLUETOOTH" value="1"/></td><td class="main">蓝牙 - 通过蓝牙连接</td></tr>
    <tr><td class="small"><input type="checkbox" name="MEDIA" value="1" checked="checked"/></td><td class="main">多媒体 - 拍摄和录制的MIDlet</td></tr>
    <tr><td class="small"><input type="checkbox" name="ADDRESSBOOK" value="1"/></td><td class="main">通讯录 - 使用手机通讯录</td></tr>
    <tr><td class="small"><input type="checkbox" name="EVENT" value="1"/></td><td class="main">启动权限 - 自动启动/事件</td></tr>
    <tr><td class="small"><input type="checkbox" name="COMM" value="1"/></td><td class="main">链接功能 - 与komportom工作</td></tr>
    <tr><td class="small"><input type="checkbox" name="PUSHREGISTRY" value="1"/ checked="checked"></td><td class="main">推送注册 - 自动运行MIDlet上运行的事件</td></tr>
    <tr><td class="small"><input type="checkbox" name="MMS" value="1"/></td><td class="main">彩信 - 发送彩信</td></tr>
    <tr><td class="small"><input type="checkbox" name="PHONECALL" value="1"/ checked="checked"></td><td class="main">打电话 - 允许应用程序拨打电话</td></tr>
    <tr><td class="small"><input type="checkbox" name="SMS" value="1"/ checked="checked"></td><td class="main">短信 - 发送和接收短信<span class="red_note"> *不推荐！</span></td></tr>
</table>
<br />输入的.JAR文件网址或上传本机.JAR文件: <span class="red_note"> *有效网址和上传文件不能同时使用，且不能有中文</span>
<div class="header_v"><br /><input type="hidden" name="thp" value="27"/>有效.JAR文件网址: <input type="text" name="jar_url" value="" size="60" /> <br /><br />
<input type="hidden" name="MAX_FILE_SIZE" value="2000000">上传本机.JAR文件:  <input name="jar_file" type="file" size="60" /><br/><br/><center><input type="submit" value=" 签署证书" /></center><br/><br/></div>
</div>
</form>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #要塞信息港#</div><br/><br/></body></html>
PART2;
    }

function html_ok($phone_cert, $response_url){ // 依手机型号而定
print<<<PART0
<?xml version='1.0' encoding='UTF-8'?><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='zh-cn'>
<head>
<meta http-equiv="Cache-Control" content="private,max-age=60"/>
PART0;
echo _style();
print<<<PART1
<link rel="shortcut icon" href="/favicon.ico"/><title>在线 JAVA 签署证书</title></head><body>
<center><h3>JAVA  署证书</h3>您手机的证书:
<div class="header_v">如果您的手机上没有安装签证所需要的根证书，下载并安装到手机内: 
PART1;
    
    echo $phone_cert;
    
print<<<PART2
下载证书</a> <span class="red_note"> *自述文件在存档里面..</span></div>
<br/>已签署证书的.JAD文件的链接:<br/><div class="contur_v"><div class="header_v">
PART2;
    
    echo $response_url." KB; \t";
    
print<<<PART3
   链接的有效时长: <b>1000</b> 分钟<br/><br/>请<a href="/javacert/"> 返回</a>继续发证</div></div>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #要塞信息港#</div></body></html>'
PART3;
    }

function html_error($phone_cert){
print<<<PART0
<?xml version='1.0' encoding='UTF-8'?><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='zh-cn'>
<head>
<meta http-equiv="Cache-Control" content="private,max-age=60"/>
PART0;
echo _style();
print<<<PART1
<link rel="shortcut icon" href="/favicon.ico"/><title>在线 JAVA 签署证书</title></head><body>
<center><h3>JAVA  署证书</h3>您手机的证书:
<div class="header_v">如果您的手机上没有安装签证所需要的根证书，下载并安装到手机内: 
PART1;
    
    echo $phone_cert;
    
print<<<PART2
下载证书</a> <span class="red_note"> *自述文件在存档里面..</span></div>
<br/>已签署证书的.JAD文件的链接:
<br/><div class="contur_v"><div class="header_v">发生错误: 无法使用指定的输入文件!<br/><br/> 请<a href="/javacert/">返回</a> 输入正确的 .jar 文件地址或者上传 .JAR 文件</div></div>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #要塞信息港#</div></body></html>
PART2;
    }

function _style(){
print<<<STYLE
<style type="text/css"> 
table.bt1 {
border-collapse:collapse;
width:80%;
border:thin solid #99CC66;
margin:1px;
padding:2px;
}
td.small {
border:thin dashed ;
width:5%;
text-align:center;
padding:4px;
}
td.main {
border:thin dashed ;
width:80%;
text-align:left;
padding:2px;
}
.header,.header_v,.header_rek {
border:thin solid #99CC66;
width:80%;
text-align:left;
margin:1px;
padding:2px;
}
.red_note {
color:red;
font-size:12px;
}
</style>
STYLE;
}
class urlcheck{
    var $regex = array(// 协议名(注意在这里必须写成小写) => 对应的正则表达式
        'ftp' => '$this->ftpurl',
        'file' => '$this->fileurl',
        'http' => '$this->httpurl',
        'https' => '$this->httpurl',
        'gopher' => '$this->gopherurl',
        'news' => '$this->newsurl',
        'nntp' => '$this->nntpurl',
        'telnet' => '$this->telneturl',
        'wais' => '$this->waisurl'
        );
    
    var $lowalpha;
    var $hialpha;
    var $alpha;
    var $digit;
    var $safe;
    var $extra;
    var $national;
    var $punctuation;
    var $reserved;
    var $hex;
    var $escape;
    var $unreserved;
    var $uchar;
    var $xchar;
    var $digits;
    
    var $urlpath;
    var $password;
    var $user;
    var $port;
    var $hostnumber;
    var $alphadigit;
    var $toplabel;
    var $domainlabel;
    var $hostname;
    var $host;
    var $hostport;
    var $login;
    
    // ftp
    var $ftptype;
    var $fsegment;
    var $fpath;
    var $ftpurl;
    
    // file
    var $fileurl;
    
    // http,https
    var $search;
    var $hsegment;
    var $hpath;
    var $httpurl;
    
    // gopher
    var $gopher_string;
    var $selector;
    var $gtype;
    var $gopherurl;
    
    // news
    var $article;
    var $group;
    var $grouppart;
    var $newsurl;
    
    // nntp
    var $nntpurl;
    
    // telnet
    var $telneturl;
    
    // wais
    var $wpath;
    var $wtype;
    var $database;
    var $waisdoc;
    var $waisindex;
    var $waisdatabase;
    var $waisurl;
    
    function check($url){
        $pos = @strpos($url, ':', 1);
        if($pos < 1) return false;
        $prot = substr($url, 0, $pos);
        if(!isset($this -> regex[$prot])) return false;
        eval('$regex = ' . $this -> regex[$prot] . ';');
        return ereg('^' . $regex . '$', $url);
        }
    
    function urlcheck(){
        $this -> lowalpha = '[a-z]';
        $this -> hialpha = '[A-Z]';
        $this -> alpha = '(' . $this -> lowalpha . '|' . $this -> hialpha . ')';
        $this -> digit = '[0-9]';
        $this -> safe = '[$.+_-]';
        $this -> extra = '[*()\'!,]';
        $this -> national = '([{}|\^~`]|\\[|\\])';
        $this -> punctuation = '[<>#%"]';
        $this -> reserved = '[?;/:@&=]';
        $this -> hex = '(' . $this -> digit . '|[a-fA-F])';
        $this -> escape = '(%' . $this -> hex . '{2})';
        $this -> unreserved = '(' . $this -> alpha . '|' . $this -> digit . '|' . $this -> safe . '|' . $this -> extra . ')';
        $this -> uchar = '(' . $this -> unreserved . '|' . $this -> escape . ')';
        $this -> xchar = '(' . $this -> unreserved . '|' . $this -> reserved . '|' . $this -> escape . ')';
        $this -> digits = '(' . $this -> digit . '+)';
        
        $this -> urlpath = '(' . $this -> xchar . '*)';
        $this -> password = '((' . $this -> uchar . '|[?;&=]' . ')*)';
        $this -> user = '((' . $this -> uchar . '|[?;&=]' . ')*)';
        $this -> port = $this -> digits;
        $this -> hostnumber = '(' . $this -> digits . '.' . $this -> digits . '.' . $this -> digits . '.' . $this -> digits . ')';
        $this -> alphadigit = '(' . $this -> alpha . '|' . $this -> digit . ')';
        $this -> toplabel = '(' . $this -> alpha . '|(' . $this -> alpha . '(' . $this -> alphadigit . '|-)*' . $this -> alphadigit . '))';
        $this -> domainlabel = '(' . $this -> alphadigit . '|(' . $this -> alphadigit . '(' . $this -> alphadigit . '|-)*' . $this -> alphadigit . '))';
        $this -> hostname = '((' . $this -> domainlabel . '\\.)*' . $this -> toplabel . ')';
        $this -> host = '(' . $this -> hostname . '|' . $this -> hostnumber . ')';
        $this -> hostport = '(' . $this -> host . '(:' . $this -> port . ')?)';
        $this -> login = '((' . $this -> user . '(:' . $this -> password . ')?@)?' . $this -> hostport . ')';
        
        $this -> ftptype = '[aidAID]';
        $this -> fsegment = '((' . $this -> uchar . '|[?:@&=])*)';
        $this -> fpath = '(' . $this -> fsegment . '(/' . $this -> fsegment . ')*)';
        $this -> ftpurl = '([fF][tT][pP]://' . $this -> login . '(/' . $this -> fpath . '(;[tT][yY][pP][eE]=' . $this -> ftptype . ')?)?)';
        
        $this -> fileurl = '([fF][iI][lL][eE]://(' . $this -> host . '|[lL][oO][cC][aA][lL][hH][oO][sS][tT])?/' . $this -> fpath . ')';
        
        $this -> search = '((' . $this -> uchar . '|[;:@&=])*)';
        $this -> hsegment = '((' . $this -> uchar . '|[;:@&=])*)';
        $this -> hpath = '(' . $this -> hsegment . '(/' . $this -> hsegment . ')*)';
        $this -> httpurl = '([hH][tT][tT][pP][sS]?://' . $this -> hostport . '(/' . $this -> hpath . '([?]' . $this -> search . ')?)?)';
        
        $this -> gopher_string = '(' . $this -> xchar . '*)';
        $this -> selector = '(' . $this -> xchar . '*)';
        $this -> gtype = $this -> xchar;
        $this -> gopherurl = '([gG][oO][pP][hH][eE][rR]://' . $this -> hostport . '(/(' . $this -> gtype . '(' . $this -> selector . '(%09' . $this -> search . '(%09' . $this -> gopher_string . ')?)?)?)?)?)';
        
        $this -> article = '((' . $this -> uchar . '|[;/?:&=])+@' . $this -> host . ')';
        $this -> group = '(' . $this -> alpha . '(' . $this -> alpha . '|' . $this -> digit . '|[-.+_])*)';
        $this -> grouppart = '([*]|' . $this -> group . '|' . $this -> article . ')';
        $this -> newsurl = '([nN][eE][wW][sS]:' . $this -> grouppart . ')';
        
        $this -> nntpurl = '([nN][nN][tT][pP]://' . $this -> hostport . '/' . $this -> group . '(/' . $this -> digits . ')?)';
        
        $this -> telneturl = '([tT][eE][lL][nN][eE][tT]://' . $this -> login . '/?)';
        
        $this -> wpath = '(' . $this -> uchar . '*)';
        $this -> wtype = '(' . $this -> uchar . '*)';
        $this -> database = '(' . $this -> uchar . '*)';
        $this -> waisdoc = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . '/' . $this -> wtype . '/' . $this -> wpath . ')';
        $this -> waisindex = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . '[?]' . $this -> search . ')';
        $this -> waisdatabase = '([wW][aA][iI][sS]://' . $this -> hostport . '/' . $this -> database . ')';
        $this -> waisurl = '(' . $this -> waisdatabase . '|' . $this -> waisindex . '|' . $this -> waisdoc . ')';
        }
    }