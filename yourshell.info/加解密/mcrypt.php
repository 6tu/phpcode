<?php 
// designate string to be encrypted 
$string = "applied cryptography, by bruce schneier, is a wonderful cryptography reference.\r\n<br><br>"; 

// encryption/decryption key 
$key = "four score and twenty years ago"; 

// encryption algorithm 
$cipher_alg = mcrypt_rijndael_128; 

// create the initialization vector for added security. 
$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher_alg, mcrypt_mode_ecb), mcrypt_rand); 

// output original string 
print "original string: $string "; 

// encrypt $string 
$encrypted_string = mcrypt_encrypt($cipher_alg, $key, $string, mcrypt_mode_cbc, $iv); 

// convert to hexadecimal and output to browser 
print "encrypted string: ".bin2hex($encrypted_string)." \r\n<br><br>"; 
$decrypted_string = mcrypt_decrypt($cipher_alg, $key, $encrypted_string, mcrypt_mode_cbc, $iv); 

print "decrypted string: $decrypted_string"; 
?> 
