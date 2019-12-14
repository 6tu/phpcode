<?php
header("Content-type: text/html;charset=GBK");
# 由于需要向 index.php 写数据，所以不能命名为 index.php
$mylink = array
(
array('http://cmded.net/forum/', 'CMDeD 论坛', '001'),
array('http://127.0.0.1/phpbb2/', 'google 搜索', 'google')
);
$var_name = array(
array('网&nbsp;&nbsp; 址 (必填)','link'),
array('网站名称(必填)','link_name'),
array('精简代号','sort_name')
);
$title = '增加书签';
$body = '增加书签';
$sign = 'text';
$Login = 1;
$user = 'root'; $pass = 'rootpass';

if(isset($_GET['admin']) && $_GET['admin'] == 'admin'){
echo auth_user($Login, $user, $pass);
echo form($var_name,$title,$body,$sign);
exit(0);
}
# 增加书签
if(isset($_POST['link'])){
echo auth_user($Login, $user, $pass);
$_link = utf8togbk(base64_decode(hex2str(@$_POST['link']))); 
$_link = htmlspecialchars(addslashes(trim(ltrim(strtolower($_link)))));
$_link = str_replace(' ', '', $_link);

if (strpos($_link, '://') === false){
$_link = 'http://' . $_link;
}
//$_link = str_replace('https://', 'http://', $_link);

$_link_domain = @parse_url($_link);
if(strstr($_link_domain['host'], '.') == false){ #这里存在更改的可能
$title = "网址有误，请重新输入";
$body = $title;
echo form($var_name,$title,$body,$sign);
exit(0);
}
if(!isset($_link_domain['path'])){
$_link = $_link."/";
}
if (isset($_link_domain['path']) && strrpos($_link_domain['path'],'/') !== strlen($_link_domain['path'])-1 && strstr($_link_domain['path'],'.') == false){
$_link = $_link."/";
}

$_link_name = utf8togbk(base64_decode(hex2str($_POST['link_name']))); 
$_link_name = htmlspecialchars(addslashes(trim(ltrim(strtolower($_link_name)))));
if(empty($_link_name)){
$title = "请输入网站名称";
$body = $title;
echo form($var_name,$title,$body,$sign);
exit(0);
}

$_link_sort = utf8togbk(base64_decode(hex2str($_POST['sort_name']))); 
$_link_sort = htmlspecialchars(addslashes(trim(ltrim(strtolower($_link_sort)))));
$_link_sort = str_replace(' ', '', $_link_sort);

# 提交数据处理完毕

$ht = file_get_contents('.htaccess');
$_ht = explode("\r\n###\r\n", $ht);

$n1 = count($_ht);
for($i = 0; $i < $n1 ;$i++){

$_ht[$i] = str_replace('$ ', '##',$_ht[$i]);
$_ht[$i] = explode('##' , $_ht[$i]);
if(@$_ht[$i][2] == $_link){
$_link=$_ht[$i][2];
$_link_name = $_ht[$i][3];
$_link_sort = $_ht[$i][4];
$_link_enc = $_ht[$i][1];

echo "\r\n<br />".$_link."已被记录过，数据如下\r\n<br />";
echo '代理网址 <a href="' . $_link_enc . '">' . $_link_enc . '</a><br />';
echo '缩短网址 <a href="/pe/my/' . $_link_sort . '">' . $_link_sort . '</a><br />';
$title = "已经有该网址";
$body = '请输入网址';
echo form($var_name,$title,$body,$sign);
exit(0);
}
}
if(empty($_link_sort)){
$_link_sort = str_pad(($n1 + 4), 3, 0, STR_PAD_LEFT);
}
// 这里生成代理网址// $_SERVER['PHP_SELF'] . 

$relink = 'RewriteRule ^my/' . $_link_sort . '$ ' .'/pe/?q=' . mylinkenc($_link) . "mylink=&hl=6e9\r\n##$_link##$_link_name##$_link_sort\r\n###\r\n";
$ht = str_replace('</IfModule>', $relink, $ht);
$ht .= '</IfModule>';
file_put_contents('.htaccess', $ht);
echo "\r\n<br />".$_link."已添加到书签了\r\n<br />";
echo $_link_name . ' 的真实代理网址 <a href="/pe/?q=' . mylinkenc($_link) . 'mylink=&hl=6e9" >' . mylinkenc($_link) . '</a><br />';
echo $_link_name . ' 的缩短网址 <a href="my/' . $_link_sort . '">' . $_link_sort . '</a><br />';
$title = "添加完毕";
$body = '请输入网址';
echo form($var_name,$title,$body,$sign);
exit(0);
}
if(isset($_GET['ht']) && $_GET['ht'] == 'htaccess'){
echo auth_user($Login, $user, $pass);
$ht=file_get_contents('.htaccess');
$var_name = array(
array('内容','ht'),
);
$title = '修改 .htaccess';
$body = $ht;
$sign = 'textarea';
$htaccess = form($var_name,$title,$body,$sign);	
if(extension_loaded('mbstring')){
$htaccess = mb_convert_encoding($htaccess, 'UTF-8', 'GBK');
}else if(extension_loaded('iconv')){
$htaccess = iconv('GBK', 'UTF-8//IGNORE//TRANSLIT', $htaccess);
}
$htaccess = utf2html($htaccess);

$key = rand(0, 1) . rand(1, 9);
$htaccess = base64_encode(encrypt_string($htaccess, $key));
$htaccess = '<script type="text/javascript" src="./js/encode.js"></script>' . "\r\n" .
    '<script type="text/javascript">' . "\r\n" .
    'var data=window.atob("' . $htaccess . '");' . "\r\n" .
    'var string=decrypt_string(data, "' . $key . '");' . "\r\n" .
    'document.write(string);' . "\r\n" .
    '</script>';
echo $htaccess;
}
if(isset($_POST['ht'])){
$ht = base64_decode(hex2str($_POST['ht'])); 
echo utf8togbk($ht);
file_put_contents('.htaccess',$ht);
}
## 修改INDEX.PHP
if(isset($_GET['index']) && $_GET['index'] == 'index'){
echo auth_user($Login, $user, $pass);
$index=file_get_contents('./pe/index.php');
$var_name = array(
array('内容','index'),
);
$title = '修改 .index.php';
$body = $index;
$sign = 'textarea';
echo form($var_name,$title,$body,$sign);		
}
if(isset($_POST['index'])){
$index = utf8togbk(base64_decode(hex2str($_POST['index']))); 
//echo $index;
file_put_contents('index.txt',$index);
}





