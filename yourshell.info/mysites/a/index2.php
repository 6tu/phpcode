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
$os = $code->getOS();//����ϵͳ��
$browse = $code->getBrowse();

//��AJAX �������������н���
if(isset($_POST['encode']) && $_POST['encode']=='base64'){
$m=base64_decode($m);
if(extension_loaded('mbstring')){
$m = mb_convert_encoding($m,'GBK','UTF-8');
}else {
$m = iconv('UTF-8','GB2312//IGNORE//TRANSLIT',$m);
}
$screen = utf8tohtml("��Ů + ˧��");
}else if(isset($_POST['mobile']) && $_POST['mobile']=='mobile'){
echo mobile_form();
$os = '�ֻ�';
$browse = "����";
$screen = "˧�� X ��Ů";
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
//echo "���û�е㣬������λΪ�㣬����������������ѯϵͳ"."\r\n<br />\r\n";
$d = telid($m);
echo '-------------------------------------------<br />';
	if(is_array($d)){
echo '<table>';
		echo utf8tohtml('<tr><td align="right">��ѯ����Ϊ��</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  �������ţ�</td><td align="left">').utf8tohtml($d['1']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">��������Ϊ��</td><td align="left">').utf8tohtml($d['2']).'</td></tr>';
		echo '</table>';
	}else{
		echo utf8tohtml('�ÿ��ſ��ܲ�����! SORRY!<br />');
		echo utf8tohtml("�������û����¼���ѯ������<a href='./message.php '> ����</a>��ϵ��лл<br />");
	}
	echo '-------------------------------------------<br />';

}else if($_dot===false and !$_one==0 and $l > 6 and $l <= 12 and $_numeric==1){
//echo "���û�е㣬������λ��Ϊ�㣬����С��ʮ��λ�������ֻ������ѯϵͳ"."\r\n<br />\r\n";
	$d = mobile_data($m);
	echo '-------------------------------------------<br />';
	if(is_array($d)){
echo '<table>';
		echo utf8tohtml('<tr><td align="right">��ѯ����Ϊ��</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  �������ţ�</td><td align="left">').utf8tohtml($d['1']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">��������Ϊ��</td><td align="left">').utf8tohtml($d['2']).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">��ԭʼ���ͣ�</td><td align="left">').utf8tohtml($d['3']).'</td></tr>';
		echo '</table>';
	}else{
		echo utf8tohtml('�ÿ��ſ��ܲ�����! SORRY!<br />');
		echo utf8tohtml("�������û����¼���ѯ������<a href='./message.php '> ����</a>��ϵ��лл<br />");
	}
	echo '-------------------------------------------<br />';


}else if($_dot===false and !$_one==0 and $l > 12 and  $_numeric==1){
//echo "���û�е㣬������λ��Ϊ�㣬���ȴ���ʮ��λ����ô�������֤��ѯϵͳ";
		$id=new IDCheck($m);

                $key = @$id->GetKey();
		if(($id=$id->Part())==False)
		{
		
echo '-------------------------------------------<br />';
echo utf8tohtml('���֤���� '.$cm.'��Ч��');
if($l==18){
$m=substr($cm,0,17).$key;
echo utf8tohtml('���һλӦ���� <b>').$key .'<b><br />';
echo $m.'<b><br />';
}
echo '<br />-------------------------------------------<br />';
		}
		else
		{
			echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">���֤���룺</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">��֤�ص㣺</td><td align="left">').utf8tohtml($id[0]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">����ʱ�䣺</td><td align="left">').utf8tohtml($id[1]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�Ա�</td><td align="left">').utf8tohtml($id[2]).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';
		
		}



}else if($_dot > 0 ){
//echo "����е㣬����IP��ѯϵͳ,<br /><br />";
$ipobj=new ipLocation();
$ip=$ipobj->getIP();
$addr=$ipobj->getaddress($ip);
$address=$ipobj->getaddress($m);
$ipobj=NULL;
	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">��ѯIP/������</td><td align="left">').$cm.'</td></tr>';
                echo utf8tohtml('<tr><td align="right">������A��¼��</td><td align="left">').gethostbyname($m).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">  �����أ�</td><td align="left">').utf8tohtml($address["area1"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�����ض���</td><td align="left">').utf8tohtml($address["area2"]).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';


	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">����IP��</td><td align="left">').$ip.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�����أ�</td><td align="left">').utf8tohtml($addr["area1"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�����ض���</td><td align="left">').utf8tohtml($addr["area2"]).'</td></tr>';
		echo utf8tohtml('<tr><td align="right">����ϵͳ��</td><td align="left">').$os.'</td></tr>';
	        echo utf8tohtml('<tr><td align="right">�������</td><td align="left">').$browse.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�ֱ��ʣ�</td><td align="left">').$screen.'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';

}else if ($_dot===false and $l ==6 and $_numeric==1){ 
//echo "���û�е㣬����Ϊ6�������ۺϲ�ѯϵͳ"."\r\n<br />\r\n";
$post=postcode_data($m);

if($post == ""){
echo utf8tohtml('û�й����ĵ�ַ!<br /><br />');
}else{

	echo '-------------------------------------------<br />';
echo '<table>';
		echo utf8tohtml('<tr><td align="right">�����������룺</td><td align="left">').$cm.'</td></tr>';
		echo utf8tohtml('<tr><td align="right">�����أ�</td><td align="left">').utf8tohtml($post).'</td></tr>';
		echo '</table>';

	echo '-------------------------------------------<br />';
}

}else{
echo "\r\n<br />";
echo $cm."\r\n<br /><br />";
echo utf8tohtml("�ύ����������\r\n<br />�����Ǻ��֣��Ҳ���С��6�ֽ�<br />");
echo utf8tohtml("�������û����¼���ѯ������<a href='./message.php '> ����</a>��ϵ��лл<br />");
}

}

echo "<br /><br />\n";
echo utf8tohtml('�ͻ��������Ϣ:<br /><small>');
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