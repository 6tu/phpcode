<?php
# 5次，同IP地址注册等待三个小时。重复证书等待七天。

$domain_config = array(
    'test.6tu.me' => array('challenge' => 'dns-01')
	);


$accpkey = 'certdata/account_key.pem';
$certpkey= 'certdata/keys/'  . $domain .'cert_private_key.pem';
$chain   = 'certdata/certs/' . $domain .'fullchain.pem.pem';

require 'acmecert/ACMECert.php';
require 'dnsapi/dns_he.php';

# 泛域名使用 Staging Environment
$ac = new ACMECert();          #Live
// $ac = new ACMECert(false);  #Staging

echo '<pre>';
echo "\r\n<br> Generate RSA Private Key with Openssl. \r\n<br>";
$key = $ac -> generateRSAKey();
file_put_contents('account_key.pem', $key);

echo "\r\n<br> Register Account Key with Let'sEncrypt. \r\n<br>";
$ac -> loadAccountKey('file://'.$accpkey);
$ret = $ac -> register(true, 'info@6tu.me');
// print_r($ret);

echo "\r\n<br> Add ". $opts['value'] .'and '. $opts['key'] ." to DNS resolution service\r\n<br>"; 
$handler = function($opts)use($dns){
    $zone_id = dns_he_find_zone($opts['key'], $dns);
    dns_he_add($dns, $opts['key'], $opts['value'], $zone_id);
    return function($opts)use($dns, $zone_id){
        //dns_he_rm($dns, $opts['key'], $opts['value'], $zone_id);
    };
};

echo "\r\n<br>Get Certificate using dns-01 challenge\r\n<br>";
$pkey = file_get_contents($accpkey);
file_put_contents($certpkey, $pkey);
$fullchain = $ac -> getCertificateChain('file://'.$certpkey, $domain_config, $handler);
file_put_contents('fullchain.pem', $fullchain);
if(file_exists('fullchain.pem')){
    echo "\r\n<br>Looks like it's successful, please check fullchain.pem";
}else{
    echo "\r\n<br>Failed to make certificate";
}


