<?php
/*
$url = $_POST['q'];
print_r($_REQUEST);
echo '<br />';
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if (function_exists("curl_init")) {
		if ($_GET["test"] == null) {
			//header( "HTTP/1.1 301 Moved Permanently" );
			//header( "Location: https://127.0.0.1/d.php" );
			echo "aa";
		} else {
			echo 'Hello Opera Mini Server! Fuck GFW!';
		}
	} else {
		echo 'cURL is not enabled.';
	}
} else { 
*/
	$curlInterface = curl_init();
	$headers[] = 'Connection: Keep-Alive';
	$headers[] = 'content-type: application/xml';
	$headers[] = 'User-Agent: Java0';
	curl_setopt_array($curlInterface, array(
		CURLOPT_URL => 'http://127.0.0.1/pe/',
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_POST => 1,
		CURLOPT_POSTFIELDS => @file_get_contents('php://input')) #获取客户端提交的说有数据
    );
	$result = curl_exec($curlInterface);
	curl_close($curlInterface);
	header('Content-Type: application/octet-stream');
	header('Cache-Control: private, no-cache');
	echo $result;
#}
?>
