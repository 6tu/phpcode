<?php

$ls = myscandir('D:/shopex/*');

$c = explode("\r\n",$ls); 
$n = count($c);
$cc = '';
for($i = 0 ; $i < $n ; $i++){
if (strpos($c[$i],'.php') != false){
$cc .= $c[$i]."\r\n";
}
}

$c = '';
$c = explode("\r\n",$cc); 
$n = count($c);
for($i = 0 ; $i < $n ; $i++){
$cont = file_get_contents($c[$i]);
if (strpos($cont,'2004072201') != false){
echo $c[$i]."\r\n";
}
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



//************* ทัพไ *************
//$c = file_get_contents($ls);
//echo $ls;
//print_r($c);
//echo 'copy  '.basename($c[$i]).'   '.$c[$i]."\r\n";
//$dir = str_replace('<br/>',"\r\n",$dir);
//echo copy('D:/x/'.basename($c[$i]),$c[$i]);



?>