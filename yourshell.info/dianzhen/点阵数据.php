<?php
/**
 * ��ȡ���ֵ�������
 *
 * @author    legend <legendsky@hotmail.com> 
 * @link      http://www.ugia.cn/?p=82
 * @Copyright www.ugia.cn  
 */
header('Content-Type:text/html;charset=GBK'); 
$str = "���ִ󷨺�";

$font_file_name   = "simsun12.fon"; // �����ֿ��ļ���
$font_width       = 12;  // ���ֿ��
$font_height      = 12;  // ���ָ߶�
$start_offset     = 0; // ƫ��

$fp = fopen($font_file_name, "rb");

$offset_size = $font_width * $font_height / 8;
$string_size = $font_width * $font_height;
$dot_string  = "";

for ($i = 0; $i < strlen($str); $i ++)
{
    if (ord($str{$i}) > 160)
    {
        // ������λ�룬Ȼ���ټ���������λ���ά���е�λ�ã������ó����ַ����ļ��е�ƫ��
        $offset = ((ord($str{$i}) - 0xa1) * 94 + ord($str{$i + 1}) - 0xa1) * $offset_size;
        $i ++;
    }
    else
    {
        $offset = (ord($str{$i}) + 156 - 1) * $offset_size;        
    }
    
    // ��ȡ���������
    fseek($fp, $start_offset + $offset, SEEK_SET);
    $bindot = fread($fp, $offset_size);

    for ($j = 0; $j < $offset_size; $j ++)
    {
        // �������Ƶ�������ת��Ϊ�ַ���
        $dot_string .= sprintf("%08b", ord($bindot{$j}));

    }

}

fclose($fp);

$str2arr=str_split($dot_string,$font_width);
$n = count($str2arr) / $font_width ;
$k = '';
for($i = 0;$i < ($font_width);$i++){
for($j = 0;$j < $n;$j++){
$x= $j * $font_width + $i ;
$k .= $str2arr[$x] ;
}
}
$k = $k;
$dot_string=implode(str_split($k,count($str2arr)),"<br>");
 $dot_string=str_replace('0','&nbsp;&nbsp;',$dot_string);
$dot_string=str_replace('1','��',$dot_string); 
//echo $dot_string;
echo '<center><table><tbody><tr><td><span style="line-height:15px;">'.$dot_string.'</span></td></tr></tbody></table></center>';
/*

$len = ($font_width + 4)* $font_width;
$dot_string=str_replace('0','X',$dot_string);
$dot_string=str_replace('1','Y',$dot_string);
$dot_string=implode(str_split($dot_string,$font_width),"<br>");
$dot_string=implode(str_split($dot_string,$len),"</span></td>\r\n<td><span style=\"line-height:15px;\">");
$dot_string=str_replace('X','&nbsp;&nbsp;',$dot_string);
$dot_string=str_replace('Y','��',$dot_string);
echo '<span style="line-height:15px;">';
echo '<center><table><tbody><tr><td><span style="line-height:15px;">'.$dot_string.'</td></tr></tbody></table></center>';
echo '</span>';
*/
?>










