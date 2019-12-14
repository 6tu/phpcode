<?php
/****************************
* Parser to parse a webpage
****************************/
class knParser{
	var $url_prefix = '';
	var $url='';
	var $source='';
	var $type='';
	var $output='';
	var $charset='';
	var $extraParseEngine=false;
	var $stdEncoder=false;
	var $values=Array();
	function __construct($page_url,$page_data,$url_prefix=""){
		$this->url = $page_url;
		$this->source = $page_data;
		$this->stdEncoder = false;
		$this->url_prefix = $url_prefix;
	}
	function setMimeType($mime_type){
		$mime_type=preg_replace('~;.*$~','',$mime_type);
		$this->type=preg_replace('~\s~','',$mime_type);
	}
	function getCharset($mime_raw,$page_raw){
		$this->charset = preg_replace('~^(.*);\s*charset=(.*)$~','$1',$mime_raw);
		if($this->charset==""){
			preg_match('~<\s*meta\s+http-equiv\s*=\s*"content-type".*content\s*=\s*".*;\s*charset=(.*)"~iUs',$page_raw,$pmatch);
			$this->charset = $pmatch[1];
		}
		return $this->charset;
	}
	function setEncoder($encoder){
		$this->stdEncoder = $encoder;
	}
	function set_value($name,$val){
		$this->values[$name]=$val;
	}
	function get_value($name,$def){
		if(isset($this->values[$name]))
			return $this->values[$name];
		return $def;
	}
	function parse(){
		switch($this->type){
			case 'knproxy/noparse':$this->output = $this->source;break;
			case 'text/css':$this->parseCss();break;
			case 'text/javascript':
			case 'application/javascript':
			case 'application/x-javascript':$this->parseJS();break;
			case 'image/gif':
			case 'image/png':
			case 'image/jpeg':$this->output = $this->source;break;
			case 'text/html':
			default:$this->parseHTML();break;
		}
		if($this->extraParseEngine!=false && $this->extraParseEngine instanceof stdParseEngine){
			$this->output = $this->extraParseEngine->parse($this->type,$this->url,$this->output);
		}
		if($this->get_value('use_page_encryption',false)){
			if($this->stdEncoder->can('page_encrypt') && ($this->type=="text/html" || $this->type=="")){
				$key = $this->stdEncoder->getKey();
				$this->output = $this->stdEncoder->encrypt_page($this->output,$key);
				$this->set_value('key',$key);
			}
		}
	}
	function parseRawCSS($css){
		$css = preg_replace_callback('~(url|src)\(\s*([^\s].*)\s*\)~iUs',array('self','parseSimpleURL'),$css);
		return $css;
	}
	function parseStyle($callback){
		return $callback[1] . $callback[2]. $this->parseRawCSS($callback[3]) . $callback[2];
	}
	function parseCss($source = false){
		if(!$source)
			$css = $this->source;
		elseif(is_array($source)){
			$heading = $source[1];
			$css = $source[2];
		}
		$css = $this->parseRawCSS($css);
		if(!$source)
			$this->output = $css;
		else
			return $heading . $css . '</style>';
	}
	function parseJS(){		
		$this->output = $this->source;//NOT IMPLEMENTED YET
	}
	function parseScriptTagURLReloc($url){
		$delim = $url[1];
		$urlA = $url[2];
		$tmp = $this->decodeJSURL($urlA);
		$urlDec = $this->url->getAbsolute($tmp[0]);
		if($this->stdEncoder!=false)
			$urlDec = $this->stdEncoder->encode($urlDec);
		$urlDec = $this->escapeJS($this->url_prefix . $urlDec);
		return 'location.replace(' . $delim . $urlDec . $delim . ')'; 
	}
	function parseScriptTag($matches){
		$tagInner = preg_replace_callback('~location\.replace\(([\'"])(.*)\1\)~iUs',Array('self','parseScriptTagURLReloc'),$matches[2]);
		return '<script ' . $matches[1] . '>' . $tagInner . '</script>';
	}
	function parseHTML(){
		//BY FAR THE MOST DIFFICULT
		$code = preg_replace_callback('~(href|src|codebase|url|action)\s*=\s*([\'\"])?(?(2) (.*?)\\2 | ([^\s\>]+))~isx',array('self','parseExtURL'),$this->source);
		$code = preg_replace_callback('~(<\s*style.*>)(.*)<\s*/\s*style\s*>~iUs',Array('self','parseCSS'),$code);
		$code = preg_replace_callback('~(style\s*=\s*)([\'\"])(.*)\2~iUs',Array('self','parseStyle'),$code);
		$code = preg_replace_callback('~<script(\s*.*)>(.*)<\s*/\s*script>~iUs',Array('self','parseScriptTag'),$code);
		$this->output = $code;
	}
	function parseSimpleURL($matches){
		$url = $matches[2];
		$method = $matches[1];
		$url = preg_replace('~^"(.*)"$~iUs','$1',$url);//REMOVE FILTERS
		$url = preg_replace('~^\s*(.*)\s*$~iUs','$1',$url);
		$url = preg_replace('~^\'(.*)\'$~iUs','$1',$url);
		if(strtolower(substr($url,0,5))=='data:')
			return $method . '(' . $url . ')';
		$url = $this->url->getAbsolute($url);
		$encoder = $this->stdEncoder;
		if($encoder!=false){
			$url = $this->url_prefix . $encoder->encode($url);
		}
		return $method . '(' . $url . ')';
	}
	function decodeJSURL($jsURL){
		$purl = preg_replace('~\\\\~iUs','',$jsURL);
		if($purl[0]=='"' || $purl[0]=="'")
			$sep = $purl[0];
		else
			$sep = '';
		$purl = preg_replace('~["\']~iUs','',$purl);
		return Array($purl,$sep);
	}
	function escapeJS($text){
		return preg_replace('~([/"\'])~','\\\$1',$text);
	}
	function parseExtURL($matches){
		$method = $matches[1];
		$delim = $matches[2];
		if($delim=='')
			$url = $matches[4];
		else
			$url = $matches[3];
		if( strtolower(substr($url,0,11)) == 'javascript:' || isset($url[0]) && $url[0] == '#' || strtolower(substr($url,0,5))=='data:'){
			return $method . '=' . $delim . $url . $delim . ' ';//NO PARSE
		}
		if(substr($url,0,1)=='\\' && $delim==''){
			$tpurl = $this->decodeJSURL($url);
			if($tpurl!='#'){
				$u = $this->url->getAbsolute($tpurl[0]);
				$encoder = $this->stdEncoder;
				if($encoder!=false)
					$u = $this->url_prefix . $encoder->encode($u);
				return $method . '=' . $this->escapeJS($tpurl[1].$u.$tpurl[1]);
			}else{
				return $method . '=' . $this->escapeJS($tpurl[1] . '#' . $tpurl[1]);
			}
		}
		$url = $this->url->getAbsolute($url);
		$encoder = $this->stdEncoder;
		if($encoder!=false){
			$url = $this->url_prefix . $encoder->encode($url);
		}
		$new = $method . '=' . $delim . $url . $delim . ' ';
		return $new;
	}
}

?>