function auth_user($Login, $user, $pass){
if ($Login){
$valid_passwords = array
(
$user => $pass,
);
$valid_users = array_keys($valid_passwords);
$user = isset ($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
$pass = isset ($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
if (!$validated){
header('WWW-Authenticate: Basic realm="My Realm"');
header('HTTP/1.0 401 Unauthorized');
die ('Not authorized');}
}
}
function form($var_name,$title,$body,$sign){
if($sign == 'textarea'){
$ht = $body;
$body = '内容';
}
$n = count($var_name);
$_url_form = '<html><head><title>' .$title. '</title><div style="margin:0;text-align:center;background-color:#99CC66;font-size:12px;font-weight:bold;padding:0px;">'
 . '<script src="./js/encode.js" type="text/javascript"></script><script language="javascript" >'."\r\n"
		 . 'function autojs(){';
		 for($i = 0;$i < $n; $i++){
$_url_form .= 'document.form.'.$var_name[$i][1].'.value=str2hex(window.btoa(utf16to8(document.form.'.$var_name[$i][1].'.value))); ';
 }
		$_url_form .= 'document.form.submit();}</script></head>'."\r\n\r\n".'<body>'
		. '<h3>'.$body.'</h3>'

 . '<form name="form" method="post" action="' . $_SERVER['PHP_SELF'] . '" onsubmit="autojs()">'
 . '<table>';
 for($i = 0;$i < $n; $i++){
		 if($sign == 'text'){
		 $_url_form .= '<tr><td style="text-align: left">'.$var_name[$i][0].':&nbsp;&nbsp;</td>'
. '<td><input type="text" size="80" name="' . $var_name[$i][1] . '" /></td></tr>';
		}
		if($sign == 'textarea'){
		$_url_form .= '<textarea name="' . $var_name[$i][1] . '" style="height:500px;width:1000px" >'.$ht.'</textarea>';
		}
		}
				 //$_url_form .= '<tr><td></td><td><input type="hidden" name="admin" value="admin" /></td></tr>';
		
		 $_url_form .= '<tr><td></td><td style="text-align: center"><input type="submit" value="提交" /></td></tr></table>'
. '</form></div>';
return $_url_form;
}



function mylinkenc($strlink)
{
$enclink = str2hex(base64_encode(encrypt_string($strlink,'8')));
return $enclink;
}

function mylinkdec($enclink)
{
$strlink = deccrypt_string(base64_decode(hex2str($enclink)),'8');
return $strlink;
}

function encrypt_string($input, $key){ // 加密函数
// global $key;
$line = "";
for($i = 0;$i < strlen($input);$i++){
$line .= chr(ord($input[$i]) + $key); //ord()返回字符的 ASCII 值。十进制
} //chr()从指定的 ASCII 值返回字符。
return $line;
}

function deccrypt_string($input, $key){ // //解密函数
// global $key;
$line = "";
for($i = 0;$i < strlen($input);$i++){
$line .= chr(ord($input[$i]) - $key);
}
return $line;
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
	
function utf8togbk($str){
    if(extension_loaded('mbstring')){
        $str = mb_convert_encoding($str, 'GBK', 'UTF-8');
        }else if(extension_loaded('iconv')){
        $str = iconv('UTF-8', 'GBK//IGNORE//TRANSLIT', $str);
        }
	return $str;
    }