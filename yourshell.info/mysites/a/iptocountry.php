<?php
	
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
	echo $countryName;
	
?>