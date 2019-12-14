<?php
//* 删除相同的行 20110524 */

$file=file_get_contents('2011.txt');
$array_file = explode("\r\n",$file);
/*
$n=count($array_file);
for($i = 0; $i < $n ; $i++){
$file = str_replace($array_file[$i],"",$file);
$file .= $array_file[$i]."\r\n";
}
# 消除空行
$array_file = explode("\r\n",$file);
$n=count($array_file);
$new_file = '';
for($i = 0; $i < $n ; $i++){
if(strlen($array_file[$i]) > 0){
$new_file .=$array_file[$i]."\r\n";
}else{
continue;
}
}
file_put_contents('x2011.txt',$new_file );
echo $new_file;
*/

//* 用数组函数操作，更简单 */

$_file = array_values(array_unique($array_file));
$new_file='';
foreach($_file as $value){
$new_file .= $value."\r\n";
}
file_put_contents('x2011.txt',$new_file);
echo $new_file;

?>
