<?php
$path=dirname(__FILE__); 
function php_get_browser($agent = NULL){
 
$browscapini=$path.'/data/php_browscap.ini';


//*****************如果没有fnmatch()则建立该函数*****************//
if(!function_exists('fnmatch')) {
    function fnmatch($pattern, $string) {
        return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
    } // end
}
//*****************分析php_browscap.ini，返回数组*****************//
$agent=$agent?$agent:$_SERVER['HTTP_USER_AGENT']; 
$yu=array(); 
$q_s=array("#\.#","#\*#","#\?#"); 
$q_r=array("\.",".*",".?");
if (file_exists($browscapini)==false) {

//$bini=file_get_contents('http://browsers.garykeith.com/stream.asp?PHP_BrowsCapINI');
//file_put_contents('php_browscap.ini',$bini);
 
 echo 'php_browscap.ini 不存在，请从http://browsers.garykeith.com/stream.asp下载';
 exit;
}

$brows=parse_ini_file($browscapini,true); 
foreach($brows as $k=>$t){ 
  if(fnmatch($k,$agent)){ 
  $yu['browser_name_pattern']=$k; 
  $pat=preg_replace($q_s,$q_r,$k); 
  $yu['browser_name_regex']=strtolower("^$pat$"); 
    foreach($brows as $g=>$r){ 
      if(@$t['Parent']==$g){ 
        foreach($brows as $a=>$b){ 
          if($r['Parent']==$a){ 
            $yu=array_merge($yu,$b,$r,$t); 
            foreach($yu as $d=>$z){ 
              $l=strtolower($d); 
              $hu[$l]=$z; 
            } 
          } 
        } 
      } 
    } 
    break; 
  } 
} 
return @$hu; 
} 

if (!function_exists('apache_response_headers')) {
    function apache_response_headers () {
        $arh = array();
        $headers = headers_list();
        foreach ($headers as $header) {
            $header = explode(":", $header);
            $arh[array_shift($header)] = trim(implode(":", $header));
        }
        return $arh;
    }
}

if( !function_exists('apache_request_headers') ) {
function apache_request_headers() {
  $arh = array();
  $rx_http = '/\AHTTP_/';
  foreach($_SERVER as $key => $val) {
    if( preg_match($rx_http, $key) ) {
      $arh_key = preg_replace($rx_http, '', $key);
      $rx_matches = array();
      // do some nasty string manipulations to restore the original letter case
      // this should work in most cases
      $rx_matches = explode('_', $arh_key);
      if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        $arh_key = implode('-', $rx_matches);
      }
      $arh[$arh_key] = $val;
    }
  }
  return( $arh );
}
}

/*
if(ini_get("browscap")) {
    $browserinfo = get_browser(null, true);
}else{
    $browserinfo = php_get_browser($agent = NULL);
}

foreach($browserinfo as $key => $value){
echo "$key => $value \r\n<br />";
}
*/

$clientinfo='';
echo "USER_AGENT => ".$_SERVER['HTTP_USER_AGENT'] . "\r\n";
echo '<table><tr><td align="left">客户机相关信息:<br /><small>';
echo 'REMOTE_ADDR =>'.$_SERVER['REMOTE_ADDR']."\r\n<br />";
foreach (apache_request_headers() as $key => $value){
$clientinfo .="$key => $value \r\n";
echo "$key => $value <br />";
}
foreach (apache_response_headers() as $key => $value){
$clientinfo .="$key => $value \r\n";
echo "$key => $value <br />";
}
$oldlog=file_get_contents($path.'/data/client.log');
$clientinfo=$clientinfo."================\r\n".$oldlog;
file_put_contents($path.'/data/client.log',$clientinfo);
echo "</td></tr>\r\n<br /><br /></small></td></tr></table>";
//echo "-----------------\r\n<br />";






?>