<?php
if(false !== function_exists("zip_open")){
echo "zip ��չ����\r\n<br>";
}
if(false !== function_exists("imap_open")){
echo "imap ��չ����\r\n<br>";
}
$url = 'http://'.$_SERVER['HTTP_HOST'].substr(@$REQUEST_URI,0,strrpos(@$REQUEST_URI,'/')); 
echo $url;
echo $_SERVER['PHP_SELF']."<br><br><br>";
echo __FILE__."<br>"; 
?>
