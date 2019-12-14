<?php
$body = file_get_contents('browse_mailbox.php');
$body = str_replace('	','',$body);
$body = str_replace('    ','',$body);
$body = trim(ltrim($body));
$body = str_replace('/*','<123456789>',$body);
$body = str_replace('*/','</123456789>',$body);
$body = preg_replace('#(<\s*123456789[^>]*>)(.*?)(<\s*/123456789[^>]*>)#is', '', $body);
$body = str_replace(";\r\n",';123456789',$body);
$body = str_replace("{\r\n",'{12345678x',$body);
$body = str_replace("}\r\n",'}12345678y',$body);
$body = str_replace("\r\n",'',$body);
$body = str_replace(";123456789",";\n",$body);
$body = str_replace("\r\n&&"," &&",$body);
$body = str_replace("\r\n||"," ||",$body);
$body = str_replace("12345678x","\n",$body);
$body = str_replace("12345678y","\n",$body);
file_put_contents('x2011.txt',$body);
//echo $new_file;

?>