<?php
$c=file_get_contents('x.txt');
$c=str_replace(array('BEGIN:VCARD','END:VCARD','N;CHARSET=UTF-8:','TEL;CELL','VERSION:2.1'),'',$c);
$c=str_replace("\r\n:",':  ',$c);
file_put_contents('xx.txt',$c);


?>
