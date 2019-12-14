<?php
$str = "0123456789²âÊÔab¡°\"'cdefghijklmnopÄãºÃ¶à×Ö½Ú×Ö·ûÂÒÂë";
$str = file_get_contents('D://t.htm');
$r = 3;
$m = mb_strlen($str);
$n = $m/$r;
$jsarr = '';
  for($i = 0; $i < $r; $i++){
 $nstr = '';
 for($j = 0; $j < $n; $j++){
 $n2 = $j * $r + $i;
 if($m < $n2) break;
 #$nstr = @$str[$n2].$nstr; //¶à×Ö½Ú×Ö·ûÂÒÂë
 $nstr = mb_substr($str,$n2,1,'gbk').$nstr; //ÉùÃ÷$strµÄ±àÂë¸ñÊ½
 }
$nstr = "\"". addJsSlashes($nstr) ."\",";//addslashes  addcslashes

$jsarr .= $nstr;
}

function addJsSlashes($str) {
  $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134\177");
  return str_replace(array(chr(7), chr(11)), array('\007', '\013'), $str);
}

echo "<script> arr = [".$jsarr."];";
?>

function decode ( arr ) {
   var width= arr[0].length; 
   var sfarr = new Array(width);
   for ( var  c  = 0;  c < width; c++ ) {
        sfarr[c] = "";
    }
   for ( var c = 0; c < arr.length; c++ ) {
      if ( arr[ c ] == null ) continue;
      var str = new String( arr[ c ] );
      var dif = width - str.length;
         for ( var z = 0; z < str.length; z++ ) {
 	      if ( str.charAt ( z ) + "" == "" ) continue;
 	      else sfarr[ z + dif ] += str.charAt( z );  }
  }
  var w = "";
  for ( var c = 0; c < sfarr.length; c++ ) {
 	 w =  sfarr[ c ] + w ;
  }
 document.write( w );
 };

 decode(arr);document.close();
</script>
