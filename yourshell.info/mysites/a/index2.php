<?php
header("content-type:text/html; charset=GBK");
include('./include/common.php');
if(!isset($_POST['a'])){
echo form();
exit;
}


if(isset($_POST['a']) && $_POST['a'] == 'search'){
$m=$_POST['m'];

$code = new clientGetObj; 
$os = $code->getOS();//操作系统：
$browse = $code->getBrowse();

//含AJAX 符合条件，进行解码
if(isset($_POST['encode']) && $_POST['encode']=='base64'){
$m=base64_decode($m);
if(extension_loaded('mbstring')){
$m = mb_convert_encoding($m,'GBK','UTF-8');
}else {
$m = iconv('UTF-8','GB2312//IGNORE//TRANSLIT',$m);
}
$screen = utf8tohtml("美女 + 帅哥");
}else if(isset($_POST['mobile']) && $_POST['mobile']=='mobile'){
echo mobile_form();
$os = '手机';
$browse = "摸吧";
$screen = "帅哥 X 美女";
}else {
echo form();
$screen = getScreen();
}



$m=htmlspecialchars(addslashes(trim(ltrim(strtolower($m)))));
$cm=utf8tohtml($m);
$l=strlen($m);
$_dot=strpos($m,'.');
$_one=substr($m,0,1);
$_numeric=is_numeric($m);
if ($l > 12){
$idx=substr($m,0,$l-1);
$_numeric=is_numeric($idx);
}

//echo $m.";&#38388;\r<br />\r\n";
//echo $l."\r\n<br />\r\n";

//echo $_SERVER['HTTP_USER_AGENT'];


if ($_dot===false and $_one==0 and $l > 6 and $l < 12 and $_numeric==1){ 
//echo "如果没有点，而且首位为零，进入电信坐机号码查询系统"."\r\n<br />\r\n";
$d = telid($m);
echo '-------------------------------------------<br />';
	if(is_array($d)){
echo '<table>';
		echo utf8tohtml('<tr><td align="right">查询号码为：</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  所属区号：</td><td align="left">').utf8tohtml($d['1']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">　　城市为：</td><td align="left">').utf8tohtml($d['2']).'</td></tr>';
		echo '</table>';
	}else{
		echo utf8tohtml('该卡号可能不存在! SORRY!<br />');
		echo utf8tohtml("如果数据没有收录或查询错误，请<a href='./message.php '> 留言</a>联系，谢谢<br />");
	}
	echo '-------------------------------------------<br />';

}else if($_dot===false and !$_one==0 and $l > 6 and $l <= 12 and $_numeric==1){
//echo "如果没有点，而且首位不为零，长度小于十二位，进入手机号码查询系统"."\r\n<br />\r\n";
	$d = mobile_data($m);
	echo '-------------------------------------------<br />';
	if(is_array($d)){
echo '<table>';
		echo utf8tohtml('<tr><td align="right">查询号码为：</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  所属区号：</td><td align="left">').utf8tohtml($d['1']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">　　城市为：</td><td align="left">').utf8tohtml($d['2']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">卡原始类型：</td><td align="left">').utf8tohtml($d['3']).'</td></tr>';
		echo '</table>';
	}else{
		echo utf8tohtml('该卡号可能不存在! SORRY!<br />');
		echo utf8tohtml("如果数据没有收录或查询错误，请<a href='./message.php '> 留言</a>联系，谢谢<br />");
	}
	echo '-------------------------------------------<br />';


}else if($_dot===false and !$_one==0 and $l > 12 and  $_numeric==1){
//echo "如果没有点，而且首位不为零，长度大于十二位，那么进入身份证查询系统";
		$id=new IDCheck($m);

                $key = @$id->GetKey();
		if(($id=$id->Part())==False)
		{
		
echo '-------------------------------------------<br />';
echo utf8tohtml('身份证号码 '.$cm.'无效，');
if($l==18){
$m=substr($cm,0,17).$key;
echo utf8tohtml('最后一位应该是 <b>').$key .'<b><br />';
echo $m.'<b><br />';
}
echo '<br />-------------------------------------------<br />';
		}
		else
		{
			echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">身份证号码：</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">发证地点：</td><td align="left">').utf8tohtml($id[0]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">出生时间：</td><td align="left">').utf8tohtml($id[1]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">性别：</td><td align="left">').utf8tohtml($id[2]).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';
		
		}



}else if($_dot > 0 ){
//echo "如果有点，进入IP查询系统,<br /><br />";
$ipobj=new ipLocation();
$ip=$ipobj->getIP();
$addr=$ipobj->getaddress($ip);
$address=$ipobj->getaddress($m);
$ipobj=NULL;
	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">查询IP/域名：</td><td align="left">').$cm.'</td></tr>';
                echo utf8tohtml('<tr><td align="right">域名的A记录：</td><td align="left">').gethostbyname($m).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  所属地：</td><td align="left">').utf8tohtml($address["area1"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">所属地二：</td><td align="left">').utf8tohtml($address["area2"]).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';


	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">您的IP：</td><td align="left">').$ip.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">所属地：</td><td align="left">').utf8tohtml($addr["area1"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">所属地二：</td><td align="left">').utf8tohtml($addr["area2"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">操作系统：</td><td align="left">').$os.'</td></tr>';
	        echo utf8tohtml('<tr><td align="right">浏览器：</td><td align="left">').$browse.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">分辨率：</td><td align="left">').$screen.'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';

}else if ($_dot===false and $l ==6 and $_numeric==1){ 
//echo "如果没有点，长度为6，进入综合查询系统"."\r\n<br />\r\n";
$post=postcode_data($m);

if($post == ""){
echo utf8tohtml('没有关联的地址!<br /><br />');
}else{

	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">您的邮政编码：</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">所属地：</td><td align="left">').utf8tohtml($post).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';
}

}else{
echo "\r\n<br />";
echo $cm."\r\n<br /><br />";
echo utf8tohtml("提交的数据有误\r\n<br />不能是汉字，且不能小于6字节<br />");
echo utf8tohtml("如果数据没有收录或查询错误，请<a href='./message.php '> 留言</a>联系，谢谢<br />");
}

}

echo "<br /><br />\n";
echo utf8tohtml('客户机相关信息:<br /><small>');
echo 'REMOTE_ADDR =>'.$_SERVER['REMOTE_ADDR']."\r\n<br />";
foreach (apache_request_headers() as $key => $value){
echo "$key => $value <br />";
}
foreach (apache_response_headers() as $key => $value){
echo "$key => $value <br />";
}
echo "<br /></small>";


if(isset($_POST['mobile']) && $_POST['mobile']=='mobile'){
echo '</card></wml>'; 
}

?>