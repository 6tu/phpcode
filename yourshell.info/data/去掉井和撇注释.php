<?php
$x = file('nph-proxy.cgi');
$n = count($x);
echo $n;
$nx = '';
for($i=0; $i < $n; $i++){
$xx = trim(ltrim($x[$i]));
if(isset($xx['0']) && $xx['0'] !== '#' && $xx['0'] !== '/') $nx .= $x[$i]."\n";
}

$nx = str_replace(" @@@","#",$nx);
$nx = str_replace("\n\n\n","\n",$nx);
$nx = str_replace("\n\n","\n",$nx);
$nx = str_replace("\n\n","\n",$nx);
file_put_contents('nph-x.cgi',$nx);
$x = file('nph-x.cgi');
$n = count($x);
echo "<br>".$n;
?>