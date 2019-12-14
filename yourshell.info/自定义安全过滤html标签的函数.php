可自定义安全html的php输入过滤函数

$text 需要过滤的字符串

$tags 自定义的安全html

function safehtml($text, $tags = null){
$text     =     trim($text);
//完全过滤注释
$text     =     preg_replace('/<!--?.*-->/','',$text);
//完全过滤动态代码
$text     =     preg_replace('/<\?|\?'.'>/','',$text);
//完全过滤js
$text     =     preg_replace('/<script?.*\/script>/','',$text);

$text     =     str_replace('[','&#091;',$text);
$text     =     str_replace(']','&#093;',$text);
$text     =     str_replace('|','&#124;',$text);
//转换换行符
$text=str_replace(array("\n","\r"),array("%nr%","%rr%"),$text);
//br
$text     =     preg_replace('/<br(\s\/)?'.'>/i','[br]',$text);
$text     =     preg_replace('/(\[br\]\s*){10,}/i','[br]',$text);
//过滤危险的属性，如：过滤on事件lang js
while(preg_match('/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1],$text);
}
while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1].$mat[3],$text);
}
if(empty($tags)) {
$tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a|span|h1|h2|h3|h4|h5|h6';
}
//允许的HTML标签
$text     =     preg_replace('/<('.$tags.')(.*?[^><\/[\]]*)>/i','[\1\2]',$text);
$text     =     preg_replace('/<\/('.$tags.')>/i','[/\1]',$text);
//return $text;
//过滤多余html
$text     =     preg_replace('/<(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|textarea|input|select|radio|checkbox)[^><]*>/i','',$text);
$text     =     preg_replace('/<\/(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|textarea|input|select|radio|checkbox).*>/i','',$text);
//return $text;

//过滤合法的html标签
//while(preg_match('/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i',$text,$mat)){
//     $text=str_replace($mat[0],str_replace('>',']',str_replace('<','[',$mat[0])),$text);
//}
//转换引号
while(preg_match('/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i',$text,$mat)){
$text=str_replace($mat[0],$mat[1].'|'.$mat[3].'|'.$mat[4],$text);
}
//过滤错误的单个引号
while(preg_match('/\[[^\[\]]*(\"|\')[^\[\]]*\]/i',$text,$mat)){
$text=str_replace($mat[0],str_replace($mat[1],'',$mat[0]),$text);
}
//转换其它所有不合法的 < >
$text     =     str_replace('<','&lt;',$text);
$text     =     str_replace('>','&gt;',$text);
$text     =     str_replace('"','&quot;',$text);
//反转换
$text     =     str_replace('[','<',$text);
$text     =     str_replace(']','>',$text);
$text     =     str_replace('|','"',$text);
$text     =     str_replace('\\','"',$text);
//过滤多余空格
$text     =     str_replace('  ',' ',$text);
//反转换换行符
$text=str_replace(array("%nr%","%rr%"),array("\n","\r"),$text);
return $text;
}
 
