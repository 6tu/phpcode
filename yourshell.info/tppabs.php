<?php
set_time_limit(0);
function view_dir($dir)
{
$dp=opendir($dir); //��Ŀ¼���
//echo "<br>".$dir."<br><br>"; 
$path2='';
while ($file = readdir($dp)) //����Ŀ¼
{
   if ($file !='.'&&$file !='..') //����ļ����ǵ�ǰĿ¼����Ŀ¼
   {    
    $path=$dir."/".$file; //��ȡ·��
    if(is_dir($path)) //�����ǰ�ļ�ΪĿ¼
    {
     view_dir($path);   //�ݹ����
    }
    else   //�������Ŀ¼
    {

//echo '<tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0">';

   $path2 .= $path."\r\n";
   
   $c=file_get_contents($path);
//   $c = str_replace('http://4.28.99.196/chigb', "", $c);
//   $c1=array(' tppabs="/style/book.css" rel="stylesheet"',' tppabs="/chigb/up.gif"',' tppabs="/chigb/1pix.gif"',' tppabs="/chigb/left.gif"',' tppabs="/chigb/right.gif"');
//   $c2 = str_replace($c1, "", $c);

$del=array("/tppabs=.+?['|\"]/i");
$c2 = preg_replace($del,"",$c);
   $c2 = str_replace('" >', '">', $c2);

   file_put_contents($path,$c2);
//echo '<td width="52%" height="25"><a href="'.$path.'">'.$path.'</a></td>';
//echo '<td width="17%" align="center"><a href='.$_SERVER['SCRIPT_NAME'].'?cp='.$path.'> ������XX</a></td>';
//echo '<td width="31%" align="center"><b><a href='.$_SERVER['SCRIPT_NAME'].'?cmd=del&name='.$path.'>ɾ��</a></b></td>';
//echo '</tr>';
//echo '</table></td></tr>';
    }
   }
}
return $path2."\r\n";
closedir($dp);
} 
$dir='E:book\chigb\x';
echo view_dir($dir);





$file='<div id="m"><p id="lg"><img src="../img/baidu_sylogo1.gif" width="27px" height="12px" usemap="#mp"><map name="mp"><a shape="rect" coords="40,25,230,95" href="../yuanso/index.html" target="_blank" title="��˽���ռ�" ></map></p><p id="nv"><a href="../yuanso/index1.html">����1</a>��<b>����2</b>��<a href="../yuanso/index3.html">����3</a></p></div>
';
$del=array("/name=.+?['|\"]/i","/src=.+?['|\"]/i","/id=.+?['|\"]/i","/width=.+?['|\"]/i","/height=.+?['|\"]/i","/usemap=.+?['|\"]/i","/shape=.+?['|\"]/i","/coords=.+?['|\"]/i","/target=.+?['|\"]/i","/title=.+?['|\"]/i");
$file = preg_replace($del,"",$file);//ȥ��style��ʽ
$file = str_replace(" ","",$file);//ȥ�����пո�
$file = str_replace("<ahref=","<a href=",$file);//��ԭ�ո�
echo $file;


?>