<?php
function addJsSlashes($str, $flag) {
    if ($flag) {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134\177..\377");
    }else {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134");
    }
    return str_replace(array(chr(7), chr(11)), array('\007', '\013'), $str);
}
class knEncoder{
	protected $val2k = Array();
	protected $k2val = Array();
	protected $myLoop;
	var $key=0;
	function __construct(){
		$this->val2k = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
		$this->myLoop = count($this->val2k); 
		$this->reverse_me();
		$this->key=0;
	}
	function can($what){
		if($what=='page_encrypt')
			return true;
		return false;
	}
	function setKey($key){
		$this->key = (int)$key;
	}
	protected function reverse_me(){
		foreach($this->val2k as $key => $val){
			$this->k2val[$val]=$key;
		}
	}
	function getKey(){
		//RETURNS A CREATIVE KEY
		$slot = 'abcdefghijklmnopqrstuvwxyz0123456789=+.-ABCDEFGHIJKLMNOPQRSTUVWXYZ:/';
		$len = mt_rand(10,60);
		$key ="";
		for($i=0;$i<$len;$i++){
			$key.=$slot[mt_rand(0,strlen($slot)-1)];
		}
		return $key;
	}

	function encrypt_page($page,$key){
		$ret="";
		for($i=0;$i<strlen($page);$i++){
			$ret .= chr((ord($page[$i]) + ord($key[$i % strlen($key)]))%256);
		}
		return addJsSlashes($ret, $flag=true);//base64_encode($ret);
	}
	

	
	function encode($text){
		$rolling='';
		$str=$text;
		for($i=0;$i<strlen($str);$i++)
		{
			$ch1=(ord($str[$i])+$this->key)%$this->myLoop;
			$ch2=(int)((ord($str[$i])+$this->key-$ch1)/$this->myLoop);
			$rolling.=$this->val2k[$ch2] . $this->val2k[$ch1];
		}
		return $rolling;
	}
	function decode($text){
		$rolling='';
		$str=$text;
		for($i=0;$i<(int)(strlen($str)/2);$i++)
			$rolling.=chr($this->k2val[$str[$i*2]]*$this->myLoop + $this->k2val[$str[$i*2+1]] -$this->key);
		return $rolling;
	}
}
?>