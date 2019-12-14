<?php
header("Content-type: text/html; charset=UTF-8");
$str = file_get_contents('http://www.sony.co.jp/');
$coding = chkcode($str); //判断文件gbk.html的编码
$str = iconv($coding,"UTF-8//IGNORE//TRANSLIT",$str); //转为UTF-8编码
echo $coding."\r\n<br />";
echo $str;

function chkcode($str){
    $code = array(
        'GBK',
        'EUC-CN',
        'BIG5',
        'EUC-TW',
        'HZ',
        'CP950',
        'BIG5-HKSCS',
        'UTF-8',
        'ASCII',
        'ISO-8859-1',
        'ISO-8859-6',
        'ISO-8859-8',
        'GB2312',
        'CP936',
        'BIG5-HKSCS:2001',
        'BIG5-HKSCS:1999',
        'ISO-2022-CN',
        'ISO-2022-CN-EXT',
        'SJIS',
        'JIS',
        'EUC-JP',
        'SHIFT_JIS',
        'eucJP-win',
        'SJIS-win',
        'ISO-2022-JP',
        'CP932',
        'ISO-2022-JP',
        'ISO-2022-JP-2',
        'ISO-2022-JP-1',
        'EUC-KR',
        'CP949',
        'ISO-2022-KR',
        'JOHAB',
        'UTF-7'
);

foreach($code as $charset){
if($str==iconv('UTF-8',"$charset//IGNORE//TRANSLIT",iconv($charset,'UTF-8// IGNORE//TRANSLIT',$str))){
        return $charset;
        break;
        }
    }
return 'UTF-8';
}
?>