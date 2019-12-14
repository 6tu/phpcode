<?php
//$ttf = chunk_split(base64_encode(file_get_contents('lsansi.ttf')));
//file_put_contents('lsansi.ttfx',$ttf);

$size = '18';
$font = 'lsansi.ttf';                                // $ffolder="/usr/local/bin/fonts";
$text = 'Private key: pHp';
$img  = imagecreate(200,30);                         # 每个字的长宽不大于字体 16*12=196
imagecolorallocate($img,255,255,255);                # 分配颜色(红绿蓝)0xff,0xCC,0xCC
$black = imagecolorallocate($img,0,0,255);
imagettftext($img,$size,0,5,22,$black,$font,$text);  # 字体,旋转,X轴,Y轴(大于等于字体)
//header('Content-type: image/gif');
imagegif($img,'x.gif');
imageDestroy($img);
echo '<br><center><img src="x.gif">';

?>