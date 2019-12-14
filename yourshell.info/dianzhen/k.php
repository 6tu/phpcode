<?php
$n=3;
$k = '';
for($i = 1;$i < 4;$i++){
for($j = 0;$j < 3;$j++){
//$k .= $j * $n + $i .',';
$k .= $j * $n + $i .',';
}
}
echo $k;
$var=explode(",",$k);
print_r($var);
?>