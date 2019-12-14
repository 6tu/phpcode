<HTML>
<HEAD>
<TITLE>文件上传界面</TITLE>
</HEAD>
<BODY><TABLE><CENTER>
<?
if($UploadAction){
$UploadAction=0;
$TimeLimit=60;
/*设置超时限制时间默认时间为 30s，设置为0时为不限时 */
set_time_limit($TimeLimit);
If(($Upfile != "none")&&
($Upfile != ""))
{
$Filepath="d:\web\web\php\test"; //上载文件存放路径
$FileName=$Filepath.$Upfile_name;
if($Upfile_size <1024) //上载文件大小
{$FileSize = (string)$Upfile_size . "字节";}
elseif($Upfile_size <(1024 * 1024))
{
$FileSize = number_format((double)($Upfile_size / 1024), 1) . " KB";
}
else
{
$FileSize = number_format((double)($Upfile_size/(1024*1024)),1)."MB";
}
if(!file_exists($FileName))
{
if(copy($Upfile,$FileName))
{unlink($Upfile);
echo "<br><br>\n";
echo "文件 $Upfile_name 已上载成功！";
echo "<br><br>\n";
echo "文件位置：$FileName";
echo "<br><br>\n";
echo "文件大小：$FileSize";
echo "<br><br>\n";
}
else
{echo "文件 $Upfile_name上载失败！"; }
}
else
{echo "文件 $Upfile_name已经存在！"; }
}
else
{echo "你没有选择任何文件上载！"; }
set_time_limit(30); //恢复默认超时设置
}
?>
<FORM ENCTYPE = "multipart/form-data" NAME = "SubmitForm"
ACTION = "default.php" METHOD = "POST">
<INPUT TYPE = "hidden" NAME = "MAX_FILE_SIZE" VALUE ="1000000">
<INPUT TYPE = "hidden" NAME = "UploadAction" VALUE = "1">
<TR><TD><INPUT NAME = "Upfile" TYPE = "file" SIZE = "30"></TD>
</TR><TR><TD><INPUT NAME = "submit" VALUE = "提交" TYPE = "submit">
<INPUT NAME = "reset" VALUE = "重置" TYPE = "reset"></TD>
</TR></FORM></CENTER></TABLE>
</BODY>
</HTML>