<?php
$str = file_get_contents('mmh.html');
$link = match_links($str);
print_r($link);

$str2 = file_get_contents('mh.html');
$p = explode('<hr size=1>',$str2);

for($i=0; $i < 42; $i++){

for($j=0; $j < 42; $j++){
if(strstr($p[$i],$link[1][$j]))
$fn = $link[0][$j+4];
$c = $p[$i];
file_put_contents($fn,$c);
continue;
}
}
function match_links($document){  # 白名单相关函数，获取超链接
    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $document, $links);
    while(list($key, $val) = each($links[2])){
        if(!empty($val))
            $match[] = $val;
        }
    while(list($key, $val) = each($links[3])){
        if(!empty($val))
            $match[] = $val;
        }
    return array($match, $links[4]);
    }

?>