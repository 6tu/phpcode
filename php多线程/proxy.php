
<?php
$url = 'http://www.proxy360.cn/default.aspx';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
curl_setopt($ch, CURLOPT_REFERER, "http://www.163.com/");
curl_setopt($ch, CURLOPT_HEADER, 0);
$proxy = curl_exec($ch);
curl_close($ch);
$out = ob_get_contents();
ob_clean();

// 包含 header 时使用
#$out1 = explode("\r\n\r\n",$out,2);
#$out = $out1[1];

$out2 = explode('<div class="LittleBlockHead" >',$out,2);
$out = $out2[0];
$out3 = explode('<div class="proxylistitem" name="list_proxy_ip">',$out,2);
$out = $out3[1];

$out = str_replace('<input','br+huiche<input',$out);
$out = strip_tags($out);
#$out = strip_tags($out,'<span>');
$out = str_replace("&nbsp;","",$out);
$out = str_replace('更多的免费PROXY地址.....',' ',$out);
$out = str_replace(array("\r\n","\n","\t"),"",$out);
$out = preg_replace('/ +/',' ',$out);
$out = str_replace("br+huiche","<br>\r\n",$out);
echo $out1[0]."\r\n\r\n<pre>\r\n".$out."\r\n";

$ipx = explode("<br>\r\n",$out);
$n = count($ipx);
$proxy = '';
for($i = 0; $i < $n; $i++){
$ip = explode(" ",$ipx[$i]);
if(array_key_exists('2', $ip)) $proxy .= $ip[1].':'.$ip[2]."\r\n";
}
echo $proxy;
?>
