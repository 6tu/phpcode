<?php

/* SS-panel - 免登陆查看器
 *
 * 作者: 
 * 版本: 
 * 更新日期: 2015.10.23
 * 
 * 使用环境:
 * 
 *     适用于 SS-panel v2
 *     服务器要求支持PHP 和 curl扩展
 *     目前不支持 HTTPS 访问的站点
 *     在同目录下建立 tmp 文件夹
 *  
 * 用法:  更换函数中的 $host 。。。
 * 
 * $ret = getData($host, $data, $cookie_jar);
 * ret2json($ret,$host);
 * 
 * $ret = getData($host1, $data, $cookie_jar);
 * ret2json($ret,$host);
 * 
 * $ret = getData($host2, $data, $cookie_jar);
 * ret2json($ret,$host);
 * 
 */


header("Content-type: text/html; charset=utf-8");
set_time_limit(30);
date_default_timezone_set('Asia/Shanghai');

/*---------- 设置区------------------*/

$host = 'http://104.161.17.138/';   //$skey=4, $node_label='<td>'
$host1 = 'http://ss.myphoto.wang/'; //$skey=1, $node_label='<a class="btn btn-xs'
$host2 = 'http://yy.yy.yy/';
$data = array(
'email' => 'user@email.com', // email 账号
'passwd' => 'password',       // 申请时的密码
'remember_me' => '1'
);
$cookie_jar = tempnam('./tmp', 'JSESSIONID');

$title = '<html><head><title>SS-panel - 免登陆查看器</title></head><body>';

/*---------- 结束------------------*/

echo gbk2utf8($title);

// 更换 函数中的 $host 。。。
$ret = getData($host1, $data, $cookie_jar, $node_label='<a class="btn btn-xs'); // 如果节点数据获取失败，切换 <td>
ret2json($ret,$host1,$skey=1);     // 如果节点数据获取失败，$skey

echo '<hr><center>&copy; 2015</body></html>';

function ret2json($ret, $host, $skey){
    $ret = utf8togbk($ret);
    // $ret = mb_ereg_replace('：', ": ", $ret);
    $ret = str_replace('：', ": ", $ret);
    $ret = str_replace(':', ": ", $ret);
    $ret = str_replace('  ', " ", $ret);
    echo '<p><p><small>' . $host . "\r\n <br>---------------------------<br> \r\n\r\n";
    echo gbk2utf8($ret) . "</small>\r\n" . gbk2utf8('============================= JSON 格式');
    
    // 建立 json 格式
    $ss_array = explode("---------------------------", $ret);
    
    $ss_two_array = explode('</p>', $ss_array[1]);
    $ss_port = preg_replace('/[^0-9]/', '', $ss_two_array[0]);
    $ss_pw_array = explode(' ', $ss_two_array[1]);
    $ss_pw = $ss_pw_array[3];
    
    // 当 mbstring 没有加载时使用
    // $ss_pw = preg_replace('/\s(?=\s)/', '', strip_tags($ss_two_array[1]));
    // $ss_pw = substr($ss_pw, -8);
    $node = '';
    $ss_one_array = explode('<p>', $ss_array[0]);
    $n = count($ss_one_array);
    for($i = 1; $i <= $n-2; $i++){
         $ss_one_array[$i] = trim($ss_one_array[$i]);
         $ss_method_array = explode(" ", $ss_one_array[$i]);
         $method = end($ss_method_array);
         $server = $ss_method_array[$skey];
         $json = "\r\n";
         $json .= '{' . "\r\n";
         $json .= '"server":"' . $server . '",' . "\r\n" ;
         $json .= '"server_port":' . $ss_port . ',' . "\r\n" ;
         $json .= '"local_port":1080,' . "\r\n" ;
         $json .= '"password":"' . $ss_pw . '",' . "\r\n" ;
         $json .= '"timeout":600,' . "\r\n" ;
         $json .= '"method":"' . $method . '"' . "\r\n" ;
         $json .= '}' . "\r\n" ;
         $node .= $json;

         echo "\r\n <br><br> \r\n" . $json;
         }
	$fn = str_replace('/', "", $host);	
	$fn = str_replace('http:', "", $fn);	
	$fn = './tmp/'.$fn.'_node_json.txt';
	file_put_contents($fn, $node);
    echo "\r\n\r\n".gbk2utf8('<br><br>============================= JSON 文件<p><a href = "'.$fn.'">    node_json.txt</a></p>');

    }

