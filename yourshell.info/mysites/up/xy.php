<?php
$c=file_get_contents('x.txt');
$c=str_replace(array('BEGIN:VCARD','END:VCARD','N;CHARSET=UTF-8:','TEL;CELL','VERSION2.1'),'',$c);

file_put_contents('xx.txt','',$c);


?>
