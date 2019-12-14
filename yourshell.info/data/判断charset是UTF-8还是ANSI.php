<?php
function chkcode($html){  # 判断charset是UTF-8还是ANSI

    if($html == @iconv('UTF-8', 'UTF-8//IGNORE//TRANSLIT', $html))  return 'utf-8';

    $char = str_replace(array('charset =','charset="','charset= '), "charset=", $html);
    preg_match("'<meta.*?charset=(.+?)\".*?\>'is",$char,$meta_charset);

    if (isset($meta_charset[1]) && !empty($meta_charset[1])){
        $charset = str_replace(array(' ','"'), "", $meta_charset[1]);
        $charset = strtolower($charset); 
        if(strstr($charset,'gb2312')) $charset = 'GBK';
        if(strstr($charset,'iso-8859-1') && $lang == 'zh-cn') $charset = 'GBK';
        if(strstr($charset,'utf-8')) $charset = 'ANSI';
        return $charset;
   }else{
        return 'ANSI';
   }
}

$html = file_get_contents('mmh.html');
$charset = chkcode($html); //echo $charset;
if($charset !== 'utf-8' && $charset !== 'ANSI' ){
    //$html = mb_convert_encoding($html, 'UTF-8', $charset);
    $html = iconv($charset, 'UTF-8//IGNORE//TRANSLIT', $html);
    }
if($charset !== 'ANSI' ){
header("Content-type: text/html;charset=utf-8");
//$html = base64_encode($html); #作需要的处理
echo $html;
}else echo $html;
