<?php
$text = 'Private key: pHp';                          # ��֧��UTF-8
$img  = imagecreate(160,30);                         # ����һ�� 200X30 ��ͼ��,ÿ���ֵĳ����������� 16*12=196
imagecolorallocate($img,255,255,255);                # ������ɫ(������),���屳��
$blue = imagecolorallocate($img,0,0,255);            # �����Ļ�����ʹ�������ɫ��
imagestring($img, 5, 5, 5, $text, $blue);            # ָ��ʹ��$blue,font,x,y
header("Content-type: image/gif");
imagegif($img);                                      //imagegif($img,'x.gif');
imagedestroy($img);
//echo '<br><center><img src="x.gif">';

?>