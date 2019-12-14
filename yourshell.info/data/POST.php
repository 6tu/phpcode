<?php
function fmime($filename){
$fen=substr($filename,strrpos($filename,".")+1);
$file = file("./mime.txt");
$n=count($file);
for($i = 0;$i < $n;$i++){
$line = explode(" ", $file[$i]);
if($line[1]===$fen){
$mime = $line[0];
break;
}else{
$mime = 'application/octet-stream';
}
}
return $mime;
}
 $data["btnUpload"]="upload"; 
 $data["MAX_FILE_SIZE"]=2000000;
 $httpurl = "http://mybox.uuuq.com/up.php";
 $filename = "./articles/www.zip";
 $mime = fmime($filename);
 $url = parse_url($httpurl);
 if (!$url) return "couldn't parse url";
 if (!isset($url['port'])) { $url['port'] = ""; }
 if (!isset($url['query'])) { $url['query'] = ""; 
}
 //$boundary = "--------------------".md5(uniqid(rand()));
 srand((double)microtime()*1000000);
 $boundary = "---------------------------".substr(md5(rand(0,32000)),0,10);
 $boundary_2 = "--$boundary";
 $encoded = "";
 while (list($k,$v) = each($data))
 {
  $encoded .= $boundary_2."\r\nContent-Disposition: form-data;
name=\"".rawurlencode($k)."\"\r\n\r\n";
  $encoded .= rawurlencode($v)."\r\n";
 }
 $encoded .= $boundary_2."\r\nContent-Disposition: form-data;
name=\"file\"; filename=\"$filename\"\r\nContent-Type:

$mime\r\n\r\n";
 $content = join("", file($filename));
 $content .= "\r\n".$boundary_2."--\r\n\r\n";
 $length = strlen($content) + strlen($encoded);
 $contents = $encoded.$content;
 $fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
 if (!$fp) return "Failed to open socket to $url[host]";
 fputs($fp, sprintf("POST %s%s%s HTTP/1.0\r\n", $url['path'],
 $url['query'] ? "?" : "", $url['query']));
 fputs($fp, "Host: $url[host]\r\n");
 fputs($fp, "Content-type: multipart/form-data; boundary=$boundary\r\n");
 fputs($fp, "Content-length: ".$length."\r\n");
 fputs($fp, "Connection: close\r\n\r\n");
 fputs($fp, $contents);
 $line = fgets($fp,1024);
 if (!eregi("^HTTP/1\.. 200", $line)) return;
 $results = "";
 $inheader = 1;
 while(!feof($fp))
 {
  $line = fgets($fp,1024);
  if ($inheader && ($line == "\r\n" || $line == "\r\r\n"))
  {
   $inheader = 0;
  }
  elseif (!$inheader)
  {
   $results .= $line;
  }
 }
 fclose($fp);
echo "ok";
?>


