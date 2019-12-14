<?php
/**
 *web:      echo("usage: " .$_SERVER['PHP_SELF']. "?ip=<ip_address>\r\n<br>");
 *php-cli:  /usr/local/bin/php -f dbip.php <ip_address>
 */

//=========== 分析提交的数据 ===========//
if(isset($argc)){
	if(empty($argv[1])) exit("usage: {$argv[0]} <ip_address>\n");
	else{
		$ip_addr = gethostbyname($argv[1]);
		$host = $ip_addr;
	}
}else if(empty($_GET['ip'])){
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	#$ip_addr = '192.210.192.248';
	$host = $ip_addr;
}else{
	$ip_addr = gethostbyname($_GET['ip']);
    if(!filter_var($ip_addr, FILTER_VALIDATE_IP)) $ip_addr = $_SERVER['REMOTE_ADDR'];
	$host = $_GET['ip'];
}
$hostname = gethostbyaddr($ip_addr);
if($host == $ip_addr) $host = '';
if($hostname == $ip_addr) $hostname = '';

$linefeed = "\r\n";

//=========== 获取 ipip api 的数据 ===========//
#$ipip = 'http://freeapi.ipip.net/?ip=' . $ip_addr . '&lang=EN';
$ipip = 'http://freeapi.ipip.net/' . $ip_addr;
$ipinfo = getipinfo($ipip);
$ipinfo = json_decode($ipinfo);

$data = array("ip" => $ip_addr);
$url = 'http://www.ipip.net/ip/ajax/';
$ipip = $linefeed . parse_url($url)['host'] . $linefeed;
$result = http($url, $data, $json = false);
$result = preg_replace('#<script[^>]*?>.*?<\/script\s*>#si', '', $result);
$array1 = explode('<table', $result);
$array2 = @explode('</table>', $array1[2]);

$html = @strip_tags('<table' . $array1[1], '<span>');
$html .= strip_tags('<table' . $array2[0] .'</table>', '<td>');
$html = strip_space_enter($html, $tags = '<span><td>', $n = 50);
$html = strip_space_enter($html, $tags = '<span><td>', $n = 30);
$html = str_replace(array('</span>', '<td>',), array("</span>\r\n", "\r\n<td>"),  $html);

$array = explode("\r\n", $html);
foreach($array as $v){
	if(strstr($v, '<span id="myself">')) $iplocation = $v;
}
$iplocation = @str_replace(array('</span>', '<span id="myself">'), array(""),  $iplocation);

$html = str_replace(array('</td>', "\r\n"), array(""),  $html);
$array = explode('<td>', $html);

if(count($array) > 4){
    $asn = $array[4];
    $ipsegment = $array[5];
    $isp = $array[6];
}else{
    $asn = @$array[1];
    $ipsegment = @$array[2];
    $isp = @$array[3];
}
$ipip_array = array(
                    'IP Address' => $ip_addr, 
                    'Domain' => $host, 
                    'Hostname' => $hostname, 
                    'IPlocation' => $iplocation, 
                    'Country' => $ipinfo[0],
                    'Region' => $ipinfo[1],
                    'City' => $ipinfo[2],
                    'ISP/Organization' => $ipinfo[4],
                    'ASN' => $asn, 
                    'IPsegment' => $ipsegment, 
                    'ISP' => $isp
                );

$ipip_array = array_filter($ipip_array);

# 打印数据到客户机

if(!empty($_GET['ips'])) exit(json_encode($ipip_array));



$tdpre = '<tr><td style="width:150px;"><pre>';
$tdoff = '</pre></td></tr>';
$td = '</td><td style="width:5px;"></td><td style="width:500px;"><pre>';

$html = html_form($ip_addr);

//--------------------------------//
$html .= '<div id="content"><br><b>从' .$ipip. '查找的结果</b><br>';
$html .= '<table>';
foreach($ipip_array as $key => $value){
	$html .= $tdpre . $key . $td . $value . $tdoff;;
}
$html .= '</table></div><br>';



$footer = '<br>Copyright ©2017<br>';
$html .= '<br> <hr style="width:660px;">'.$footer.'<center><br><br></body></html>';
echo ($html);
//echo beautify_html($html);


/************** 函数区，无需修改 ************/

# JSON 转数组
function json2array($html){
	$obj = json2object($html);
	$html = object2array($obj);
	return $html;
}

