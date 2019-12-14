<form method="post" action="" >
  <input name="deldir" type="text" value="/home/walk/public_html/html_bak/" SIZE=100/>
  <input name="Submit" type="submit" value=" GET "/>

</form>
<?php
function del_dir($dir)    
{    
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {    
       $str = "rmdir /s/q " . $dir;
exec ($str);    
} else {    
       $str = "rm -Rf " . $dir; 
exec ($str);    
}    
}  

set_time_limit(0);

$deldir=$_POST['deldir'];
//$dir='/home/walk/public_html/html_bak/'; 
$pos = strpos($deldir, 'html_bak');
if ($pos === false){
echo '不是指定目录';
exit;
}else if(strpos($deldir, '../')){
echo '不能有../';
exit();
}else{

echo del_dir($deldir)   ;
}
$dir='wget-data';
if(is_dir($dir)==false){
mkdir ($dir,0777);
}
?>


