<?php
set_time_limit(0);
function view_dir($dir)
{
$dp=opendir($dir); //打开目录句柄
//echo "<br>".$dir."<br><br>"; 
$path2='';
while ($file = readdir($dp)) //遍历目录
{
   if ($file !='.'&&$file !='..') //如果文件不是当前目录及父目录
   {    
    $path=$dir."/".$file; //获取路径
    if(is_dir($path)) //如果当前文件为目录
    {
     view_dir($path);   //递归调用
    }
    else   //如果不是目录
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
//echo '<td width="17%" align="center"><a href='.$_SERVER['SCRIPT_NAME'].'?cp='.$path.'> 复制至XX</a></td>';
//echo '<td width="31%" align="center"><b><a href='.$_SERVER['SCRIPT_NAME'].'?cmd=del&name='.$path.'>删除</a></b></td>';
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





$file='<div id="m"><p id="lg"><img src="../img/baidu_sylogo1.gif" width="27px" height="12px" usemap="#mp"><map name="mp"><a shape="rect" coords="40,25,230,95" href="../yuanso/index.html" target="_blank" title="点此进入空间" ></map></p><p id="nv"><a href="../yuanso/index1.html">文字1</a>　<b>文字2</b>　<a href="../yuanso/index3.html">文字3</a></p></div>
';
$del=array("/name=.+?['|\"]/i","/src=.+?['|\"]/i","/id=.+?['|\"]/i","/width=.+?['|\"]/i","/height=.+?['|\"]/i","/usemap=.+?['|\"]/i","/shape=.+?['|\"]/i","/coords=.+?['|\"]/i","/target=.+?['|\"]/i","/title=.+?['|\"]/i");
$file = preg_replace($del,"",$file);//去除style样式
$file = str_replace(" ","",$file);//去除所有空格
$file = str_replace("<ahref=","<a href=",$file);//还原空格
echo $file;


?>