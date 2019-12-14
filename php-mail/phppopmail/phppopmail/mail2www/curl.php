<?php
// create a new curl resource
$ch = curl_init();
// set URL and other appropriate options  
curl_setopt($ch, CURLOPT_URL, "http://walk.cmded.net/mail2www/imap.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // grab URL, and return output  
    $output = curl_exec($ch);
    // close curl resource, and free up system resources
    curl_close($ch);
    // Replace ¡®Google¡¯ with ¡®PHPit¡¯
    $output = str_replace('Google', 'PHPit', $output);
     // Print output 
    echo $output;
?>
    