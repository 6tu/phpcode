
<?php
/*
 * �������ܣ�ʵ����������HTML��ǩ��ȡ�������λ��֧����Ӣ�Ļ�ϣ�����������֧��mb_string��iconv������ͨ�û�����
 * ��    �ߣ�Harry Zhang
 * ��    �䣺korsenzhang@yahoo.com.cn
 * ��Ȩ������������������˸ô����д������Ƶĵط����뷢�ʼ����ң�������http://forum.f2blog.com��̳��ϵharry��лл��
 * ����˵����htmlSubString ����Ϊ��ȡHTML�ִ�������������ȡ�����ͣ�$maxlen���������֣�Ҳ����һ���ض��ı�ǩ����<!--more-->�������Ҫ��[more]֮���UBB��ǩ��
                           ���Լ��ֹ�����һ�¾Ϳ����ˡ������׵Ŀ�����ϵ�Ұ�����ɡ�
    strip_empty_html ����Ϊ�Ѷ����HTML��ǩȥ����������Ҫע������ı��ʽҲ��ƥ��<br /></p>�����ģ�����������Ҫ�������ſ��ԡ�
    getStringLength ����Ϊȡ���ַ���������������һ��������һ���֣�Ӣ��һ����ĸ��һ���֡�
    subString ����Ϊ��ȡһ�����ȵ���Ӣ����ĸ���������˺���Ϊͨ�ý�ȡ����Ӣ���ַ��ĺ������ܶ�ط������ṩ��
 * ʹ�÷�����htmlSubString(��Ҫ�����HTML�ַ���,Ҫ��ȡ�ĳ��Ȼ����ض��ı�ǩ)����ȡ�ĳ����в�����HTML��ǩ����ȫΪ��ʾ����ҳ�ϵ����֡�
 * ��  �ڣ�2007-01-25
 */
//���Դ��룬����У���ַ���Ϊͳ�ƽ�ȡ�������������Ҫ��ȡ�������Ƿ���ͬ�������ͬ����ʾ��ȡ���ַ�����ȫ��ȷ�ˡ�
$source_html=<<<HTML
 <div>a��b��c<b>��d��</b>1һ�¡�</div><br/>
 <div>e<font color="red">��ʲô����</font><a href="mailto:korsenzhang@yahoo.com.cn">����ϵ��</a></div>
 <div>f<!--more-->��ʲô����<a href="mailto:korsenzhang@yahoo.com.cn">����ϵ��</a></div>
HTML;
$target_html_1=htmlSubString($source_html,3);
$target_html_2=htmlSubString($source_html,10);
$target_html_3=htmlSubString($source_html,15);
$target_html_4=htmlSubString($source_html,50);
echo "==========��ȡHTML�ַ������Ա���===========<hr>";
echo "ԭ�����ַ�����<hr>$source_html<hr><br><br>";
echo "��ȡ3���ַ������Խ��Ϊ��<hr>".$target_html_1."<hr><font color=red>����У���ַ�����</font>".getStringLength(strip_tags($target_html_1))."<br><br>";
echo "��ȡ10���ַ������Խ��Ϊ��<hr>".$target_html_2."<hr><font color=red>����У���ַ�����</font>".getStringLength(strip_tags($target_html_2))."<br><br>";
echo "��ȡ15���ַ������Խ��Ϊ��<hr>".$target_html_3."<hr><font color=red>����У���ַ�����</font>".getStringLength(strip_tags($target_html_3))."<br><br>";
echo "��ȡ50���ַ������Խ��Ϊ��<hr>".$target_html_4."<hr><font color=red>����У���ַ�����</font>".getStringLength(strip_tags($target_html_4))."<br><br>";
//�������Դ���

//�����ĸ�����Ϊ���뺯����
function htmlSubString($content,$maxlen=300){
 //���ַ���HTML��ǩ������顣
 $content = preg_split("/(<[^>]+?>)/si",$content, -1,PREG_SPLIT_NO_EMPTY| PREG_SPLIT_DELIM_CAPTURE);
 $wordrows=0; //��Ӣ����
 $outstr="";  //���ɵ��ִ�
 $wordend=false; //�Ƿ�������ĳ���
 $beginTags=0; //��<img><br><hr>��Щ�̱�ǩ�⣬�������㿪ʼ��ǩ����<div*>
 $endTags=0;  //�����β��ǩ����</div>�����$beginTags==$endTags��ʾ��ǩ��Ŀ��Գƣ������˳�ѭ����
 //print_r($content);
 foreach($content as $value){
  if (trim($value)=="") continue; //�����ֵΪ�գ��������һ��ֵ
  if (strpos(";$value","<")>0){
   //�����Ҫ��ȡ�ı�ǩ��ͬ���򵽴�������ȡ��
   if (trim($value)==$maxlen) {
    $wordend=true;
    continue;
   }
   if ($wordend==false){
    $outstr.=$value;
    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
     $beginTags++; //��img,br,hr��ı�ǩ����1
    }
   }else if (preg_match("/<\/([^>]+?)>/is",$value,$matches)){
    $endTags++;
    $outstr.=$value;
    if ($beginTags==$endTags && $wordend==true) break; //���������ˣ����ұ�ǩ����ƣ��Ϳ����˳�ѭ����
   }else{
    if (!preg_match("/<img([^>]+?)>/is",$value) && !preg_match("/<param([^>]+?)>/is",$value) && !preg_match("/<!([^>]+?)>/is",$value) && !preg_match("/<br([^>]+?)>/is",$value) && !preg_match("/<hr([^>]+?)>/is",$value)) {
     $beginTags++; //��img,br,hr��ı�ǩ����1
     $outstr.=$value;
    }
   }
  }else{
   if (is_numeric($maxlen)){ //��ȡ����
    $curLength=getStringLength($value);
    $maxLength=$curLength+$wordrows;
    if ($wordend==false){
     if ($maxLength>$maxlen){ //����������Ҫ��ȡ��������Ҫ�ڸ���Ҫ��ȡ
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
 //ѭ���滻������ı�ǩ����<p></p>��һ��
 while(preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is",$outstr)){
  $outstr=preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is","strip_empty_html",$outstr);
 }
 //���󻻵ı�ǩ������
 if (strpos(";".$outstr,"[html_")>0){
  $outstr=str_replace("[html_&lt;]","<",$outstr);
  $outstr=str_replace("[html_&gt;]",">",$outstr);
 }
 //echo htmlspecialchars($outstr);
 return $outstr;
}
//ȥ������Ŀձ�ǩ
function strip_empty_html($matches){
 $arr_tags1=explode(" ",$matches[1]);
 if ($arr_tags1[0]==$matches[2]){ //���ǰ���ǩ��ͬ�����滻Ϊ�ա�
  return "";
 }else{
  $matches[0]=str_replace("<","[html_&lt;]",$matches[0]);
  $matches[0]=str_replace(">","[html_&gt;]",$matches[0]);
  return $matches[0];
 }
}
//ȡ���ַ����ĳ��ȣ�������Ӣ�ġ�
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
/***********��һ�����Ƚ�ȡ�ַ������������ģ�*********/
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