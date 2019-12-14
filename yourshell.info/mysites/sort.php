<?php
set_time_limit(0);
//$str="我喜欢abce你呢";
$str = file_get_contents('http://127.0.0.1/pe');
$array = my_mb_split($str,'utf-8',2);

$key = array_keys($array);
$key2 = shuffle($key);
$n = count($array);

$new_key = '';
$new_str = '';
$new_array = array();
for($i=0; $i < $n; $i++){
$new_key .= $key[$i]. ',';
$new_str .= $array[$key[$i]];
$new_array[$key[$i]] = $array[$key[$i]];
}

echo $new_str."\r\n<br /><br />\r\n\r\n";
//print_r($new_array);



function my_mb_split($str,$charset,$len=1) {
    for($i=0;$i<mb_strlen($str,$charset);$i+=$len)
    {
        $strarr[]=mb_substr($str,$i,$len,$charset);
    }
    return $strarr;
}
?>