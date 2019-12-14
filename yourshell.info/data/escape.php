<?php
//<script language="javascript" type="text/javascript" src="js/utf.js"></script>

function escape($str) { //仅用于英文
$n = strlen($str);
$e = '';
for($i = 0; $i < $n; $i++){
$e .= '%'.bin2hex($str[$i]); //$e.=chr($str[$i]);
}
return '<script language="javascript" type="text/javascript">document.write(unescape(\''.$e.'\'));</script>';
}
$str = 'aB';
echo escape($str);

<?PHP
function phpEscape($str, $encode =""){
   preg_match_all("/[\xC0-\xE0].|[\xE0-\xF0]..|[\x01-\x7f]+/", $str, $r);
   // prt($r);
  $ar = $r[0];
   foreach($ar as $k => $v){
    $ord = ord($v[0]);
   if($ord <= 0x7F)
     $ar[$k] = rawurlencode($v);
   elseif ($ord < 0xE0){
     $ar[$k] ="%u" . bin2hex(iconv($encode,"UCS-2", $v));
     }
    elseif ($ord < 0xF0){
     $ar[$k] ="%u" . bin2hex(iconv($encode,"UCS-2", $v));
    }
   } //foreach 
   return join("", $ar);
  }
$str = '3as2d4f654asd6f你好';
$x = phpEscape($str, $encode ="utf-8");
echo $x;

echo '<script language="javascript" type="text/javascript">document.write('.
$x.');</script>';