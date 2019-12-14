���Զ��尲ȫhtml��php������˺���

$text ��Ҫ���˵��ַ���

$tags �Զ���İ�ȫhtml

function safehtml($text, $tags = null){
$text     =     trim($text);
//��ȫ����ע��
$text     =     preg_replace('/<!--?.*-->/','',$text);
//��ȫ���˶�̬����
$text     =     preg_replace('/<\?|\?'.'>/','',$text);
//��ȫ����js
$text     =     preg_replace('/<script?.*\/script>/','',$text);

$text     =     str_replace('[','&#091;',$text);
$text     =     str_replace(']','&#093;',$text);
$text     =     str_replace('|','&#124;',$text);
//ת�����з�
$text=str_replace(array("\n","\r"),array("%nr%","%rr%"),$text);
//br
$text     =     preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
$text     =     preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
//����Σ�յ����ԣ��磺����on�¼�lang js
while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1],$text);
}
while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1].$mat[3],$text);
}
if(empty($tags)) {
$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a|span|h1|h2|h3|h4|h5|h6';
}
//�����HTML��ǩ
$text     =     preg_replace('/<('.$tags.')(.*?[^><\/[\]]*)>/i','[\1\2]',$text);
$text     =     preg_replace('/<\/('.$tags.')>/i','[/\1]',$text);
//return $text;
//���˶���html
$text     =     preg_replace('/<(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|textarea|input|select|radio|checkbox)[^><]*>/i','',$text);
$text     =     preg_replace('/<\/(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|textarea|input|select|radio|checkbox).*>/i','',$text);
//return $text;

//���˺Ϸ���html��ǩ
//while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
//     $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
//}
//ת������
while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
}
//���˴���ĵ�������
while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
}
//ת���������в��Ϸ��� < >
$text     =     str_replace('<','&lt;',$text);
$text     =     str_replace('>','&gt;',$text);
$text     =     str_replace('"','&quot;',$text);
//��ת��
$text     =     str_replace('[','<',$text);
$text     =     str_replace(']','>',$text);
$text     =     str_replace('|','"',$text);
$text     =     str_replace('\\','"',$text);
//���˶���ո�
$text     =     str_replace('  ',' ',$text);
//��ת�����з�
$text=str_replace(array("%nr%","%rr%"),array("\n","\r"),$text);
return $text;
}
 
