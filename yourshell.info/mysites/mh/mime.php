<?php
function fmime($filename){
$fen=substr($filename,strrpos($filename,".")+1);
$file = file("./mime.txt");
$n=count($file);
for($i = 0;$i < $n;$i++){
$line = explode(" ", $file[$i]);
if($line[1]===$fen){
$mime = $line[0];
break;
}else{
$mime = 'application/octet-stream';
}
}
echo $mime;
}
$filename ='www/test/www.zip';
fmime($filename);
?>