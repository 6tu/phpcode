<?php
function getpage($url){
$url  = @parse_url($url);
$temp = '';

if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
$fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
if (!$fp) {
$http_code = '0';
echo "$errstr ($errno)<br />\n";
}else{
$out = "GET $url[path] HTTP/1.1\r\n";
$out .= "Host: $url[host]\r\n";
$out .= "Connection: Close\r\n\r\n";
fwrite($fp, $out);
while (!feof($fp)) {
$temp .= fgets($fp, 128);
}
fclose($fp);
$http_code = substr($temp,9,3);
$temp = explode("\r\n\r\n",$temp,2);
$temp = $temp[1];
}
}elseif(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$temp = curl_exec ($ch);
$http_code = getpageinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);
}else{
$http_code = '0';
$temp = 'the hosting do not support curl or fsockopen() ';
}
if ($http_code >= 400) { //400 - 600都是服务器错误
return 'false';
echo $temp;
exit(0);
}else{
return $temp;
}
}

function match_links($document){
preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $document, $links);
while(list($key, $val) = each($links[2])){
if(!empty($val))
$match[] = $val;
}
while(list($key, $val) = each($links[3])){
if(!empty($val))
$match[] = $val;
}
return array($match, $links[4]); //
}

// -----------------main--------------------//
$dtw_url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');

    header("Content-type: text/html; charset=GBK");
    $dtw_url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');
    $dtw_temp = getpage($dtw_url);
	if ($dtw_temp == 'false'){
        echo $dtw_temp;
	exit;
	}
	
    $dtw_temp_ip = explode('<div id="content_list_right1">', $dtw_temp);
    $dtw_temp_ip = @$dtw_temp_ip[1] . @$dtw_temp_ip[2];
    $dtw_temp_ip = str_replace('http://', 'https://', $dtw_temp_ip);
    $dtw_ip = match_links($dtw_temp_ip);
    $dtwip = '';
    foreach (@$dtw_ip[0] as $key => $value){
        $dtwip .= "\r\n<br />" . '<a href="' . $value . '">' . $value . '</a>';
        }
    $dtw_ip_n = count(@$dtw_ip[0]);
    
print<<<JS1
<html><head>
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<script language="javascript" >
i=1
var autourl=new Array()

JS1;
    
    for($i = 0 ; $i < $dtw_ip_n ; $i++){
        $j = $i + 1;
        $dtw_ip[0][$i] = str_replace("//", '\\', $dtw_ip[0][$i]);
        $dtw_ip[0][$i] = str_replace("/", '', $dtw_ip[0][$i]);
        $dtw_ip[0][$i] = str_replace('\\', '//', $dtw_ip[0][$i]);
        echo "autourl[$j]=\"" . $dtw_ip[0][$i] . "\"\r\n";
        }
    
print<<<JS2
function auto(url)
{
if(i)
{
i=0;
top.location=url
}}
function run()
{
for(var i=1;
i<autourl.length;i++)
document.write("<img src="+autourl[i]+" width=1 height=1 onerror=auto('"+autourl[i]+"')>") 
}
run() 
</script>
JS2;
    
    echo '</head><title>选择服务器</title><body>'; // . $link;
    echo '</h3>正在选择服务器，请稍等</h3>';
    echo '<br /> <br />出现证书提示点“<b>是</b>”，可能连续点几次';
    echo '</body></html>';
    exit(0);


?> 



