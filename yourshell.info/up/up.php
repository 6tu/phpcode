<?
$htmlcode='<html><head>
<title>up files</title></head> 
<body> 
<form enctype="multipart/form-data" action="up.php" method="post"> 
<br>
<input name="file" type="file">   <input type="submit" name="upload" value="�ϴ�"> 
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
Echo   "�ļ����������޸��ļ������ϴ�";
exit;
}
if (!move_uploaded_file($file,$store_dir.$file_name)) {
exit;
}
@chmod($file, 0755);
$uf = $_FILES['file'];
$size = (($uf['size'])/1024);
$size=round($size, 2); 
echo  "<a href=up/".$uf['name'] . ">" .$uf['name'] ."</a>&nbsp;&nbsp;(��С:" .$size."KB)&nbsp;&nbsp;�ϴ��ɹ�\r\n<br>";
echo $htmlcode;
}
?>