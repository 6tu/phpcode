<?php
function getpage($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $temp = curl_exec ($ch);
    curl_close ($ch);
    if (strlen($temp) < 420){
        echo "获取数据失败，所在的服务器不可用";
        exit(0);
        }
    return $temp;
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
header("Content-type: text/html; charset=GBK");
$url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');
//$url = 'http://127.0.0.1/p.html';
$temp = getpage($url);
$temp_proxy = explode('<div id="content_list_right1">', $temp);
$temp_proxy = $temp_proxy[1] . $temp_proxy[2];
$temp_proxy = str_replace('http://', '', $temp_proxy);
$proxy = match_links($temp_proxy);
$link = '';
foreach ($proxy[0] as $key => $value){
    $value = 'https://'.gethostbyname($value);
    $link .= "\r\n<br />" . '<a href="' . $value . '">' . $value . '</a>';
    }
$n = count($proxy[0]);

print<<<JS1
<html><head>
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<script language="javascript" >
i=1
var autourl=new Array()

JS1;

for($i = 0 ; $i < $n ; $i++){
    $j=$i+1;
    $proxy[0][$i]=str_replace("//",'\\',$proxy[0][$i]);
    $proxy[0][$i]=str_replace("/",'',$proxy[0][$i]);
    $proxy[0][$i]=str_replace('\\','//',$proxy[0][$i]);
    $proxy[0][$i]=gethostbyname($proxy[0][$i]);
    echo "autourl[$j]=\"" . $proxy[0][$i] . "\"\r\n";
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
document.write("<img src="+autourl[i]+" width=1 height=1 onerror=auto('https://"+autourl[i]+"')>") 
}
run() 
</script>
JS2;

echo '</head><title>选择服务器</title><body>' . $link;
echo '<br /> <br />出现证书提示点“<b>是</b>”，可能连续点几次';
echo '</body></html>';

?> 



