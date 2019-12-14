<?
$htmlcode='<html><head>
<title>up files</title></head> 
<body> 
<form enctype="multipart/form-data" action="up.php" method="post"> 
<br>
<input name="file" type="file">   <input type="submit" name="upload" value="上传"> 
</form> 
</body>
</html> ';
@$file=$_POST['file'];
@$file=$_FILES['file']['tmp_name'];
@$file_name=$_FILES['file']['name'];
$store_dir = "up/";
if ($file==""){
echo $htmlcode;
}else{
$accept_overwrite = 1;
if (file_exists($store_dir . $file_name) && !$accept_overwrite) {
Echo   "文件重名，请修改文件名后上传";
exit;
}
if (!move_uploaded_file($file,$store_dir.$file_name)) {
exit;
}
@chmod($file, 0755);
$uf = $_FILES['file'];
$size = (($uf['size'])/1024);
$size=round($size, 2); 
echo  "<a href=up/".$uf['name'] . ">" .$uf['name'] ."</a>&nbsp;&nbsp;(大小:" .$size."KB)&nbsp;&nbsp;上传成功\r\n<br>";
echo $htmlcode;
}
?>