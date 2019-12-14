<?php
/*
Linux 空间文件删除器
作者:朦朧中的罪惡
版本1.0
版权:自由软件,随意传播
####警告####
本软件为空间维护工具,使用完毕之后请立即删除本文件
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>空间文件夹/文件删除工具</title>
<style>
body {font-family:"宋体"; font-size:12px;}
imput { border:1px #ccc solid;}
b { color:#FF0000;}
</style>
</head>
<body>
<form action="?action=dirdel" method="post">
删除文件夹,<b>请确定填写无误后再进行删除操作!</b><br>
请输入文件夹路径,多个文件夹请使用";"隔开
<input type="text" name="all_folder" size="50">
<input type="submit" value="删除">
</form>
<br>
<form action="?action=filedel" method="post">
删除文件,<b>请确定填写无误后再进行删除操作!</b><br>
请输入完整的文件路径,多个文件请使用";"隔开
<input type="text" name="all_files" size="50">
<input type="submit" value="删除">
</form>
<br>
<?php
$action = $_GET['action'];

//删除目录操作
if($action=='dirdel') {
$all_folder = $_POST['all_folder'];
if(!empty($all_folder)) {
  //根据分号识别多个文件夹
  $folders = explode(';',$all_folder);
  if(is_array($folders)) {
   foreach($folders as $folder) {
    deldir($folder);
    echo $folder . '删除成功<Br>';
   }
  }
}
}

if($action=='filedel') {
$all_files = $_POST['all_files'];
if(!empty($all_files)) {
  //根据分号识别多个文件
  $files = explode(';',$all_files);
  if(is_array($files)) {
   foreach($files as $file) {
    if(is_file($file)) {
     if(unlink($file)) {
      echo $file . '删除成功<Br>';
     } else {
      echo $file . '无法删除,请检查权限<Br>';
     }
    } else {
     echo $file . '不存在<br>';
    }
   }
  }
}
}





//删除目录及所包含文件函数
function deldir($dir) {
//打开文件目录
$dh = opendir($dir);
//循环读取文件
while ($file = readdir($dh)) {
  if($file != '.' && $file != '..') {
   $fullpath = $dir . '/' . $file;
   //判断是否为目录
   if(!is_dir($fullpath)) {
    //如果不是,删除该文件
    if(!unlink($fullpath)) {
     echo $fullpath . '无法删除,可能是没有权限!<br>';
    }
   } else {
    //如果是目录,递归本身删除下级目录
    deldir($fullpath);
   }
  }
}
//关闭目录
closedir($dh);
//删除目录
if(rmdir($dir)) {
  return true;
} else {
  return false;
}
}
?>
</body>
</html>