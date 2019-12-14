<?php
$text = 'Private key: pHp';                          # 不支持UTF-8
$img  = imagecreate(160,30);                         # 建立一幅 200X30 的图像,每个字的长宽不大于字体 16*12=196
imagecolorallocate($img,255,255,255);                # 分配颜色(红绿蓝),定义背景
$blue = imagecolorallocate($img,0,0,255);            # 背景的基础上使用另外的色彩
imagestring($img, 5, 5, 5, $text, $blue);            # 指定使用$blue,font,x,y
header("Content-type: image/gif");
imagegif($img);                                      //imagegif($img,'x.gif');
imagedestroy($img);
//echo '<br><center><img src="x.gif">';

?>