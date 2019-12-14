<?php
$store_dir = "up/"; //�ϴ��ļ����Ŀ¼
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
// jar_url��Ч����ȡ URL ��������Ч�� URL
if(!empty($_POST['jar_url'])){
    
    $jar_url = htmlspecialchars(addslashes(trim(ltrim($_POST['jar_url']))));
    $checkurl = array(str_replace(' ', '%20', $jar_url));
    $_url = new urlcheck();
    if($_url -> check($checkurl[0]) !== 1){
        echo "<center><b>��Ч����ַ</b></center><br><br>";
        echo html_form();
        exit;
        }
    
    $jar_url_x = pathinfo($jar_url);
    $jar_name = $jar_url_x['basename'];
    $jar_name_extension = strtolower($jar_url_x['extension']);
    /**
     * if($jar_name_extension !== '.jar'){
     * echo "<center><b>��Ч����ַ</b></center><br><br>";
     * echo html_form();
     * exit;
     * }
     */
    if(strstr($jar_url, ' ')){
        $jar_name = str_replace(' ', '_', $jar_name);
        $jar_url = str_replace(' ', '%20', $jar_url);
        $jar_contents = @getpage($jar_url);
        if($jar_contents == 'false'){
            echo "<center><b>��Ч����ַ</b></center><br><br>";
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

// jar_file��Ч�������ϴ��ļ�
elseif(empty($_POST['jar_url']) && !empty($_FILES['jar_file'])){
    @$jar_file = $_FILES['jar_file']['tmp_name'];
    @$jar_file_name = $_FILES['jar_file']['name'];
    $jar_file_name = str_replace(' ', '_', $jar_file_name);
     $fiearr = explode(".", $jar_file_name);
     $key = count($fiearr)-1;
     $jar_fie_extend = $fiearr[$key];
     $jar_fie_extend = strtolower($jar_fie_extend);
    if('jar' !== $jar_fie_extend){
        echo "<center><b>ֻ�����ϴ� .jar �ļ�</b></center><br><br>";
        echo html_form();
        exit;
        }
    $jar_name = $store_dir . $jar_file_name;
    $accept_overwrite = 1;
    if (file_exists($jar_name) && !$accept_overwrite){
        echo "<center><b>�ļ����������� " .$store_dir. " ���ң������޸��ļ������ϴ�</b></center><br><br>";
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
    echo "<a href=" . $jar_url . ">" . $jar_url . "</a> ��С:" . $size . "KB\r\n<br>";
    }
else{
    echo "<center><b>�޷��������� *��Ч��ַ���ϴ��ļ�����ͬʱʹ�ã��Ҳ���������</b></center><br><br>";
    echo html_form();
    exit;    
    }

// ������������Ч�� JAR�ļ�,�������ǰ�POST����������һ��GET��ȥ��֮����fgetȡ�÷���ֵ
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
   preg_match("'<div class=\"header_v\"><a href=\"/javacert/dl(.+)<b>1000</b>'s",$contents,$match); //�ִ�
   $match[1] = '<a href="/javacert/dl'.$match[1];
   preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $match[1], $links); // ����
   preg_match("'<b>(.+)</b>'s",$match[1],$size);  // ��С $size[0]
   $jad = 'JAD: <a href="' .$links[4][0]. '">' .$links[4][0]. '</a><br/>����: <input size="60" type="text" value="' .$links[4][0]. '"/><br/><br/>';
   $jar = 'JAR: <a href="' .$links[4][1]. '">' .$links[4][1]. '</a><br/>����: <input size="60" type="text" value="' .$links[4][1]. '"/><br/><br/>';
   $size = 'JAR�ļ���С: ' .$size[0];

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
    if ($http_code >= 400){ // 400 - 600���Ƿ���������
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
<title>����javaǩ��֤��</title></head>
<body><center><h3>JAVA ǩ��֤��</h3>
PART1;
    echo '<form enctype="multipart/form-data" action="' . $_SERVER['PHP_SELF'] . '" method="post" >';
print<<<PART2
<div>�����ֻ��ͺ�<br />
<table class="bt1">
    <tr><td class="small"><input type="radio" name="phone" value="sie" /></td><td class="main">������</td></tr>
    <tr><td class="small"><input type="checkbox" name="phone1" value="sg"/></td><td class="main">SGold</td></tr>
    <tr><td class="small"><input type="radio" name="phone" value="nokia" checked="checked"/></td><td class="main">ŵ���� (<span class="red_note">ʹ��EXP(Darkman)֤��</span>)</td></tr>
    <tr><td class="small"><input type="radio" name="phone" value="se"/></td><td class="main">���ᰮ���� (<span class="red_note">ʹ��HALMER֤��</span>)</td></tr>
</table>
<br />MIDlet��Ȩ��<br />
<table class="bt1">
    <tr><td class="small"><input type="checkbox" name="FILE_SYSTEM" value="1"/ checked="checked"></td><td class="main">�ļ�ϵͳ - ��/д�ļ�</td></tr>
    <tr><td class="small"><input type="checkbox" name="INTERNET" value="1"/ checked="checked"></td><td class="main">������ - ����������</td></tr>
    <tr><td class="small"><input type="checkbox" name="BLUETOOTH" value="1"/></td><td class="main">���� - ͨ����������</td></tr>
    <tr><td class="small"><input type="checkbox" name="MEDIA" value="1" checked="checked"/></td><td class="main">��ý�� - �����¼�Ƶ�MIDlet</td></tr>
    <tr><td class="small"><input type="checkbox" name="ADDRESSBOOK" value="1"/></td><td class="main">ͨѶ¼ - ʹ���ֻ�ͨѶ¼</td></tr>
    <tr><td class="small"><input type="checkbox" name="EVENT" value="1"/></td><td class="main">����Ȩ�� - �Զ�����/�¼�</td></tr>
    <tr><td class="small"><input type="checkbox" name="COMM" value="1"/></td><td class="main">���ӹ��� - ��komportom����</td></tr>
    <tr><td class="small"><input type="checkbox" name="PUSHREGISTRY" value="1"/ checked="checked"></td><td class="main">����ע�� - �Զ�����MIDlet�����е��¼�</td></tr>
    <tr><td class="small"><input type="checkbox" name="MMS" value="1"/></td><td class="main">���� - ���Ͳ���</td></tr>
    <tr><td class="small"><input type="checkbox" name="PHONECALL" value="1"/ checked="checked"></td><td class="main">��绰 - ����Ӧ�ó��򲦴�绰</td></tr>
    <tr><td class="small"><input type="checkbox" name="SMS" value="1"/ checked="checked"></td><td class="main">���� - ���ͺͽ��ն���<span class="red_note"> *���Ƽ���</span></td></tr>
</table>
<br />�����.JAR�ļ���ַ���ϴ�����.JAR�ļ�: <span class="red_note"> *��Ч��ַ���ϴ��ļ�����ͬʱʹ�ã��Ҳ���������</span>
<div class="header_v"><br /><input type="hidden" name="thp" value="27"/>��Ч.JAR�ļ���ַ: <input type="text" name="jar_url" value="" size="60" /> <br /><br />
<input type="hidden" name="MAX_FILE_SIZE" value="2000000">�ϴ�����.JAR�ļ�:  <input name="jar_file" type="file" size="60" /><br/><br/><center><input type="submit" value=" ǩ��֤��" /></center><br/><br/></div>
</div>
</form>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #Ҫ����Ϣ��#</div><br/><br/></body></html>
PART2;
    }

function html_ok($phone_cert, $response_url){ // ���ֻ��ͺŶ���
print<<<PART0
<?xml version='1.0' encoding='UTF-8'?><html xmlns='http://www.w3.org/1999/xhtml' xml:lang='zh-cn'>
<head>
<meta http-equiv="Cache-Control" content="private,max-age=60"/>
PART0;
echo _style();
print<<<PART1
<link rel="shortcut icon" href="/favicon.ico"/><title>���� JAVA ǩ��֤��</title></head><body>
<center><h3>JAVA  ��֤��</h3>���ֻ���֤��:
<div class="header_v">��������ֻ���û�а�װǩ֤����Ҫ�ĸ�֤�飬���ز���װ���ֻ���: 
PART1;
    
    echo $phone_cert;
    
print<<<PART2
����֤��</a> <span class="red_note"> *�����ļ��ڴ浵����..</span></div>
<br/>��ǩ��֤���.JAD�ļ�������:<br/><div class="contur_v"><div class="header_v">
PART2;
    
    echo $response_url." KB; \t";
    
print<<<PART3
   ���ӵ���Чʱ��: <b>1000</b> ����<br/><br/>��<a href="/javacert/"> ����</a>������֤</div></div>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #Ҫ����Ϣ��#</div></body></html>'
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
<link rel="shortcut icon" href="/favicon.ico"/><title>���� JAVA ǩ��֤��</title></head><body>
<center><h3>JAVA  ��֤��</h3>���ֻ���֤��:
<div class="header_v">��������ֻ���û�а�װǩ֤����Ҫ�ĸ�֤�飬���ز���װ���ֻ���: 
PART1;
    
    echo $phone_cert;
    
print<<<PART2
����֤��</a> <span class="red_note"> *�����ļ��ڴ浵����..</span></div>
<br/>��ǩ��֤���.JAD�ļ�������:
<br/><div class="contur_v"><div class="header_v">��������: �޷�ʹ��ָ���������ļ�!<br/><br/> ��<a href="/javacert/">����</a> ������ȷ�� .jar �ļ���ַ�����ϴ� .JAR �ļ�</div></div>
<div><br/> &#169; 2011 <a href="http://yourshell.info/" >yourshell.info</a> #Ҫ����Ϣ��#</div></body></html>
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
    var $regex = array(// Э����(ע�����������д��Сд) => ��Ӧ��������ʽ
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