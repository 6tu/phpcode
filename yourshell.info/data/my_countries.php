<?php
function my_countries($my_countries,$use_ip2nation){    //”…‰Ø¿¿∆˜”Ô—‘∫Õ IP ≈–∂œ
if ($use_ip2nation) {
$server   = 'localhost'; // MySQL hostname
$username = 'root'; // MySQL username
$password = 'rootwdp'; // MySQL password
$dbname   = 'walk_ip2nation'; // MySQL db name

$db = mysql_connect($server, $username, $password) or die(mysql_error());
      mysql_select_db($dbname) or die(mysql_error());
$sql = 'SELECT 
            country
        FROM 
            ip2nation
        WHERE 
            ip < INET_ATON("'.$_SERVER['REMOTE_ADDR'].'") 
        ORDER BY 
            ip DESC 
        LIMIT 0,1';
list($country) = mysql_fetch_row(mysql_query($sql));
echo $country;
if (!in_array($country, $my_countries)) {
$info = "\r\n<br><br>people from outside of china may not use this proxy ,please visit http://clearwisdom.net\r\n<br><br>";
//$info .= encrypt_string($info);
echo $info;
exit;
}
}
}
$my_countries = array('us', 'ca', 'uk', 'fr', 'de', 'it', 'nl');
$use_ip2nation = true; // set this to true to block countries other than from above array

echo my_countries($my_countries,$use_ip2nation);







function onlychina($line){    //”…‰Ø¿¿∆˜”Ô—‘∫Õ IP ≈–∂œ

$server   = 'localhost'; // MySQL hostname
$username = 'walk_ip2nation'; // MySQL username
$password = '0000000'; // MySQL password
$dbname   = 'walk_ip2nation'; // MySQL db name

$db = mysql_connect($server, $username, $password) or die(mysql_error());
      mysql_select_db($dbname) or die(mysql_error());

$sql = 'SELECT 
            c.country 
        FROM 
            ip2nationCountries c,
            ip2nation i 
        WHERE 
            i.ip < INET_ATON("'.$_SERVER['REMOTE_ADDR'].'") 
            AND 
            c.code = i.country 
        ORDER BY 
            i.ip DESC 
        LIMIT 0,1';

list($countryName) = mysql_fetch_row(mysql_query($sql));

// Output full country name
//echo $countryName;

if(strstr($countryName,'China')===FALSE){
$info = "\r\n<br><br>people from outside of china may not use this proxy ,please visit http://clearwisdom.net\r\n<br><br>";
$info .= encrypt_string($info);
echo $info;
exit;
}
}



?>