<?php
$password = "A5dns" ;	// �������룬����ʱ��Ҫ�����룬һ��Ҫ�޸ĵ������ⲻ��ȫ��

//////  �����������򣬲����޸�  ////////////////////////////////////////////////////////
echo '<style>'
	. 'body{font-family:Verdana; font-size:12px; background-color:#fcfcfc}'
	. 'input{font:12px Tahoma} '
	. '</style>';

if ( $password == "isphp" )
{
	echo "<h3 align=center>��û���޸Ĺ������룬Ϊ���ⲻ��ȫ�����޸ĳ�������!</h3>";
	echo "<center>�޸ķ������£�<br>"
		. '�ü��±��� ���ļ�(rm.php), ���ڶ��е� <font color=red>$password = "isphp" </font> �е� isphp �ĳ�����Ҫ������, ���ϴ���������</center>';
	exit;
}

if ( !IsSet($HTTP_POST_VARS['dirname']) ) 
{
	$self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $PHP_SELF;
	echo "<form action=\"{$self}\" method=post name=delform><br><center><font color=red>������ɾ�����ļ����ļ���</font>: <input name=dirname style=\"color:red\"> "
		. "����: <input type=password name=pass size=6> <input type=submit value=\"ȷ��\"></center></form><br><center><br><br>ע������Ĭ������Ϊ��<font color=red>A5dns</font>��Ϊ��ȫ�������޸ģ�����ʹ�ú�ɾ���˳���<br><br><br><br><br>�� �ļ���Ŀ¼����ɾ������ɾ������Ŀ¼��ɾ����̵��޷���FTPɾ����Ŀ¼���ļ�<br><br><center>(ע����ͬĿ¼�µ������ļ���Ŀ¼һ��ɾ��)����ʱ����FTP�������ļ���Ŀ¼777Ȩ�޺���ɾ����<br><br><br>�˳����� <a href=http://www.A5dns.com>��ѿռ�</a> <a href=http://www.A5dns.com>www.A5dns.com</a> �����ռ���<br><br>"; 
	echo " <SCRIPT> document.delform.dirname.focus() </SCRIPT> ";
}
else 
{
	if ( $password != $HTTP_POST_VARS['pass'] )
	{
		exit("<script>alert('����Ĺ���Ա����, �޷����������� ������������룬�����ڱ��ļ��ĵڶ��в鵽����!');</script>");
	}
	$dir_name = $HTTP_POST_VARS['dirname'];
	if ( is_file( $dir_name ) )
	{
        	if ( unlink($dir_name) )		echo "Del file \"$dirname\" successfully!<br>"; 
                else				echo "<FONT COLOR=RED>Fail to del file \"$dirname\"!</FONT><br>"; 
	}
	else
	{
        	CleanDir($dir_name);
			if ( rmdir($dir_name) )		echo "Remove dir \"$dirname\" successfully!<br>";
            	else                 		echo "<FONT COLOR=RED>Fail to Remove dir \"$dirname\"!</FONT><br>"; 
	}
} 

function CleanDir($dir)
{
	$handle=opendir($dir);
	while ( $file=readdir($handle) )
	{
		if ( ($file==".") || ($file=="..") ) continue;
		if ( is_dir("$dir/$file") )
		{
			CleanDir("$dir/$file");
			if ( rmdir("$dir/$file") )	echo "Remove dir \"$dir/$file\" successfully!<br>";
                        else				echo "<FONT COLOR=RED>Fail to Remove dir \"$dir/$file\"!</FONT><br>"; 
		} 
		else      
		{ 
			if ( unlink("$dir/$file") )     echo "Del file \"$dir/$file\" successfully!<br>"; 
			else                 		echo "<FONT COLOR=RED>Fail to del file \"$dir/$file\"!</FONT><br>";  
		}
	}
	closedir($handle);
}

?> 


<title>PHP�ļ���Ŀ¼����ɾ��������ѿռ�,www.A5dns.com ��ѹ���ռ�,PHP����ɾ������</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta name="keywords" content="��ѿռ�,��������,A5dns,����ɾ������" >