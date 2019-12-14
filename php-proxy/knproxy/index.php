<?php
require_once('includes/module_parser.php');
require_once('includes/module_encoder.php');
require_once('includes/module_url.php');
require_once('includes/module_http.php');
require_once('includes/general_functions.php');

$knEncoder = new knEncoder();
if(!isset($_GET['url']) || $_GET['url']==''){
	include('index.inc.php');
	exit();
}
$url = $_GET['url'];
if(isset($_GET['encrypt_key'])){
	$key = (int)$_GET['encrypt_key'];
	$knEncoder->setKey($key);
}

if(!preg_match('~/~',$url))
	$url = $knEncoder->decode($url);
$knEncoder->setKey(0);
$url = checkHttpUrl($url);
	$_HOST = $_SERVER['HTTP_HOST'];
	if(strtolower(substr($_SERVER['HTTP_HOST'],0,4))!='http')
		$_HOST = 'http://' . $_SERVER['HTTP_HOST'];
	$_SCRIPT =$_HOST . $_SERVER['SCRIPT_NAME'];

if(isset($_POST['force_http']) || isset($_COOKIE['knprox_force_unsecure']) && $_COOKIE['knprox_force_unsecure']=='true'){
	$url = preg_replace('~^https:~iUs','http:',$url);
	if(!isset($_COOKIE['knprox_force_unsecure']))
		setcookie('knprox_force_unsecure','true',0);
}

$knURL = new knUrl();
$knURL->setBaseurl($url);

$knHTTP = new knHttp($url);
$knHTTP->setPost($_POST);
$knHTTP->setCookies($_COOKIE);
if(!empty($_POST['knUSER']) || isset($_COOKIE['__knLogin'])){
	if($_POST['knUSER']==''){
		$pas=explode('/',$url);
		unset($pas[count($pas)-1]);
		$url_base = strtolower(implode('/',$pas));
		$a=explode('|',$_COOKIE['__knLogin']);
		foreach($a as $vak){
			$uK = explode('#',$vak);
			if($uK[0]!='' && strpos($url_base,$uK[0])===0){
				$knHTTP->httpauth=$uK[1];
				break;
			}
		}
	}else{
		$knHTTP->setLogin($_POST['knUSER'],$_POST['knPASS']);
		$pas=explode('/',$url);
		unset($pas[count($pas)-1]);
		$url_base = strtolower(implode('/',$pas));
		$a=explode('|',$_COOKIE['__knLogin']);
		$a[]=$url_base . '#' . $knHTTP->httpauth;
		setcookie('__knLogin',implode('|',$a),2147364748);
	}
}
$knHTTP->send();
if($knHTTP->is_secure==true && (!isset($_COOKIE['knprox_ssl_warning']) || $_COOKIE['knprox_ssl_warning']!='off') && !isset($_POST['yes'])){
	include_once('includes/gui_notice.php');exit();
}
elseif($knHTTP->is_secure && isset($_POST['yes'])){
	setcookie('knprox_ssl_warning','off',2147483647);
}
$headers = $knHTTP->parseHeader();
if((isset($_GET['debug']) && $_GET['debug']=='true') || (isset($_COOKIE['knprox_syst_debug']) && $_COOKIE['knprox_syst_debug']=='true')){
	$eobj=Array('status'=>1994);//AUTOMATICALLY RE ENABLE SSL WARNINGS
	setcookie('knprox_ssl_warning','on',0);
	setcookie('knprox_force_unsecure','false',0);
	setcookie('knLogin','',-1);
	if(isset($_GET['set_debug_cookie'])){
		setcookie('knprox_syst_debug','true',2147483647);
	}
	if(isset($_GET['clear_cookies']) && $_GET['clear_cookies']=='true'){
		foreach($_COOKIE as $key=>$val){
			setcookie($key,'__',-1);
		}
	}
	include('includes/gui_error.php');
	exit();
}
if($headers['status']==401){
	//UNAUTH
	$realm = $headers['www-authenticate-realm'];
	include('includes/gui_httpauth.php');
	exit();
}
header('HTTP/1.1 ' . $headers['status']);
if(((int)$headers['status']>400 && (int)$headers['status']!=404)|| (int)$headers['status']<1){
	$eobj=Array('status'=>$headers['status']);
	include('includes/gui_error.php');
	exit();
}
header('Content-Type: ' . $knHTTP->doctype);
if(isset($headers['location']) && $headers['location']!=''){
	$url = $knURL->getAbsolute($headers['location']);
	$knurl = $knEncoder->encode($url);
	if($_GET['enp']=='true')
		$nURL = basename(__FILE__) . "?enp=true&url=" . $knurl;
	else
		$nURL = basename(__FILE__) . "?url=" . $knurl;
	header('Location: ' . $nURL );
}
if(isset($headers['cookies']) && is_array($headers['cookies']))
foreach($headers['cookies'] as $key=>$value){
	setcookie($key,$value,2147483647);
}
$knParser = new knParser($knURL,$knHTTP->content,$_SCRIPT . '?url=');
$knParser->setMimeType($knHTTP->doctype);
$knParser->setEncoder($knEncoder);


if(isset($_GET['enp']) && $_GET['enp'] =='true'){
	if($knParser->type=='text/html' || $knParser->type==''){
		$knParser->url_prefix = '?enp=true&url=';
		echo '<script language="javascript" type="text/javascript" src="js/denjihou.js"></script>';
		echo '<script language="javascript" type="text/javascript" src="js/utf.js"></script>';
		echo '<script language="javascript" type="text/javascript">';
		$knParser->set_value('use_page_encryption',true);
		$knParser->parse();
		$key = $knParser->get_value('key','');
		echo 'knEncode.setxmkey("' . $key . '");' . "\n";
		echo 'knEncode.charset="'. $knParser->getCharset($knHTTP->doctype,substr($knHTTP->content,0,1000)) .'";' . "\n";
		echo 'var page = knEncode.decode("' . $knParser->output . '");' . "\n";
		echo 'document.write(page.toUTF16());' . "\n";
		echo '</script>';
		exit();
	}
}
$knParser->parse();
echo $knParser->output;
?>