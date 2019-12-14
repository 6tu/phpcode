<?php
print_r($_SERVER);

function getIP() {
        if (getenv('HTTP_CLIENT_IP')) {
				$ip = getenv('HTTP_CLIENT_IP'); 
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) { //获取客户端用代理服务器访问时的真实ip 地址
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
echo "<br><br>".getIP();
?>