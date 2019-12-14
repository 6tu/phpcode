<?php 
$file = file('./down/country-ipv4.lst'); 
$handle = fopen('./cnip.txt', 'a'); 
if($handle) { 
foreach ($file as $key => $val) { 
if (strpos($val, '#') !== 0) { 
$ipLines = explode(' ', $val); 
if ($ipLines[6] == 'cn') { 
fputs($handle, $ipLines[0]. '-'. $ipLines[2]. "\n"); 
} 
} 
} 
} 
?> 
