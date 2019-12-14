<?php
/**
 * 由于 json_encode() 函数的原因，此文件保存为 utf-8 编码格式 , 
 * 或是用 utf2html($str) 把 UTF-8 转为10进制编码
 * 在线工具 http://tool.chinaz.com/tools/unicode.aspx
 * $str = mb_convert_encoding($str, "UTF-8", "GB2312");
 *
 * 用 ?admin 呼出修改网址的表单
 *
 */

# 设置密码
$pw = 'admin';
$fn = 'index.php';
// $fn = substr($_SERVER['PHP_SELF'],strripos($_SERVER['PHP_SELF'],"/")+1);

session_start();

# 同一IP是否每次都要都要验证，需要则去掉下行的 //
session_destroy();

# 修改网址
if(isset($_GET['admin'])){
	header("Content-type: text/html; charset=utf-8");
	exit(self_form());
}
if(!empty($_GET['url']) and $_GET['pw'] == $pw){
	header('Refresh:3,url='. $fn);
	$phpself = file_get_contents($fn);
	$array = explode("\n", $phpself);
	$n = count($array);
		for($i = 50 ; $i < $n ; $i++){
			// if(strstr($array[$i], 'http://')) $array[$i] = '';
			// if(strstr($array[$i], 'https://')) $array[$i] = '';

			if(strstr($array[$i], '##url')){
				
				$array[$i] = '$url = "'. trim($_GET['url']) ."\";\r\n##url\r\n";
				break;
			}
		}
	$c = join($array);
	$c = str_replace("\r", "\r\n", $c);
	file_put_contents($fn, $c);
	exit;
}

# 设置 SESSION
$ipmd5 =  md5($_SERVER["REMOTE_ADDR"]);
$id = substr($ipmd5, 5, 10);
$val = sha1($ipmd5);
if(@$_SESSION[$id] != $val){
	$_SESSION[$id] = $val;
	$session_id = session_id();
	setcookie('PHPSESSID', $session_id, time()+7*24*3600);

	if(empty($_POST)) {
		header("Content-type: text/html; charset=utf-8");
		exit(html());
	}
}

$host = 'hao369.info';
$rand_str = rand_char(rand(5, 30));
$num = rand(1000, 9999);
$url = 'http://' . $rand_str .'.'. $host .'/'. rand(1000, 9999);
$url = "http://$rand_str.g.cn/$num";
$url = "http://$rand_str.hao369.info/$num";
$url = "http://g.cn";
$url = "http://";

##url


$str = customize_flush() . '<br><center>&#21152;&#32;&#36733;&#32;&#20013; ...  </center><script>window.location.href="' . $url . '";</script>';

if(empty($_SESSION['point'])){
	$point = $_POST['point'];
	$_SESSION['point'] = $point;
	if( empty($point) ) exit(json_encode(array(5822,'验证失败')));
	echo json_encode(array('0'=>'0' , '1'=>$str ));
}else{
	$point = $_SESSION['point'];
	if( empty($point) ) exit(json_encode(array(5822,'验证失败')));
	echo $str;
}

/****** 函数部分,无需更改 ******/

# 刷新缓存
function customize_flush(){
    if(php_sapi_name() === 'cli'){
	return true;
	}else{
        echo(str_repeat(' ',256));
        // check that buffer is actually set before flushing
        if (ob_get_length()){           
            @ob_flush();
            @flush();
            @ob_end_flush();
        }   
        @ob_start();
	}
}

# 随机字串
function rand_char($n=4) { 
    $rand = '';
    for($i = 0;$i < $n;$i++ ){
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $rand .= $chars[mt_rand(1, $base) - 1];
	}
	return $rand;
}

# UTF8转成HTML实体
function utf2html($str){ 
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

# 首页内容
function html(){
	$html  = '<!DOCTYPE html><html><head><title>&#39564;&#35777;&#26381;&#21153;&#22120;</title><meta charset="UTF-8"/>';
	$html .= '<link href="css/drag.css" rel="stylesheet" type="text/css"/>';
	$html .= '<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>';
	$html .= '<script src="js/jquery.touch.js" type="text/javascript"></script>';
	$html .= '<script src="js/drag.js" type="text/javascript"></script>';
	$html .= '</head><body><center><div id="drag"></div>';
	$html .= '<script type="text/javascript">$("#drag").drag();</script>';
	$html .= '</center></body></html>';
	return $html;
}

# 提交表单
function self_form(){
	$form  = '<head><meta charset="UTF-8"></head><body><br><br><br><center>';
	$form .= '<form id="change" action="" method="get">';
	$form .= '密码 <input type="password" id="pw" name="pw" value=""><br>';
	$form .= '网址 <input type="text" id="url" name="url" value="http://"><br><br>';
	$form .= '可以是这样的格式 http://$rand_str.g.cn/$num <br>';
	$form .= '其中<b> .g.cn </b>为主机的 HOST<br>';
	$form .= '<input type="submit" id="button" value="提交">';
	$form .= '</form>';
	$form .= '<a href="./index.php">return back</a></center>';
	return $form;
}

# 改写 .htaccess 的例子
function htaccess(){
	/*
	$htaccess = 'RewriteEngine on'. "\r\n";
	$htaccess .= 'RewriteCond %{HTTP_HOST} ^*.hao369.info$'. "\r\n";
	$htaccess .= 'RewriteCond %{REQUEST_URI} !^/dongtaiwang/'. "\r\n";
	$htaccess .= 'RewriteCond %{REQUEST_FILENAME} !-f'. "\r\n";
	$htaccess .= 'RewriteCond %{REQUEST_FILENAME} !-d'. "\r\n";
	$htaccess .= 'RewriteRule ^(.*)$ /dongtaiwang/$1'. "\r\n";
	$htaccess .= 'RewriteCond %{HTTP_HOST} ^*.hao369.info$'. "\r\n";
	$htaccess .= 'RewriteRule ^(/)?$ dongtaiwang/index.php [L]'. "\r\n";
	
	file_put_contents('.htaccess', $htaccess);
	*/

}

?>