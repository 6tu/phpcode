bshellz 2009-09-26<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
 <HEAD>
  <TITLE>File upload</TITLE>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
 </HEAD>

 <BODY>
  <form name="form" method="POST" action="./<?php echo basename($_SERVER['SCRIPT_NAME']);?>" 

enctype="multipart/form-data">
  <input type="file" name="file">
  <input type="submit" name="btnUpload" value="upload">
  </form>
 </BODY>
</HTML>

<?php

set_time_limit(0);
$up_dir='upload';
if(is_dir($up_dir)==false){
mkdir ($up_dir,0777);
}

//$up_dir='/home/h/hahayile/html/jyg';

$allowedExtensions = array("txt", "rtf", "doc", "rar", "exe", "zip", "7z", "php", "jpg","html","ini", "inc");
echo '许可的后缀    '.implode(",",$allowedExtensions).'<br><br>';
    if(!empty($_FILES['file'])){
        $file = $_FILES['file'];
        if($file['error'] == UPLOAD_ERR_OK) {
            if(in_array(end(explode(".", $file['name'])), $allowedExtensions)) {
                $name = $file['name'];
                $size = round($file['size']/1024,2);
                move_uploaded_file($file['tmp_name'],"$up_dir/$name");
                @chmod("$up_dir/$name", 0755 );
		echo $name ." (".$size."KB) 已上载成功！".'<br><br>';
            } else {
                echo "不被许可的文件种类".$file['name'];
            }
        } else die("不能上传");
    }

echo '<pre>';
if(@$cmd=$_GET["cmd"] & $cmd='del' & @$fname=$_GET["name"] ){
@unlink("$fname");
}
function view_dir($dir)
{
$dp=opendir($dir); //打开目录句柄
//echo "<br>".$dir."<br><br>"; 
while ($file = readdir($dp)) //遍历目录
{
   if ($file !='.'&&$file !='..') //如果文件不是当前目录及父目录
   {    
    $path=$dir."/".$file; //获取路径
    if(is_dir($path)) //如果当前文件为目录
    {
     view_dir($path);   //递归调用
    }
    else   //如果不是目录
    {
     echo '<a href="'.$path.'">'.$path.'</a>              '; //输出文件名
     echo '<a href='.$_SERVER['SCRIPT_NAME'].'?cmd=del&name='.$path.'>删除</a><br>'; //输出文件名
    }
   }
}
closedir($dp);
} 
echo view_dir($up_dir).'</pre>';
?>