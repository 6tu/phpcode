<?php
header("content-Type: text/html; charset=gb2312");
$uptypes=array('image/jpg',  //�ϴ��ļ������б�
 'image/jpeg',
 'image/png',
 'image/pjpeg',
 'image/gif',
 'image/bmp',
 'application/x-shockwave-flash',
 'image/x-png',
 'application/msword',
 'audio/x-ms-wma',
 'audio/mp3',
 'application/vnd.rn-realmedia',
 'application/x-zip-compressed',
 'application/octet-stream');

$max_file_size=20000000;   //�ϴ��ļ���С����, ��λBYTE
$path_parts=pathinfo($_SERVER['PHP_SELF']); //ȡ�õ�ǰ·��
$destination_folder="../"; //�ϴ��ļ�·��

?>
<html>
<head>
<title>�ϴ��ļ�</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</head>
<body>
<form name="upform" method="post" action="" enctype="multipart/form-data">
<input type=file name="jar_file"> 
<input type=submit name="submit" value="�ύ"> 
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (!is_uploaded_file(@$_FILES["jar_file"][tmp_name]))
//�Ƿ�����ļ�
{
echo "<font color='red'>�ļ������ڣ�</font>";
exit;
}

 $file = $_FILES["upfile"];
 if($max_file_size < $file["size"])
 //����ļ���С
 {
 echo "<font color='red'>�ļ�̫��</font>";
 exit;
  }

if(!in_array($file["type"], $uptypes)){
 echo "<font color='red'>�����ϴ��������ļ���</font>";
 exit;
}
if(!file_exists($destination_folder))
mkdir($destination_folder);
$filename=@$file["tmp_name"];
$destination = @$destination_folder.@$file["name"].@$ftype;
echo "�ϴ��ɹ�";
if (@file_exists($destination) && @$overwrite != true)
{
     echo "<font color='red'>ͬ���ļ��Ѿ������ˣ�</a>";
     exit;
  }

 if(!move_uploaded_file ($filename, $destination))
 {
   echo "<font color='red'>�ƶ��ļ�����</a>";
     exit;
  }

}
?>
</center>
</body>
</html>