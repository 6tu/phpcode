<?php
/*
Name:TwoFish  encryption
About: Handles encryption / decrytion of TwoFish  ciphers
Author: Greg Sidberry [ gregsidberry.com ]
Requires:None
Vars:None

*/
class TwoFish{
	var $mTwoFish=array();
	
function TwoFish(){
	//initalise mcrypt and needed vars
	srand((double)microtime()*1000000 );
	$this->mTwoFish['td']=mcrypt_module_open(MCRYPT_TWOFISH, '',  MCRYPT_MODE_CBC, '');
	$this->mTwoFish['iv']=mcrypt_create_iv(mcrypt_enc_get_iv_size($this->mTwoFish['td']), MCRYPT_RAND);
    $this->mTwoFish['ks'] = mcrypt_enc_get_key_size($this->mTwoFish['td']);
	
}
function makeKey($key1,$key2){
	//encryption requires two keys. we use the passsed keys if available, else we get a md5 hash of current timestamp.
	
	$key1 = ($key1===false) ? md5(time()) : $key1;
    $key2 = ($key2===false) ? md5(time()) : $key2;

//now create the key using the two keys passed - taken from php.net
    $key = substr($key1, 0, $this->mTwoFish['ks']/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $this->mTwoFish['ks']/2);
    $key = substr($key.$key1.$key2.strtoupper($key1),0,$this->mTwoFish['ks']);
	$this->mTwoFish['key']=$key;
	
	
	
}
function encrypt($value){
	//init encryption
	mcrypt_generic_init($this->mTwoFish['td'], $this->mTwoFish['key'], $this->mTwoFish['iv']);
	//encypt data
	$encrypted = mcrypt_generic($this->mTwoFish['td'], $value);
	//terminate encryption handler
	 mcrypt_generic_deinit($this->mTwoFish['td']);
	
	 //return encrypted value
	 return $encrypted;
}
function decrypt($value){
	;
	//init encryption
	mcrypt_generic_init($this->mTwoFish['td'], $this->mTwoFish['key'], $this->mTwoFish['iv']);
	//encypt data
	$decrypted = mdecrypt_generic($this->mTwoFish['td'], $value);
	//terminate encryption handler
	 mcrypt_generic_deinit($this->mTwoFish['td']);
	 return $decrypted;
	
	
}

function close(){
	mcrypt_module_close($this->mTwoFish['td']);
}
}
?>