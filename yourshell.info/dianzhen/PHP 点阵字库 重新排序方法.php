
PHP 点阵字库 重新排序方法（147258369）



$row = 12;         //行数
$col = 5 ;         //列数
$str2arr=str_split($dot_string,$row);
$n = count($str2arr) / $row ;
$k = '';
for($i = 0;$i < $row;$i++){
for($j = 0;$j < $col;$j++){
$x= $j * $row + $i ;
$k .= $str2arr[$x] ;
}
}
$k = $k;
$dot_string=implode(str_split($k,count($str2arr)),"<br>");
echo $dot_string;
