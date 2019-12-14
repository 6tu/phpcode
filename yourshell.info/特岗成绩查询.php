<?php
set_time_limit(0);
/*
if(isset($_POST["ename"])){
$ename = $_POST["ename"];
$exid = $_POST["exid"];
}else{
echo '<form method="post" action="cx.php">';
echo '&nbsp;&nbsp;&nbsp;&nbsp;输入姓名：<input name="ename" type="text" value="程丽" />';
echo '&nbsp;&nbsp;&nbsp;&nbsp;考场编号：<input name="exid" type="text" value="111050323" />';
echo '&nbsp;&nbsp;<input name="submit" type="submit" value="提交" /></form>';
exit(0);
}
*/
$exid = '111050323';
$s = file_get_contents('s.txt');
$ss = explode("\r\n",$s);
$n = count($ss);
for($j=0; $j < $n; $j++){

$ename = $ss[$j];


for($i=0; $i < 40; $i++){
if($i < 10){
$i = '0'.$i;
}
$cookie_file = dirname(__FILE__) . "/cookie_" . md5(basename(__FILE__)) . ".txt"; 
$res = vlogin('http://www.nxpta.gov.cn/chengjichaxun/search.php', 'type=searchScore&Tdate=2013&Pselect=32&Ename='.$ename.'&Scardsort=1&Scardno='.$exid.$i.'&submit=%CC%E1%BD%BB');
//header("Content-type: text/html; charset=gb2312");
if(stristr($res, '不存在')){
//    @unlink($cookie_file); 
//    echo "您查询的成绩信息不存在.<br>\r\n";
    echo ".";
    }else{
//echo $res;
echo $ename.$exid.$i.'<br>';
//exit(0);
}
}
}
@unlink($cookie_file); 



function vlogin($url, $data){
     $curl = curl_init(); 
     curl_setopt($curl, CURLOPT_URL, $url); 
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); 
     curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET'); 
     curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
     curl_setopt($curl, CURLOPT_POST, 1); 
     curl_setopt($curl, CURLOPT_POSTFIELDS, @$data);
     curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']);
     curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']);
     curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
     $tmpInfo = curl_exec($curl);
     if (curl_errno($curl)){
         echo 'Errno' . curl_error($curl);
         }
     curl_close($curl);
     return $tmpInfo;
    }

?>