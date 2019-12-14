http://www.jincon.com/archives/12/

<?php
$headers['CLIENT-IP'] = '202.103.229.40'; 
$headers['X-FORWARDED-FOR'] = '202.103.229.40';
 
$headerArr = array(); 
foreach( $headers as $n => $v ) { 
    $headerArr[] = $n .':' . $v;  
}
 
ob_start();
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, "http://localhost/curl/server.php");
curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr );  //构造IP
curl_setopt ($ch, CURLOPT_REFERER, "http://www.163.com/ ");   //构造来路
curl_setopt( $ch, CURLOPT_HEADER, 1);
 
curl_exec($ch);
curl_close ($ch);
$out = ob_get_contents();
ob_clean();
 
echo $out;
?>
<?php
function GetIP(){
    if(!empty($_SERVER["HTTP_CLIENT_IP"]))
        $cip = $_SERVER["HTTP_CLIENT_IP"];
    else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
        $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    else if(!empty($_SERVER["REMOTE_ADDR"]))
        $cip = $_SERVER["REMOTE_ADDR"];
    else
    $cip = "无法获取！";
    return $cip;
}
echo "<BR>访问IP: ".GetIP()."<br>";
echo "<BR>访问来路: ".$_SERVER["HTTP_REFERER"];
?>