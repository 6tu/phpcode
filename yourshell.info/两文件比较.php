<?php
$p7=file('2007.php');
$p8=file('2008.php'); //╢Снд╪Ч
$n7=count($p7);
$n8=count($p8);
$line='';
for($i =0 ; $i < $n8 ;$i++){
if(!in_array($p8[$i],$p7)){
echo ($i+1).' '.$p8[$i]."\r\n<br>";
}
}
?>