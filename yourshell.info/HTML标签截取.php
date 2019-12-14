
<?php
/*
 * 函数功能：实现了完整的HTML标签截取而不会错位。支持中英文混合，可以运行于支持mb_string，iconv函数和通用环境。
 * 作    者：Harry Zhang
 * 邮    箱：korsenzhang@yahoo.com.cn
 * 版权声明：该如果您发现了该代码有处理不完善的地方，请发邮件给我，或者来http://forum.f2blog.com论坛联系harry，谢谢。
 * 函数说明：htmlSubString 函数为截取HTML字串的主函数，截取的类型：$maxlen可以是数字，也可以一个特定的标签，如<!--more-->，如果需要是[more]之类的UBB标签，
                           请自己手工更改一下就可以了。不明白的可以联系我帮你完成。
    strip_empty_html 函数为把多余的HTML标签去掉，但这里要注意上面的表达式也会匹配<br /></p>这样的，所以这种需要换回来才可以。
    getStringLength 函数为取得字符串的字数，中文一个汉字算一个字，英文一个字母算一个字。
    subString 函数为截取一定长度的中英文字母函数。（此函数为通用截取中文英文字符的函数，很多地方都有提供）
 * 使用方法：htmlSubString(您要处理的HTML字符串,要截取的长度或者特定的标签)，截取的长度中不包括HTML标签，完全为显示在网页上的文字。
 * 日  期：2007-01-25
 */
//测试代码，最张校对字符数为统计截取后的字数与你需要截取的字数是否相同，如果相同，表示截取的字符串完全正确了。
$source_html=<<<HTML
 <div>a我b来c<b>测d试</b>1一下。</div><br/>
 <div>e<font color="red">有什么问题</font><a href="mailto:korsenzhang@yahoo.com.cn">请联系我</a></div>
 <div>f<!--more-->有什么问题<a href="mailto:korsenzhang@yahoo.com.cn">请联系我</a></div>
HTML;
$target_html_1=htmlSubString($source_html,3);
$target_html_2=htmlSubString($source_html,10);
$target_html_3=htmlSubString($source_html,15);
$target_html_4=htmlSubString($source_html,50);
echo "==========截取HTML字符串测试报告===========<hr>";
echo "原来的字符串：<hr>$source_html<hr><br><br>";
echo "截取3个字符，测试结果为：<hr>".$target_html_1."<hr><font color=red>最终校对字符数：</font>".getStringLength(strip_tags($target_html_1))."<br><br>";
echo "截取10个字符，测试结果为：<hr>".$target_html_2."<hr><font color=red>最终校对字符数：</font>".getStringLength(strip_tags($target_html_2))."<br><br>";
echo "截取15个字符，测试结果为：<hr>".$target_html_3."<hr><font color=red>最终校对字符数：</font>".getStringLength(strip_tags($target_html_3))."<br><br>";
echo "截取50个字符，测试结果为：<hr>".$target_html_4."<hr><font color=red>最终校对字符数：</font>".getStringLength(strip_tags($target_html_4))."<br><br>";
//结束测试代码

//以下四个函数为必须函数。
function htmlSubString($content,$maxlen=300){
 //把字符按HTML标签变成数组。
 $content = preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
 $wordrows=0; //中英字数
 $outstr="";  //生成的字串
 $wordend=false; //是否符合最大的长度
 $beginTags=0; //除<img><br><hr>这些短标签外，其它计算开始标签，如<div*>
 $endTags=0;  //计算结尾标签，如</div>，如果$beginTags==$endTags表示标签数目相对称，可以退出循环。
 //print_r($content);
 foreach($content as $value){
  if (trim($value)=="") continue; //如果该值为空，则继续下一个值
  if (strpos(";$value","<")>0){
   //如果与要载取的标签相同，则到处结束截取。
   if (trim($value)==$maxlen) {
    $wordend=true;
    continue;
   }
   if ($wordend==false){
    $outstr.=$value;
    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
     $beginTags++; //除img,br,hr外的标签都加1
    }
   }else if (preg_match("/<\/([^>]+?)>/is",$value,$matches)){
    $endTags++;
    $outstr.=$value;
    if ($beginTags==$endTags && $wordend==true) break; //字已载完了，并且标签数相称，就可以退出循环。
   }else{
    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
     $beginTags++; //除img,br,hr外的标签都加1
     $outstr.=$value;
    }
   }
  }else{
   if (is_numeric($maxlen)){ //截取字数
    $curLength=getStringLength($value);
    $maxLength=$curLength+$wordrows;
    if ($wordend==false){
     if ($maxLength>$maxlen){ //总字数大于要截取的字数，要在该行要截取
      $outstr.=subString($value,0,$maxlen-$wordrows);
      $wordend=true;
     }else{
      $wordrows=$maxLength;
      $outstr.=$value;
     }
    }
   }else{
    if ($wordend==false) $outstr.=$value;
   }
  }
 }
 //循环替换掉多余的标签，如<p></p>这一类
 while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)){
  $outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
 }
 //把误换的标签换回来
 if (strpos(";".$outstr,"[html_")>0){
  $outstr=str_replace("[html_&lt;]","<",$outstr);
  $outstr=str_replace("[html_&gt;]",">",$outstr);
 }
 //echo htmlspecialchars($outstr);
 return $outstr;
}
//去掉多余的空标签
function strip_empty_html($matches){
 $arr_tags1=explode(" ",$matches[1]);
 if ($arr_tags1[0]==$matches[2]){ //如果前后标签相同，则替换为空。
  return "";
 }else{
  $matches[0]=str_replace("<","[html_&lt;]",$matches[0]);
  $matches[0]=str_replace(">","[html_&gt;]",$matches[0]);
  return $matches[0];
 }
}
//取得字符串的长度，包括中英文。
function getStringLength($text){
 if (function_exists('mb_substr')) {
  $length=mb_strlen($text,'UTF-8');
 } elseif (function_exists('iconv_substr')) {
  $length=iconv_strlen($text,'UTF-8');
 } else {
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
  $length=count($ar[0]);
 }
 return $length;
}
/***********按一定长度截取字符串（包括中文）*********/
function subString($text, $start=0, $limit=12) {
 if (function_exists('mb_substr')) {
  $more = (mb_strlen($text,'UTF-8') > $limit) ? TRUE : FALSE;
  $text = mb_substr($text, 0, $limit, 'UTF-8');
  return $text;
 } elseif (function_exists('iconv_substr')) {
  $more = (iconv_strlen($text,'UTF-8') > $limit) ? TRUE : FALSE;
  $text = iconv_substr($text, 0, $limit, 'UTF-8');
  //return array($text, $more);
  return $text;
 } else {
  preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);   
  if(func_num_args() >= 3) {   
   if (count($ar[0])>$limit) {
    $more = TRUE;
    $text = join("",array_slice($ar[0],0,$limit)); 
   } else {
    $more = FALSE;
    $text = join("",array_slice($ar[0],0,$limit)); 
   }
  } else {
   $more = FALSE;
   $text =  join("",array_slice($ar[0],0)); 
  }
  return $text;
 } 
}
?> 