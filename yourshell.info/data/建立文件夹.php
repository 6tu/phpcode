function mkdirs($path , $mode = 0755 ){
if(!is_dir($path)){
mkdirs(dirname($path),$mode);
mkdir($path,$mode);
}
return true;
}
function deldir($dir) {
 $dh=opendir($dir);
 while ($file=readdir($dh)) {
   if($file!="." && $file!="..") {
     $fullpath=$dir."/".$file;
     if(!is_dir($fullpath)) {
         unlink($fullpath);
     } else {
         deldir($fullpath);
     }
   }
 }

 closedir($dh);

 if(rmdir($dir)) {
   return true;
 } else {
   return false;
 }
}
$symd = "2000-1-1";
for($m = 0;$m < 106;$m++){
   $ym = date("Y-n-j", strtotime("$symd+$m month"));
   list($y1, $m1, $d1) = explode("-", $ym);
   $ymt = "articles"."/".$y1."/";

   mkdirs("$ymt");
   mkdirs("$m1");
@deldir($m1-1);
   for($d = 0;$d < 31;$d++){
       $ymd = date("Y-n-j", strtotime("$y1-$m1-$d1 + $d day"));
       //echo $ymd."\r\n<br>";
       list($y2, $m2, $d2) = explode("-", $ymd);
       $path = $y2 . "/" . $m2 . "/" . $d2;
       $txtname = $ymd . '.txt';
       $zipname = $y2."-".$m2 . '.zip';
       if(($y2 + $m2) > ($y1 + $m1)){
           break;
       }
       $cts = "²âÊÔASDFf3as2";
       $fp = fopen("$m1/$txtname", "w+");
       fwrite($fp, $cts);
       fclose($fp);

$zip = new ZipArchive();
if ($zip->open($zipname, ZIPARCHIVE::CREATE)!==TRUE) {
   exit("cannot open <$zipname>\n");
}
$zip->addFile("$m1/$txtname");
$zip->close();
@rename("$zipname", "$ymt$zipname");
@unlink($zipname);
   }
}
@deldir(1);
@deldir(12);
?>