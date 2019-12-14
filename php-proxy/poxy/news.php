<html><head><STYLE>BODY { FONT-SIZE: 10pt; FONT-FAMILY: 宋体 }</STYLE></head><body>
<marquee scrolldelay='0' scrollAmount='1' direction=up  height="100" onmousemove='this.stop()' onmouseout='this.start()'>
<?php
/************改写URL的函数***************/
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

function changurl($str){
    $url_str = 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strrpos($_SERVER['REQUEST_URI'], '/')) . '/';
    $str = explode('<li>', $str);
    $n = count($str);
    for($i = 1;$i < $n;$i++){
        $url = (explode('"', $str[$i]));
        $url[1] = $url_str . "index.php?q=" . encode_url($url[1]);
        $url = $url[0] . '"' . $url[1] . '"' . $url[2];
        $url = '<li>'.$url;
        echo $url;
        }
    }

function encode_url($url){
    return rawurlencode(base64_encode($url));
    }

function utf2html($str)
{
    $ret = "";
    $max = strlen($str);
    $last = 0;  
    for ($i=0; $i<$max; $i++) {
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1>>5 == 6) {  
            $ret .= substr($str, $last, $i-$last); 
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i}); 
            $c2 &= 63;  
            $c2 |= (($c1 & 3) << 6); 
            $c1 >>= 2; 
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";"; 
            $last = $i+1;
        }
        elseif ($c1>>4 == 14) {  
            $ret .= substr($str, $last, $i-$last); 
            $c2 = ord($str{++$i}); 
            $c3 = ord($str{++$i}); 
            $c1 &= 15; 
            $c2 &= 63;  
            $c3 &= 63;  
            $c3 |= (($c2 & 3) << 6); 
            $c2 >>=2; 
            $c2 |= (($c1 & 15) << 4); 
            $c1 >>= 4; 
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';'; 
            $last = $i+1;
        }
    }
    $str=$ret . substr($str, $last, $i); 
return $str;
}
/************函数结束，输出数据***************/
//$url = base64_decode('http://127.0.0.1/dl.htm');
$file = getpage('http://127.0.0.1/dl.htm');
preg_match("'全球新闻点击排行(.+)<!-- end #three_col_left -->'s", $file, $line);
$news = str_replace('<div id="content_list">', '', $line[1]);
$news = str_replace(' -->', '', $news);
$news = str_replace('<br>', '', $news);
$news = str_replace('</div>', '', $news);
$news = str_replace('class=txt ', '', $news);
$news = changurl($news);
if(extension_loaded('mbstring')){
$news= mb_convert_encoding($news,'GBK','UTF-8');
}else{
$news = iconv('UTF-8','GBK//IGNORE//TRANSLIT',$news);
}
$news = utf2html($news); 

echo $news;
?>
</marquee>
</body></html>

















