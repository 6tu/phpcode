<?php
$url = 'http://ys138.win/tools/xampp.tar.gz';
// 获取远程文件的大小
$header_array = get_headers($url, true);
$size = $header_array['Content-Length'];
#echo $size;

// 伪造 IP
$xip='202.103.229.40';
$headers['CLIENT-IP'] = $xip;
$headers['X-FORWARDED-FOR'] = $xip;
$headerArr = array();
foreach($headers as $n => $v){
    $headerArr[] = $n . ':' . $v;
}

$ch=curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
#curl_setopt($ch, CURLOPT_HEADER,false);
curl_setopt($ch, CURLOPT_RANGE,'4200001-6300000');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($ch);

file_put_contents('./x3.tar.gz',$result);
?>

x1.tar.gz x2.tar.gz x3.tar.gz x4.tar.gz x5.tar.gz >xx.tar.gz
