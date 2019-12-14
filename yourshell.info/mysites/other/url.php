<?php
//print_r($_REQUEST);
header("content-type:text/html; charset=UTF-8");


$info=$_REQUEST;
foreach($info as $key=>$str){
echo $key.'=>'.$str."\r\n<br>";
}


?>