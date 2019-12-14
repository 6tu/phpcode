<?php
$c = file_get_contents('admin.php');
$c = trim(ltrim($c));
file_put_contents('admin.phpx',$c);
?>