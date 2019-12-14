<?php
set_time_limit(0);
error_reporting(0);
date_default_timezone_set('Asia/Shanghai'); 

$path = dirname(__FILE__).'/pic';
$path = str_replace("\\",'/',$path);
$mydir=dir($path);
while($file=$mydir->read()){
if(strpos(strtolower($file),'.jpg')){
$p=$path.'/'.$file;
$exif = exif_read_data ($p,0,true);
//print_r($exif);

$dto = $exif['EXIF']['DateTimeOriginal'];
$r = ' 拍照日期： ';
$qz = 'JIA';
if ($dto==false) {
$dto = date("Y:m:d H:i:s",$exif['FILE']['FileDateTime']);
$r = ' 修改日期： ';
$qz = 'X';
}
$filename = str_replace(":",'',$dto);
$filename = str_replace(" ",'-',$filename).'.jpg';
rename($path.'/'.$file,$path.'/'.$filename);
$dto = explode(' ',$dto);
$dirname = str_replace(":",'-',$dto[0]);
$dto = $dirname.' '.$dto[1];
$dirname = $qz.$dirname;
if (!file_exists($dirname))  mkdir ($dirname,0777);
copy($path.'/'.$filename,$dirname.'/'.$filename);
unlink($path.'/'.$filename);
echo '文件 '.$p.$r.$dto.' 已处理<br>';

}
}
?>