# JSON 转 object
function json2object($html){
    $html = rtrim($html, ",");
	$html = str_replace(array('/',), array('\/',), $html); // 增加需要转义的字符
    $html = '{'. $html .'}';
    $html = json_decode($html);
	return $html;
}

# object 转数组
function object2array(&$object){
	$object = json_decode(json_encode($object),true);
	return $object;
}

# 解析 XML
function xml_parser($str){ 
    $xml_parser = xml_parser_create(); 
    if(!xml_parse($xml_parser,$str,true)){ 
        xml_parser_free($xml_parser); 
        return false; 
    }else { 
        return (json_decode(json_encode(simplexml_load_string($str)),true)); 
    } 
}

# 删除空格和回车
function strip_space_enter($html, $tags, $n){
	$html = strip_tags($html, $tags);
	$html = trim($html);
    for($i = 0; $i < $n; $i++){
        $html = str_replace(array(" \r\n", " \n", ' <', '> '), array("\r\n", "\n", '<', '>'), $html);
    }
    $html = str_replace(array("\r\n", "\n", "\r", '&nbsp;', "\t",), array(''), $html);
	return $html;
}

# 删除指定标签
function stripHtmlTags($tags, $str, $content = true)
{
    $html = [];
    // 是否保留标签内的text字符
    if($content){
        foreach (array($tags) as $tag) {
            $html[] = '/(<' . $tag . '.*?>(.|\n)*?<\/' . $tag . '>)/is';
        }
    }else{
        foreach (array($tags) as $tag) {
            $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/is";
        }
    }
    $data = preg_replace($html, '', $str);
    return $data;
}

# form 表单
function html_form($ip){
    $title = '查找的IP为' . $ip;

	$html = '';
    $html .= '<html><head><meta http-equiv="content-type" content="text/html;charset=utf-8">';
    $html .= '<title>' . $title . '</title>';
    $html .= '<style type="text/css">';
    $html .= 'pre{border:dashed 1px green;padding:6px; background-color:#C1CDCD;color:#000000; font-size:15px}';
    $html .= 'td{padding:2px;}';
    $html .= '#search {width:660px;float:center;padding:1px;}';
    $html .= '.searchbox {width:570px;padding:4px;font-size: 1em;}';
    $html .= '.searchbtn {width:90px;padding:4px;background-color:green;border: 0;font-size: 16px;color:#000000;}';
    $html .= '#content {float:center;}';
    $html .= '</style></head>';
    
    $html .= '<body><br><center><div id="search">';
    $html .= '<b> 查找 IP 地址</b>';
    $html .= '<form  method="get" action="?ip="  id="searchform" onsubmit="return false;">';
    $html .= '<input class="searchbox" type="text" name="ip" value="g.cn" />';
    $html .= '<input class="searchbtn" type="submit" onclick="window.location.href=this.form.action + this.form.ip.value;" />';
    $html .= '</form></div>';
	
    return $html;
}

# 用 CURL 获取网页内容
function getipinfo($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	if($httpCode == 404) return false;
    else{
        return $data;
	}
}

# 用curl模拟post发送json数据
# http://blog.csdn.net/pangchengyong0724/article/details/52103962
function http($url, $data, $json){
	
	$ref = parse_url($url)['scheme'] . '://' . parse_url($url)['host'] . '/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_REFERER, $ref);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, "gzip");
    if(!empty($data)){
        if($json && is_array($data)){
            $data = json_encode($data);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        if($json){
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							'Content-Type: application/x-www-form-urlencoded; charset=utf-8', 
							'Content-Length:' . strlen($data)
							)
						);
        }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $errorno = curl_errno($ch);
    if($errorno){
        return array('errorno' => false, 'errmsg' => $errorno);
    }
    curl_close($ch);
	return $res;
    //return json_decode($res, true);
}

# HTML 格式化
function beautify_html($html){
    $tidy_config = array(
        'clean' => false,
        'indent' => true,
        'indent-spaces' => 4,
        'output-xhtml' => false,
        'show-body-only' => false,
        'wrap' => 0
        );
    if(function_exists('tidy_parse_string')){ 
        $tidy = tidy_parse_string($html, $tidy_config, 'utf8');
        $tidy -> cleanRepair();
        return $tidy;
    }
    else return $html;
}


