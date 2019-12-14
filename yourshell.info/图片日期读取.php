<?php
set_time_limit(0);
date_default_timezone_set('Asia/Chongqing'); 
$ls = myscandir('f:/m/*');
$c = explode("\r\n",$ls); 
$n = count($c);

for($i = 0 ; $i < $n ; $i++){
//if(file_exists($c[$i])) 

//$md5 = md5_file($c[$i]); 


$img = $c[$i];
    $exif = exif_read_data ($img,0,true);
    $new_img_info = array (
    "文件信息" => "-----------------------------",
    "文件名" => $exif['FILE']['FileName'],
	"修改时间" => $exif['FILE']['FileDateTime'],
    "拍摄时间" =>  $exif['IFD0']['DateTime']
    );
$time2 = date('Ymd-His', $exif['FILE']['FileDateTime']); 
$time1 = str_replace(':','',$exif['IFD0']['DateTime']);
$time1 = str_replace(' ','-',$time1); //拍摄时间




 
$path = dirname($c[$i]);
$fn = basename($c[$i]);
$newfn = $path.'/'.$time1.'.jpg';
rename($c[$i],$newfn);
}

function myscandir($pathname){
$dir = '';
foreach( glob($pathname) as $filename ){
if(is_dir($filename)){
$dir .= myscandir($filename.'/*');
}else{
$dir .= $filename."\r\n";
}
}
return $dir;
}

?>