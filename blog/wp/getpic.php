<?php
#http://zh-cn.shenyun.com/news/view/article/e/DfnBrZFrRsk/?photo=11

$tags = 'article/e/DfnBrZFrRsk/?photo=';
$num = 10 ;

$base_url = 'http://zh-cn.shenyun.com/blog/view/';
$url = $base_url . $tags;

for($i = 0; $i < $num ; $i++){
	$imgurl = $url . $i;
	echo $imgurl . "<br>\r\n";
	
	
	//$pic = get_pic($imgurl);
	//echo $pic . "<br>\r\n";
}

function get_pic($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);
	$url = obtain_image_url($result);
    return $url;
}

function obtain_image_url($str){
	$result = strip_tags($str,'<meta>');
	$array = explode("\n", $result);
	$n = count($array);
	$meta = '';
	for($i = 0; $i < $n ; $i++){
		if(strstr($array[$i], '<meta')) $meta .= trim($array[$i]) . "\r\n";
	}
	unset($result);
	unset($array);
	$array = explode("\n", $meta);
	$n = count($array);
	$image = '';
	for($i = 0; $i < $n ; $i++){
		if(strstr($array[$i], 'image')) $image .= trim($array[$i]) . "\n";
	}
	$url = explode('"', $image);
	return $url[3];
}
?>




