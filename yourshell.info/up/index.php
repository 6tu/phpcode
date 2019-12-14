<?php
header("content-Type: text/html; charset=gb2312");
$uptypes=array('image/jpg',  //上传文件类型列表
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

$max_file_size=20000000;   //上传文件大小限制, 单位BYTE
$path_parts=pathinfo($_SERVER['PHP_SELF']); //取得当前路径
$destination_folder="../"; //上传文件路径

?>
<html>
<head>
<title>上传文件</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</head>
<body>
<form name="upform" method="post" action="" enctype="multipart/form-data">
<input type=file name="jar_file"> 
<input type=submit name="submit" value="提交"> 
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (!is_uploaded_file(@$_FILES["jar_file"][tmp_name]))
//是否存在文件
{
echo "<font color='red'>文件不存在！</font>";
exit;
}

 $file = $_FILES["upfile"];
 if($max_file_size < $file["size"])
 //检查文件大小
 {
 echo "<font color='red'>文件太大！</font>";
 exit;
  }

if(!in_array($file["type"], $uptypes)){
 echo "<font color='red'>不能上传此类型文件！</font>";
 exit;
}
if(!file_exists($destination_folder))
mkdir($destination_folder);
$filename=@$file["tmp_name"];
$destination = @$destination_folder.@$file["name"].@$ftype;
echo "上传成功";
if (@file_exists($destination) && @$overwrite != true)
{
     echo "<font color='red'>同名文件已经存在了！</a>";
     exit;
  }

 if(!move_uploaded_file ($filename, $destination))
 {
   echo "<font color='red'>移动文件出错！</a>";
     exit;
  }

}
?>
</center>
</body>
</html>