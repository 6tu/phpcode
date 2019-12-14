<?php
class knHttp{
	var $url='';
	var $is_secure=false;
	var $user_agent='';
	var $cookies=Array();
	var $httpauth="";
	var $http_post=Array();
	var $http_get=Array();
	var $content;
	var $headers;
	var $doctype;
	function __construct($url){
		$this->url = $url;
		$this->user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(strtolower(substr($this->url,0,6))=='https:')
			$this->is_secure=true;
		else
			$this->is_secure=false;
		if(!function_exists('curl_init'))
			return false;
	}
	function setUrl($url){
		$this->__construct($url);
	}
	function setCookies($cookies){
		$this->cookies = $cookies;
	}
	function setPost($post){
		$this->http_post=$post;
	}
	function setLogin($unam,$pass){
		if($unam!=false){
			$this->httpauth=$unam . ':' . $pass;
		}else{
			$this->httpauth='';
		}
	}
	function getPost(){
		$post = $this->http_post;
		if(!is_array($post) ||  count($post)<1){
			return '';
		}else{
			$ret="";
			$curr=0;
			foreach($post as $name=>$value){
				if($curr!=0){
					$ret.='&';
				}
				$ret.=$name . '=' . $value;
				$curr++;
			}
			return $ret;
		}
	}
	function getCookies(){
		$cookies = $this->cookies;
		if(!is_array($cookies) ||  count($cookies)<1){
			return '';
		}else{
			$ret="";
			$curr=0;
			foreach($cookies as $name=>$value){
				if($curr!=0){
					$ret.=';';
				}
				$ret.=$name . '=' . $value;
				$curr++;
			}
			return $ret;
		}
	}
	function send(){
		$ch = curl_init();
		@curl_setopt($ch, CURLOPT_URL, $this->url);
		@curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($this->is_secure){
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);
		}
		if(count($this->http_post)>0){
			curl_setopt($ch, CURLOPT_POST,count($this->http_post));
			curl_setopt($ch, CURLOPT_POSTFIELDS,$this->getPost());
		}
		if(count($this->cookies)>0){
			@curl_setopt($ch, CURLOPT_COOKIE, $this->getCookies());
		}
		if($this->httpauth!=''){
			@curl_setopt($ch, CURLOPT_USERPWD, $this->httpauth);
		}
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
		$raw = curl_exec($ch);
		$this->doctype = @curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		$spl = preg_split('~\n\r*\n\r*~',$raw,2);
		$this->headers = @$spl[0];
		$this->content = @$spl[1];
	}
	function parseHeader(){
		$headers = explode("\n",preg_replace('~\r~','',$this->headers));
		foreach($headers as $header){
			if(preg_match('~http/1\.\d+ (\d+) ~iUs',$header,$matches))
				$head['status'] = (int)$matches[1];
			if(preg_match('~^location\s*:\s*(.*)$~iUs',$header,$matches))
				$head['location'] = preg_replace('~\s~','',$matches[1]);
			if(preg_match('~set-cookie:(.+)$~iUs',$header,$matches)){
				$ckTemp = preg_replace('~;.*$~','',$matches[1]);
				$cookie = explode('=',preg_replace('~\s~','',$ckTemp));
				$head['cookies'][$cookie[0]]=$cookie[1];
			}
			if(preg_match('~^www-authenticate\s*:\s*(\w)\s*~is',$header,$m)){
				$head['www-authenticate-mode']=$m[1];
			}
			if(preg_match('~^www-authenticate\s*:.*realm="(.*)"~is',$header,$m)){
				$head['www-authenticate-realm']=$m[1];
			}
		}
		return @$head;
	}
}
?>