function getData($host, $data, $cookie_jar, $node_label){

    $url_login = $host . 'user/_login.php';
    $url_node = $host . 'user/node.php';
    $url_index = $host . 'user/index.php';
    $url_node_json = $host . 'user/node_json.php?id=2';
    $timeout = 300;

    // 登陆，保存Cookie
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_login);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $host);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $handles_login = curl_exec($ch);
	
	$handles_login = substr($handles_login, 9, 1);
	if($handles_login == 0){
	echo gbk2utf8('false,Please configure your user name and password<br>失败，可能是用户名和密码填写错误');
	exit();
	} 
	
    // 节点列表
    curl_setopt($ch, CURLOPT_URL, $url_node);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $handles_node = curl_exec($ch);
	
    // 端口+密码
    curl_setopt($ch, CURLOPT_URL, $url_index);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $handles_index = curl_exec($ch);
    curl_close($ch);
	
    unlink($cookie_jar);
	
    // 抓取网页完成，提取数据

    $explode_str1 = '<!-- Content Wrapper. Contains page content -->';
    $explode_str2 = '<footer class="main-footer">';
    $key1 = 1;
    $key2 = 0;
    $handles_index = getbody($handles_index, $explode_str1, $explode_str2, $key1, $key2);
    $handles_node = getbody($handles_node, $explode_str1, $explode_str2, $key1, $key2);
	
    $explode_str1 = '<div class="box-body">';
    $explode_str2 = '</div>';
    $key1 = 4;
    $key2 = 0;
    $handles_index = getbody($handles_index, $explode_str1, $explode_str2, $key1, $key2);

    $handles_node_array = explode("\n", $handles_node);
    $n = count($handles_node_array);
    $handles_node = '';
    for($i = 0; $i <= $n; $i++){
        //if(strpos($handles_node_array[$i], '<a class="btn btn-xs') !== false){
		if(strpos($handles_node_array[$i], $node_label) !== false){
            //$handles_node .= trim($handles_node_array[$i]);
			$handles_node .= $handles_node_array[$i]."\n";
            }
        }
	$handles_node = str_replace('Disable', "", $handles_node);	
	$handles_node = str_replace('Enable', "", $handles_node);
	$handles_node = str_replace("<td>\n", '<p>', $handles_node);
    $handles = $handles_node .'<p>---------------------------'. $handles_index;
	$handles = strip_tags($handles,'<p>');
	$handles = preg_replace('/\s(?=\s)/', '', $handles);
	$handles = str_replace('<p>', "\r\n<p>", $handles);

    return $handles;
    }

function getbody($str, $explode_str1, $explode_str2, $key1, $key2){
    $array1 = explode($explode_str1, $str);
    $str1 = $array1[$key1];
    $array2 = explode($explode_str2, $str1);
    $str2 = $array2[$key2];
    return $str2;
    }
	
function gbk2utf8($str){
    $char='GB2312';
    if(extension_loaded('mbstring')){
        $str = mb_convert_encoding($str, 'UTF-8', $char);
        }else if(extension_loaded('iconv')){
        $str = iconv($char, 'UTF-8//IGNORE//TRANSLIT', $str);
        }else{
        $str = $str;
        }
    return $str;
    }
function utf8togbk($str){
    $char='UTF-8';
    if(extension_loaded('mbstring')){
        $str = mb_convert_encoding($str, 'GB2312', $char);
        }else if(extension_loaded('iconv')){
        $str = iconv($char, 'GB2312//IGNORE//TRANSLIT', $str);
        }else{
        $str = $str;
        }
    return $str;
    }
	
?>