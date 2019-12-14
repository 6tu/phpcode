<?php

/**
 * +-----------------+------------------------------------------------------------+
 * |  名称           | PHProxy-encrypt 0.62                                       |
 * |  修改者         | yourshell.info （中国）                                    |
 * |  修改时间       | 2011年5月16日 下午                                         |
 * +-----------------+------------------------------------------------------------+
 * |  由于 PHProxy 的原作者 Abdullah Arif 停止更新，为了用这个小工具更好的        |
 * |  适应 GFW ，所以本人修改了部分代码，所以算是 PHProxy 0.5b2 的衍生本。        |
 * |  增加了 HTML 内容和 Cookies 的加密，对某些国别的来访者作了限制使用。         |
 * |                                                                              |
 * |  需要注意的是：虽然使用了加密，但是密码是明文传输的，所以解密也非常容        |
 * |  易，这只是绕过关键字检测的一种方法，绝不可以和 SSL 等同，况且发送的数       |
 * |  据是没有加密的，使用者需要斟酌使用。                                        |
 * |                                                                              |
 * +------------------------------------------------------------------------------+
 *
 * # 表示文字说明注释， // 表示单行代码注释
 */

error_reporting(E_ALL);

# 选项配置

$_config = array  # 资源配置
(
    'url_var_name'        => 'q',
    'flags_var_name'      => 'hl',
    'get_form_name'       => '____pgfa',
    'basic_auth_var_name' => '____pbavn',
    'max_file_size'       => -1,
    'allow_hotlinking'    => 1,
    'upon_hotlink'        => 1,
    'compress_output'     => 1
    );
$_flags = array  # 标签默认配置
(
    'include_form'    => 0,
    'source_page'     => 0,
    'encrypt_page'    => 0,
    'remove_scripts'  => 0,
    'accept_cookies'  => 1,
    'show_images'     => 1,
    'show_referer'    => 1,
    'static_url'      => 0,
    'rotate13_url'    => 0,
    'dynamic_url'     => 1,
    'strip_meta'      => 0,
    'strip_title'     => 0,
    'session_cookies' => 1
    );
$_frozen_flags = array  # 使用者自定义标签，0 表示可自定义
(
    'include_form'    => 0,
    'source_page'     => 0,
    'encrypt_page'    => 0,
    'remove_scripts'  => 0,
    'accept_cookies'  => 1,
    'show_images'     => 1,
    'show_referer'    => 1,
    'static_url'      => 0,
    'rotate13_url'    => 1,
    'dynamic_url'     => 0,
    'strip_meta'      => 1,
    'strip_title'     => 1,
    'session_cookies' => 1
    );

# 多语种相关代码，可自行添加语言

if(!isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])){
    $lang = 'en';
    }else{
    preg_match('/^([a-z\-]+)/i', @$_SERVER["HTTP_ACCEPT_LANGUAGE"], $matches);
    $lang = $matches[1];
    }
$lang = strtolower($lang);

switch($lang){
case 'zh-cn':
    $_labels = array
    (
        'include_form'    => array('包含表单', '在每个网页中附加一个微型URL表框'),
        'source_page'     => array('原网页', '保持原网页的编码，不加密'),
        'encrypt_page'    => array('加密HTML', '使用简单的加密算法加密HTML代码'),
        'remove_scripts'  => array('移除Scripts', '移除客户端使用的脚本 (如 JavaScript)'),
        'accept_cookies'  => array('接受Cookies', '允许cookies被保存'),
        'show_images'     => array('显示图片', '在浏览的网页上显示图片'),
        'show_referer'    => array('显示来源', '显示上次访问的网站'),
        'static_url'      => array('编码网址','编码后的网址不会随机变化'),
        'rotate13_url'    => array('Rotate13', 'Use ROT13 encoding on the address'),
        'dynamic_url'     => array('加密网址', '随机变化的加密网址'),
        'strip_meta'      => array('移除Meta标签', '移除网页上的Meta标签信息'),
        'strip_title'     => array('移除Title标签', '移除网页上的Title标签'),
        'session_cookies' => array('Session Cookies', '仅为相关 session 存储 cookies ')
        );
    $address = "网址 ";
    $go      = "提交";
    $reset   = "重置";
    $updir   = "上一级";
    $mp      = "主页";
    $form_charset = 'GBK';
    break;
case 'zh':
case 'zh-tw':
case 'zh-mo':
case 'zh-hk':
case 'zh-sg':
    $_labels = array
    (
        'include_form'    => array('包含表單', '在每個網頁中附加一個微型URL表框'),
        'source_page'     => array('原網頁', '保持原網頁的編碼，不加密'),
        'encrypt_page'    => array('加密HTML', '使用簡單的加密算法加密HTML代碼'),
        'remove_scripts'  => array('移除Scripts', '移除客戶端使用的腳本 (如 JavaScript)'),
        'accept_cookies'  => array('接受Cookies', '允許cookies被保存'),
        'show_images'     => array('顯示圖片', '在瀏覽的網頁上顯示圖片'),
        'show_referer'    => array('显示来源', '显示上次访问的网站'),
        'static_url'      => array('編碼網址','編碼網址'),
        'rotate13_url'    => array('Rotate13', 'Use ROT13 encoding on the address'),
        'dynamic_url'     => array('加密網址', '加密的網址'),
        'strip_meta'      => array('移除Meta標籤', '移除網頁中的Meta標籤信息'),
        'strip_title'     => array('移除Title標籤', '移除網頁中的Title標籤'),
        'session_cookies' => array('Session Cookies', '僅為相關 session 存儲 cookies ')
        );
    $address = "網址 ";
    $go      = "提交";
    $reset   = "重置";
    $updir   = "上一級";
    $mp      = "主頁";
    $form_charset = 'GBK';
    break;
default:
    $_labels = array
    (
        'include_form'    => array('Include Form', 'Include mini URL-form on every page'),
        'source_page'     => array('Source HTML', 'Keep Source HTML coding'),
        'encrypt_page'    => array('encrypted HTML', 'encrypted HTML'),
        'remove_scripts'  => array('Remove Scripts', 'Remove client-side scripting (i.e JavaScript)'),
        'accept_cookies'  => array('Accept Cookies', 'Allow cookies to be stored'),
        'show_images'     => array('Show Images', 'Show images on browsed pages'),
        'show_referer'    => array('Show Referer', 'Show actual referring Website'),
        'static_url'      => array('encoding URL','Use static encoding on the address'),
        'rotate13_url'    => array('Rotate13', 'Use ROT13 encoding on the address'),
        'dynamic_url'     => array('Encrypt URL', 'Use randdom encrypt on the address'),
        'strip_meta'      => array('Strip Meta', 'Strip meta information tags from pages'),
        'strip_title'     => array('Strip Title', 'Strip page title'),
        'session_cookies' => array('Session Cookies', 'Store cookies for this session only')
        );
    $address = "URL ";
    $go      = "GO";
    $reset   = "Reset";
    $updir   = "up one dir";
    $mp      = "main page";
    $form_charset = 'ISO-8859-1';
    break;
    }

# 其他选项

# 黑名单，屏蔽的域名，IP 地址
$_hosts = array
(
    // '#^127\.|192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.|localhost#i'
    );
	
# 白名单，特殊的域名，IP 地址将指向 DTW

$white_hosts = 1;  # 1 为开启
$white_list = array
(
    'dongtaiwang.com',
    'sf.net',
    'twitter.com',
    );

$_hotlink_domains = array();  # 外链的域名，IP 地址

$_insert = array();  # 插入额外的代码，未启用

# 屏蔽一些国家的访问者。 请下载并导入ip2nation，然后设置 my_countries() 函数

// $use_ip2nation= 1;  # 设置为 1 屏蔽下面的国家访问者 
// $my_countries=array('us', 'ca', 'uk', 'fr', 'de', 'it', 'nl');
// echo my_countries($my_countries,$use_ip2nation);

# 默认代理网站

$_myhost = '0';  # 1 为开启
$_one_host_url = 'http://localhost/phpbb2/';  # 完整的URL


# 常用书签(mylink)

$_mylink = 1;  # 1 为开启
$mylink_charset = 'GBK';  # 请注明书签所用的字符集合
$link = array
(
    array('http://cmded.net/forum/', 'CMDeD 论坛',''),
    array('http://www.google.com/', 'google 搜索','google'),
    array('http://localhost/wp', ' Wordpress 本机','Wordpress'),
    );


# 用户认证

$Login = 0;  # 1 为开启
$user = 'root'; 
$pass = 'rootpass';
echo auth_user($Login, $user, $pass);

# 定义一个常量 SESS_PREF，它用作密码

session_start();

if(empty($_SESSION['sesspref'])){
    $len = rand(20, 60);
	$sesspref = rand(20, 60);
	$_SESSION['sesspref']=$sesspref;
}
else $sesspref=$_SESSION['sesspref'];
define('SESS_PREF',$sesspref);


# 配置结束。 下面可以不用修改，请关闭文件。

$_iflags = '';
$_system = array
(
    'ssl'          => extension_loaded('openssl') && version_compare(PHP_VERSION, '4.3.0', '>='),
    'uploads'      => ini_get('file_uploads'),
    'gzip'         => extension_loaded('zlib') && !ini_get('zlib.output_compression'),
    'stripslashes' => get_magic_quotes_gpc()
    );
$_proxify           = array('text/html' => 1, 'application/xml+xhtml' => 1, 'application/xhtml+xml' => 1, 'text/css' => 1);
$_version           = '0.62';
$_http_host         = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'localhost');
$_script_url        = 'http' . ((isset($_ENV['HTTPS']) && $_ENV['HTTPS'] == 'on') || $_SERVER['SERVER_PORT'] == 443 ? 's' : '') . '://' . $_http_host . ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443 ? ':' . $_SERVER['SERVER_PORT'] : '') . $_SERVER['PHP_SELF'];
$_script_base       = substr($_script_url, 0, strrpos($_script_url, '/') + 1);
$_url               = '';
$_url_parts         = array();
$_base              = array();
$_socket            = null;
$_request_method    = $_SERVER['REQUEST_METHOD'];
$_request_headers   = '';
$_cookie            = '';
$_post_body         = '';
$_response_headers  = array();
$_response_keys     = array();
$_http_version      = '';
$_response_code     = 0;
$_content_type      = 'text/html';
$_content_length    = false;
$_content_disp      = '';
$_set_cookie        = array();
$_retry             = false;
$_quit              = false;
$_basic_auth_header = '';
$_basic_auth_realm  = '';
$_auth_creds        = array();
$_response_body     = '';

# 函数区域

function show_report($data){  # 状态报告
    include $data['which'] . '.inc.php';
    exit(0);
    }

function add_cookie($name, $value, $expires = 0){  # 增加 COOKIE
    return rawurlencode(rawurlencode($name)) . '=' . rawurlencode(rawurlencode($value)) . (empty($expires) ? '' : ';expires=' . gmdate('D, d-M-Y H:i:s \G\M\T', $expires)) . ';path=/;domain=.' . $GLOBALS['_http_host'];
    }


function set_post_vars($array, $parent_key = null){  # POST 设置
    $temp = array();
    
    foreach ($array as $key => $value)
    {
        $key = isset($parent_key) ? sprintf('%s[%s]', $parent_key, urlencode($key)) : urlencode($key);
        if (is_array($value))
            {
            $temp = array_merge($temp, set_post_vars($value, $key));
            }
        else
            {
            $temp[$key] = urlencode($value);
            }
        }
    
    return $temp;
    }

function set_post_files($array, $parent_key = null){  # POST 文件
    $temp = array();
    
    foreach ($array as $key => $value)
    {
        $key = isset($parent_key) ? sprintf('%s[%s]', $parent_key, urlencode($key)) : urlencode($key);
        if (is_array($value))
            {
            $temp = array_merge_recursive($temp, set_post_files($value, $key));
            }
        else if (preg_match('#^([^\[\]]+)\[(name|type|tmp_name)\]#', $key, $m))
            {
            $temp[str_replace($m[0], $m[1], $key)][$m[2]] = $value;
            }
        }
    
    return $temp;
    }

function url_parse($url, & $container){  # 分析 URL
    $temp = @parse_url($url);
    
    if (!empty($temp))
        {
        $temp['port_ext'] = '';
        $temp['base'] = $temp['scheme'] . '://' . $temp['host'];
        
        if (isset($temp['port']))
            {
            $temp['base'] .= $temp['port_ext'] = ':' . $temp['port'];
            }
        else
            {
            $temp['port'] = $temp['scheme'] === 'https' ? 443 : 80;
            }
        
        $temp['path'] = isset($temp['path']) ? $temp['path'] : '/';
        $path = array();
        $temp['path'] = explode('/', $temp['path']);
        
        foreach ($temp['path'] as $dir)
        {
            if ($dir === '..')
            {
                array_pop($path);
                }
            else if ($dir !== '.')
            {
                for ($dir = rawurldecode($dir), $new_dir = '', $i = 0, $count_i = strlen($dir);$i < $count_i;$new_dir .= strspn($dir{$i}, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789$-_.+!*\'(),?:@&;=') ? $dir{$i} : rawurlencode($dir{$i}), ++$i);
                $path[] = $new_dir;
                }
            }
        
        $temp['path'] = str_replace('/%7E', '/~', '/' . ltrim(implode('/', $path), '/'));
        $temp['file'] = substr($temp['path'], strrpos($temp['path'], '/') + 1);
        $temp['dir'] = substr($temp['path'], 0, strrpos($temp['path'], '/'));
        $temp['base'] .= $temp['dir'];
        $temp['prev_dir'] = substr_count($temp['path'], '/') > 1 ? substr($temp['base'], 0, strrpos($temp['base'], '/') + 1) : $temp['base'] . '/';
        $container = $temp;
        
        return true;
        }
    
    return false;
    }

function complete_url($url, $proxify = true){  # 完成 URL
    $url = trim($url);
    
    if ($url === '')
    {
        return '';
        }
    
    $hash_pos = strrpos($url, '#');
    $fragment = $hash_pos !== false ? '#' . substr($url, $hash_pos) : '';
    $sep_pos = strpos($url, '://');
    
    if ($sep_pos === false || $sep_pos > 5)
    {
        switch ($url{0})
        {
        case '/':
            $url = substr($url, 0, 2) === '//' ? $GLOBALS['_base']['scheme'] . ':' . $url : $GLOBALS['_base']['scheme'] . '://' . $GLOBALS['_base']['host'] . $GLOBALS['_base']['port_ext'] . $url;
            break;
        case '?':
            $url = $GLOBALS['_base']['base'] . '/' . $GLOBALS['_base']['file'] . $url;
            break;
        case '#':
            $proxify = false;
            break;
        case 'm':
            if (substr($url, 0, 7) == 'mailto:')
                {
                $proxify = false;
                break;
                }
            default:
            $url = $GLOBALS['_base']['base'] . '/' . $url;
            }
        }
    
    return $proxify ? "{$GLOBALS['_script_url']}?{$GLOBALS['_config']['url_var_name']}=" . encode_url($url) . $fragment : $url;
    }

function proxify_inline_css($css){  # CSS风格
    preg_match_all('#url\s*\(\s*(([^)]*(\\\))*[^)]*)(\)|$)?#i', $css, $matches, PREG_SET_ORDER);
    
    for ($i = 0, $count = count($matches);$i < $count;++$i)
    {
        $css = str_replace($matches[$i][0], 'url(' . proxify_css_url($matches[$i][1]) . ')', $css);
        }
    
    return $css;
    }

function proxify_css($css){  # CSS风格
    $css = proxify_inline_css($css);
    
    preg_match_all("#@import\s*(?:\"([^\">]*)\"?|'([^'>]*)'?)([^;]*)(;|$)#i", $css, $matches, PREG_SET_ORDER);
    
    for ($i = 0, $count = count($matches);$i < $count;++$i)
    {
        $delim = '"';
        $url = $matches[$i][2];
        
        if (isset($matches[$i][3]))
            {
            $delim = "'";
            $url = $matches[$i][3];
            }
        
        $css = str_replace($matches[$i][0], '@import ' . $delim . proxify_css_url($matches[$i][1]) . $delim . (isset($matches[$i][4]) ? $matches[$i][4] : ''), $css);
        }
    
    return $css;
    }

function proxify_css_url($url){  # 加载的CSS风格文件的URL
    $url = trim($url);
    $delim = strpos($url, '"') === 0 ? '"' : (strpos($url, "'") === 0 ? "'" : '');
    
    return $delim . preg_replace('#([\(\),\s\'"\\\])#', '\\$1', complete_url(trim(preg_replace('#\\\(.)#', '$1', trim($url, $delim))))) . $delim;
    }

# 追加的函数

function chkcode($str){  # 判断charset
    $code = array(
        'GBK',
        'EUC-CN',
        'BIG5',
        'EUC-TW',
        'CP950',
        'BIG5-HKSCS',
        'UTF-8',
        'GB2312',
        'CP936',
        'BIG5-HKSCS:2001',
        'BIG5-HKSCS:1999',
        'ISO-2022-CN',
        'ISO-2022-CN-EXT',
        'SJIS',
        'JIS',
        'EUC-JP',
        'SHIFT_JIS',
        'eucJP-win',
        'SJIS-win',
        'ISO-2022-JP',
        'CP932',
        'ISO-2022-JP',
        'ISO-2022-JP-2',
        'ISO-2022-JP-1',
        'EUC-KR',
        'CP949',
        'ISO-2022-KR',
        'JOHAB',
        );
    
    foreach($code as $charset){
        if($str == @iconv('UTF-8', "$charset//IGNORE//TRANSLIT", @iconv($charset, 'UTF-8//IGNORE//TRANSLIT', $str))){
            return $charset;
            break;
            }
        }
    return 'UTF-8';
    }

function utf2html($str){  # UTF8转成HTML实体
    $ret = "";
    $max = strlen($str);
    $last = 0;
    for ($i = 0;$i < $max;$i++){
        $c = $str{$i};
        $c1 = ord($c);
        if ($c1 >> 5 == 6){
            $ret .= substr($str, $last, $i - $last);
            $c1 &= 31;  # remove the 3 bit two bytes prefix
            $c2 = ord($str{++$i});
            $c2 &= 63;
            $c2 |= (($c1 & 3) << 6);
            $c1 >>= 2;
            $ret .= "&#" . ($c1 * 0x100 + $c2) . ";";
            $last = $i + 1;
            }
        elseif ($c1 >> 4 == 14){
            $ret .= substr($str, $last, $i - $last);
            $c2 = ord($str{++$i});
            $c3 = ord($str{++$i});
            $c1 &= 15;
            $c2 &= 63;
            $c3 &= 63;
            $c3 |= (($c2 & 3) << 6);
            $c2 >>= 2;
            $c2 |= (($c1 & 15) << 4);
            $c1 >>= 4;
            $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';';
            $last = $i + 1;
            }
        }
    $str = $ret . substr($str, $last, $i);
    return $str;
    }

function enc($str,$key='')
    {
    if(empty($key)) $key = SESS_PREF;
    $encstr = base64_encode(encrypt($str,$key));
    return $encstr;
    }

function dec($encstr,$key='')
    {
    if(empty($key)) $key = SESS_PREF;
    $str = decrypt(base64_decode($encstr),$key);
    return $str;
    }
	
function encrypt($input, $key){  # 加密函数
    // global $key;
    $line = "";
    $n = strlen($input);
    for($i = 0; $i < $n; $i++){
        $line .= chr(ord($input[$i]) + $key);  # ord()返回字符的 ASCII 值。十进制
        }  # chr()从指定的 ASCII 值返回字符。
    return $line;
    }

function decrypt($input, $key){  # 解密函数
    // global $key;
    $line = "";
    $n = strlen($input);
    for($i = 0;$i < $n; $i++){
        $line .= chr(ord($input[$i]) - $key);
        }
    return $line;
    }

function str2hex($s)
{
    $r = "";
    $hexes = array ("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f");
    for ($i = 0;$i < strlen($s);$i++)
    $r .= ($hexes [(ord($s{$i}) >> 4)] . $hexes [(ord($s{$i}) & 0xf)]);
    return $r;
    }

function hex2str($s)
{
    $r = "";
    for ($i = 0;$i < strlen($s);$i += 2)
    {
        $x1 = ord($s{$i});
        $x1 = ($x1 >= 48 && $x1 < 58) ? $x1-48 : $x1-97 + 10;
        $x2 = ord($s{$i+1});
        $x2 = ($x2 >= 48 && $x2 < 58) ? $x2-48 : $x2-97 + 10;
        $r .= chr((($x1 << 4) & 0xf0) | ($x2 & 0x0f));
        }
    return $r;
    }

function randstr($len = 6){
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $rs = '';
    for($i = 0;$i < $len;$i++){
        $c = rand(0, 61);
        $rs .= $chars[$c];
        }
    return $rs;
    }

function rand_add_slashes($b64url){
    $n = strlen($b64url);
    $nx = 0;
    $b64urly = '';
    for($i = 0;$i < $n ;$i ++){
        $nxx = rand(1, 12);
        if($i == '0'){
            $nx = 0;
            }else{
            $nx = $nx + $y;
            }
        if ($nx >= $n){
            break;
            }
        $b64urlx = substr($b64url, $nx, $nxx);
        $y = $nxx;
        $b64urlx = $b64urlx . "/";
        $b64urly .= $b64urlx;
        }
    return $b64urly . randstr() . '.html';
    }

function del_slashes($encstr){

    if (strstr($encstr, '.html'))
            {
            $encstr = explode('.html', $encstr);
            }
    $encstr[0] = substr($encstr[0], 0, -6);
    $b64url = str_replace('/', '', $encstr[0]);
    // $b64url = str_replace('%3D', '', $b64url);
    return $b64url.$encstr[1];
    }

function my_countries($my_countries, $use_ip2nation){  # 由浏览器语言和 IP 判断
    if ($use_ip2nation){
        $server   = 'localhost';          # MySQL hostname
        $username = 'walk_ip2nation';     # MySQL username
        $password = '0000000';            # MySQL password
        $dbname   = 'walk_ip2nation';     # MySQL db name

        $db = mysql_connect($server, $username, $password) or die(mysql_error());
        mysql_select_db($dbname) or die(mysql_error());
        $sql = 'SELECT
		country
		FROM
		ip2nation
		WHERE
		ip < INET_ATON("' . $_SERVER['REMOTE_ADDR'] . '")
		ORDER BY
		ip DESC
		LIMIT 0,1';
        list($country) = mysql_fetch_row(mysql_query($sql));
        if (!in_array($country, $my_countries)){
            $info = "\r\n<br><br>people from outside of china may not use this proxy ,please visit http://clearwisdom.net\r\n<br><br>";
            // $info .= encrypt($info);
            echo $info;
            exit;
            }
        }
    }

function getpage($url){   # 白名单相关函数，获取数据 
    if(!$_SERVER['HTTP_ACCEPT_LANGUAGE']){
        $lang = 'en';
        }else{
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

    $url = @parse_url($url);
    if(isset($url['query'])){
        $url_get = $url['path'] . '?' . $url['query'];
        }else{
        $url_get = $url['path'];
        }
    $temp = '';
    
    if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
        $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
        if (!$fp){
	    $http_code = '404';
            return "$errstr false";
            }else{
            $out = "GET $url_get HTTP/1.0\r\n";
            $out .= "Host: $url[host]\r\n";
            $out .= "Accept-Language: $lang\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            while (!feof($fp)){
                $temp .= fgets($fp, 128);
                }
            fclose($fp);
            $http_code = substr($temp, 9, 3);
            $temp = explode("\r\n\r\n", $temp, 2);
            $temp = $temp[1];
            }
        }elseif(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec ($ch);
        $http_code = getpageinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        }else{
        $http_code = '0';
        $temp = 'false';
        }
    if ($http_code >= 400){  # 400 - 600都是服务器错误
        return 'false';
        // exit(0);
    }else{
        return $temp;
        }
    }

function match_links($document){  # 白名单相关函数，获取超链接
    preg_match_all("'<\s*a\s.*?href\s*=\s*([\"\'])?(?(1)(.*?)\\1|([^\s\>]+))[^>]*>?(.*?)</a>'isx", $document, $links);
    while(list($key, $val) = each($links[2])){
        if(!empty($val))
            $match[] = $val;
        }
    while(list($key, $val) = each($links[3])){
        if(!empty($val))
            $match[] = $val;
        }
    return array($match, $links[4]);
    }

function auth_user($Login, $user, $pass){  # 用户认证函数
    if ($Login){
        $valid_passwords = array
        (
            $user   => $pass,
            'admin' => 'pass',
            );
        $valid_users = array_keys($valid_passwords);
        $user = isset ($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $pass = isset ($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
        if (!$validated){
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die ('Not authorized');
            }
        }
    }

function msg_form(){   # 留言本表框
    $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>联系</title></head>'
     . '<body style="background-color=#99CC66"><br /><br /><br /><center><h3>留 言 本</h3>'
     . '<form name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">'
     . '<table><tr><td><input name="email_efab23731" type="text" value="E-mail:" style="width:521px"></td></tr>'
     . '<tr><td><textarea name="msg_d642340b7" style="height:200px;width:521px" ></textarea></td></tr></table>'
     . '<br /><input type="submit" value="提 交"></form> ';
    return $form;
    }

	
function addJsSlashes($str, $flag) {
    if ($flag) {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134\177..\377");
    }else {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134");
    }
    return str_replace(array(chr(7), chr(11)), array('\007', '\013'), $str);
}	
# 如果有必要转换来自使用者电脑的斜杠

if ($_system['stripslashes'])
{
    function _stripslashes($value)
    {
        return is_array($value) ? array_map('_stripslashes', $value) : (is_string($value) ? stripslashes($value) : $value);
        }
    
    $_GET = _stripslashes($_GET);
    $_POST = _stripslashes($_POST);
    $_COOKIE = _stripslashes($_COOKIE);
    }

# 标签设置

if(isset($_GET['iso']) && !empty($_GET['iso']))  # 自定义HTML编码
    {
    //$iso = htmlspecialchars(addslashes(trim(ltrim(strtolower($_GET['iso'])))));
    $iso = trim(ltrim(strtolower($_GET['iso'])));
    }
if (isset($_POST[$_config['url_var_name']]) && !isset($_GET[$_config['url_var_name']]) && isset($_POST[$_config['flags_var_name']]))
    {
    foreach ($_flags as $flag_name => $flag_value)
    {
        $_iflags .= isset($_POST[$_config['flags_var_name']][$flag_name]) ? (string)(int)(bool)$_POST[$_config['flags_var_name']][$flag_name] : ($_frozen_flags[$flag_name] ? $flag_value : '0');
        }  
    $_iflags = base_convert(($_iflags != '' ? $_iflags : '0'), 2, 16);
    }
else if (isset($_GET[$_config['flags_var_name']]) && !isset($_GET[$_config['get_form_name']]) && ctype_alnum($_GET[$_config['flags_var_name']]))
    {
    $_iflags = $_GET[$_config['flags_var_name']];
    }
else if (isset($_COOKIE['flags']) && ctype_alnum($_COOKIE['flags']))
    {
    $_iflags = $_COOKIE['flags'];
    }

if ($_iflags !== '')
{
    $_set_cookie[] = add_cookie('flags', $_iflags, time() + 2419200);
    $_iflags = str_pad(base_convert($_iflags, 16, 2), count($_flags), '0', STR_PAD_LEFT);
    $i = 0;
    
    foreach ($_flags as $flag_name => $flag_value)
    {
        $_flags[$flag_name] = $_frozen_flags[$flag_name] ? $flag_value : (int)(bool)$_iflags{$i};
        $i++;
        }
    }

# 在标签的基础上定义URL编码

if ($_flags['static_url'])
{
    function encode_url($url)
    {
        global $iso;
        $url = enc($url,$key='8');
        if(isset($iso)) $url = $url . "&iso=$iso";
        return $url;
        }
    function decode_url($url)
    {
        global $iso;
        if(isset($iso)) {
		    $url = str_replace(array('&amp;','&#38;'),'&',$url); 
            $url = explode("&iso=",$url);
            $url = $url[0];
        }
        $url = dec($url,$key='8'); 
        return $url;
        }
    }
else if ($_flags['rotate13_url'])
{
    function encode_url($url)
    {
        global $iso;
        $url = rawurlencode(str_rot13($url));
        if(isset($iso)) $url = $url . "&iso=$iso";
        return $url;
        }
    function decode_url($url)
    {
        global $iso;
        if(isset($iso)) {
		    $url = str_replace(array('&amp;','&#38;'),'&',$url); 
            $url = explode("&iso=",$url);
            $url = $url[0];
        }
        $url = str_replace(array('&amp;','&#38;'),'&', str_rot13(rawurldecode($url)));
        return $url;
        }
    }
else if ($_flags['dynamic_url'])  # 动态URL str2hex hex2str 
{
    function encode_url($url)
    {
        global $iso;
        $url = rand_add_slashes(rawurlencode(enc($url, $key=''))) ;
        if(isset($iso)) $url = $url . "&iso=$iso";
        return $url;
        }
    function decode_url($url)
    {
        global $iso;
        if(isset($iso)) {
	    $url = str_replace(array('&amp;','&#38;'),'&',$url); 
            $url = explode("&iso=",$url);
            $url = $url[0];
        }
        $url = dec(rawurldecode(del_slashes($url)),$key='');
        return $url;
        }
    }
else
    {
    function encode_url($url)
    {
        global $iso;
        $url = rawurlencode($url);
        if(isset($iso)) $url = $url . "&iso=$iso";
        return $url;
        }
    function decode_url($url)
    {
        global $iso;
        if(isset($iso)) {
		    $url = str_replace(array('&amp;','&#38;'),'&',$url); 
            $url = explode("&iso=",$url);
            $url = $url[0];
        }
        $url = str_replace(array('&amp;', '&#38;'), '&', rawurldecode($url));
		return $url;
        }
    }

/*------------------------------------函数区域结束，下面是添加代码-------------------------------------*/

# 常用书签的相关代码，基本不需要更改

if($_mylink){
$basedir = pathinfo($_SERVER['PHP_SELF']);
$mylink = '';
if(!@file_get_contents('.htaccess'))
    {
    $relink = '';
    foreach($link as $key => $value)
    {
	    if(empty($value[2])){
	        $value[2] = str_pad(($key + 1), 3, 0, STR_PAD_LEFT);
	        }
        $relink .= 'RewriteRule ^my/' . $value[2] . '$ ' . $_SERVER['PHP_SELF'] . '?' . $GLOBALS['_config']['url_var_name'] . '=' . encode_url($value[0]) . "&hl=9e1\r\n##$value[0]##$value[1]##$value[2]\r\n###\r\n";
        $mylink .= '<a href="' . $_SERVER['PHP_SELF'] . '?' . $GLOBALS['_config']['url_var_name'] . '=' . encode_url($value[0]) . '&hl=9e1">' . $value[1] . "</a>\r\n\t";
        }
    $base = '<IfModule mod_rewrite.c>' . "\r\nRewriteEngine On\r\n";
    $base .= 'RewriteBase ' . $basedir['dirname'] . "/\r\n###\r\n";
    $base .= $relink;
    $base .= '</IfModule>';
    @file_put_contents('.htaccess', $base);  # 如果服务器不支持rewrite模块(同时AllowOverride all)，请在该行前加  //
    # $fp = fopen('.htaccess','a+'); fwrite($fp,$relink); fclose($fp);
}else{
    $ht = file_get_contents('.htaccess');
    $ht = explode("\r\n###\r\n", $ht);
    $nht = count($ht)-1;
    for($i = 1; $i < $nht; $i++){
        $link = explode("##", $ht[$i]);
        $mylink .= ' &nbsp;<a href="' . $basedir['dirname'] . '/my/' . @$link[3] . '">' . @$link[2] . '</a> &nbsp;' . "\r\n";
        }
    }
unset($link);
unset($basedir);
$mylink = "常用网址 >>>\r\n" . $mylink;

if(extension_loaded('mbstring')){
    $mylink = mb_convert_encoding($mylink, 'UTF-8', $mylink_charset);
    }else if(extension_loaded('iconv')){
    $mylink = iconv($mylink_charset, 'UTF-8//IGNORE//TRANSLIT', $mylink);
    }
$mylink = utf2html($mylink);
}

/*-------------------------------------------------------------------------*/

# 留言本

date_default_timezone_set('Asia/Shanghai');
$log = $_SERVER["REMOTE_ADDR"] . '@' . date('Ymd His');
$db = './msg.log';

if(isset($_GET['msg']) && $_GET['msg'] == 'contact' && !isset($_POST['msg_d642340b7']) && !isset($_POST['email_efab23731'])){
    echo msg_form();
    exit();
    }
if(isset($_POST['msg_d642340b7']) && !empty($_POST['msg_d642340b7']) && isset($_POST['email_efab23731']) && !empty($_POST['email_efab23731'])){    
    $email_efab23731 = $_POST['email_efab23731'];
    $msg_d642340b7 = $_POST['msg_d642340b7'];
    $msg_d642340b7 = $email_efab23731 . "\r\n" . $msg_d642340b7 . "\r\n------------$log------------\r\n\r\n";
    $msg_d642340b7 = htmlspecialchars(addslashes(trim(ltrim(strtolower($msg_d642340b7)))));
    $_torf = eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$", $email_efab23731);
    $headers = 'From: ' . $email_efab23731 . "\r\n" . 'Reply-To: ' . $email_efab23731 . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    @mail('yourshell.info@gmail.com', '反馈', $msg_d642340b7 , $headers);
    
    if(!file_exists($db)) file_put_contents($db, '');
    $old_msg_d642340b7 = file_get_contents($db);
    $_data = $msg_d642340b7 ."\r\n". $old_msg_d642340b7;
    @file_put_contents($db, $_data);
    echo '<meta http-equiv=refresh content="3; URL='.  $_SERVER['PHP_SELF']  .'">';
    
    if($_torf == true){

        echo '<br />谢谢，信息发送成功<br /></center></body></html>';
        exit(0);
        }else{
        echo '<br />您的E-mail可能有误，无法与您联系，请填写正确的E-mail<br /></center></body></html>';
        header("refuesh:2;url=./index.php");
        exit(0);
        }
    }
/*-------------------------------------------------------------------------*/

	
# 想想该怎么做 (POST URL-表单发送, GET 表单请求, 规则请求, 认证, cookie 管理, 显示 URL-表框)

if (!isset($_POST[$_config['url_var_name']]) && !isset($_GET[$_config['url_var_name']]) && $_myhost == '1')
    {
    header('Location: ' . $_script_url . '?' . $_config['url_var_name'] . '=' . encode_url($_one_host_url) . '&' . $_config['flags_var_name'] . '=1c9');  # 
    exit(0);
    }

if (isset($_POST[$_config['url_var_name']]) && !isset($_GET[$_config['url_var_name']]))
    {
    header('Location: '.$_script_url.'?'.$_config['url_var_name'].'='.encode_url(str_replace('&amp;', '&', rawurldecode(base64_decode(hex2str($_POST[$_config['url_var_name']]))))) . '&' . $_config['flags_var_name'] . '=' . base_convert($_iflags, 2, 16));
    exit(0);
    }

if (isset($_GET[$_config['get_form_name']]))
    {
    $_url = decode_url($_GET[$_config['get_form_name']]);
    $qstr = strpos($_url, '?') !== false ? (strpos($_url, '?') === strlen($_url)-1 ? '' : '&') : '?';
    $arr = explode('&', $_SERVER['QUERY_STRING']);
    
    if (preg_match('#^\Q' . $_config['get_form_name'] . '\E#', $arr[0]))
        {
        array_shift($arr);
        }
    
    $_url .= $qstr . implode('&', $arr);
    }
else if (isset($_GET[$_config['url_var_name']]))
    {
    $_url = decode_url($_GET[$_config['url_var_name']]);
    }
else if (isset($_GET['action']) && $_GET['action'] == 'cookies')
    {
    show_report(array('which' => 'cookies'));
    }
else
    {
    show_report(array('which' => 'index', 'category' => 'entry_form'));
    }

if (isset($_GET[$_config['url_var_name']], $_POST[$_config['basic_auth_var_name']], $_POST['username'], $_POST['password']))
    {
    $_request_method = 'GET';
    $_basic_auth_realm = base64_decode($_POST[$_config['basic_auth_var_name']]);
    $_basic_auth_header = base64_encode($_POST['username'] . ':' . $_POST['password']);
    }

# 配置 URL

if (strpos($_url, '://') === false)
    {
    $_url = 'http://' . $_url;
    }

if (url_parse($_url, $_url_parts))
    {
    $_base = $_url_parts;
    
    if (!empty($_hosts))
        {
        foreach ($_hosts as $host)
        {
            if (preg_match($host, $_url_parts['host']))
                {
                show_report(array('which' => 'index', 'category' => 'error', 'group' => 'url', 'type' => 'external', 'error' => 1));
                }
            }
        }
    }
else
    {
    show_report(array('which' => 'index', 'category' => 'error', 'group' => 'url', 'type' => 'external', 'error' => 2));
    }

# 防止外链

if (!$_config['allow_hotlinking'] && isset($_SERVER['HTTP_REFERER']))
    {
    $_hotlink_domains[] = $_http_host;
    $is_hotlinking = true;
    
    foreach ($_hotlink_domains as $host)
    {
        if (preg_match('#^https?\:\/\/(www)?\Q' . $host . '\E(\/|\:|$)#i', trim($_SERVER['HTTP_REFERER'])))
            {
            $is_hotlinking = false;
            break;
            }
        }
    
    if ($is_hotlinking)
    {
        switch ($_config['upon_hotlink'])
        {
        case 1:
            show_report(array('which' => 'index', 'category' => 'error', 'group' => 'resource', 'type' => 'hotlinking'));
            break;
        case 2:
            header('HTTP/1.0 404 Not Found');
            exit(0);
            default:
            header('Location: ' . $_config['upon_hotlink']);
            exit(0);
            }
        }
    }

# 白名单

if($white_hosts){
$str_host = explode('.', $_url_parts['host']);
$n_str_host = count($str_host);
$domain_host = @$str_host[$n_str_host-2] . '.' . @$str_host[$n_str_host-1];
$dtw_url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');

if (in_array($domain_host, $white_list))
    {
    header("Content-type: text/html; charset=GBK");
    $dtw_url = base64_decode('aHR0cDovL2Rvbmd0YWl3YW5nLmNvbS9sb2MvcGhvbWUucGhw');
    $dtw_temp = getpage($dtw_url);
	if (strstr($dtw_temp,'false')){
        echo $dtw_temp;
        header("refresh: 2;url=./index.php");
	    exit(0);
	}
    $dtw_temp_ip = explode('<div id="content_list_right1">', $dtw_temp);
    $dtw_temp_ip = @$dtw_temp_ip[1] . @$dtw_temp_ip[2];
    $dtw_temp_ip = str_replace('http://', 'https://', $dtw_temp_ip);
    $dtw_ip = match_links($dtw_temp_ip);
    $dtwip = '';
    foreach ($dtw_ip[0] as $key => $value){
        $dtwip .= "\r\n<tr><td>" . '<a href="' . $value . '">' . $value . '</a></td></tr>';
        }
    $dtw_ip_n = count($dtw_ip[0]);
    
print<<<JS1
<html><head>
<meta http-equiv="content-type" content="text/html;charset=gb2312">
<script language="javascript" >
i=1
var autourl=new Array()

JS1;
    
    for($i = 0 ; $i < $dtw_ip_n ; $i++){
        $j = $i + 1;
        $dtw_ip[0][$i] = str_replace("//", '\\', $dtw_ip[0][$i]);
        $dtw_ip[0][$i] = str_replace("/", '', $dtw_ip[0][$i]);
        $dtw_ip[0][$i] = str_replace('\\', '//', $dtw_ip[0][$i]);
        echo "autourl[$j]=\"" . $dtw_ip[0][$i] . "\"\r\n";
        }
    
print<<<JS2
function auto(url)
{
if(i)
{
i=0;
top.location=url
}}
function run()
{
for(var i=1;
i<autourl.length;i++)
document.write("<img src="+autourl[i]+" width=1 height=1 onerror=auto('"+autourl[i]+"')>") 
}
run() 
</script>
JS2;
    
    echo '</head><title>选择服务器</title></head><body style="background-color=#99CC66">';  # . $dtwip;
    echo '<br /><br /><center></h3>正在选择服务器，请稍等</h3><table>'. $dtwip . '</table>';
    echo '<br /> <br />出现证书提示点“<b>是</b>”，可能连续点几次</center>';
    echo '</body></html>';
    exit(0);
    }
}


# 打开 SOCKET 服务

do
{
    $_retry = false;
    $_socket = @fsockopen(($_url_parts['scheme'] === 'https' && $_system['ssl'] ? 'ssl://' : 'tcp://') . $_url_parts['host'], $_url_parts['port'], $err_no, $err_str, 30);
    
    if ($_socket === false)
    {
        show_report(array('which' => 'index', 'category' => 'error', 'group' => 'url', 'type' => 'internal', 'error' => $err_no));
        }
    
# 设置请求头
    
    $_request_headers = $_request_method . ' ' . $_url_parts['path'];
    
    if (isset($_url_parts['query']))
        {
        $_request_headers .= '?';
        $query = preg_split('#([&;])#', $_url_parts['query'], -1, PREG_SPLIT_DELIM_CAPTURE);
        for ($i = 0, $count = count($query);$i < $count;$_request_headers .= implode('=', array_map('urlencode', array_map('urldecode', explode('=', $query[$i])))) . (isset($query[++$i]) ? $query[$i] : ''), $i++);
        }
    
    $_request_headers .= " HTTP/1.0\r\n";
    $_request_headers .= 'Host: ' . $_url_parts['host'] . $_url_parts['port_ext'] . "\r\n";
    
    if (isset($_SERVER['HTTP_USER_AGENT']))
        {
        $_request_headers .= 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . " PHProxy by yourshell.info". "\r\n";
        }
    if (isset($_SERVER['HTTP_ACCEPT']))
        {
        $_request_headers .= 'Accept: ' . $_SERVER['HTTP_ACCEPT'] . "\r\n";
        }
    else
        {
        $_request_headers .= "Accept: */*;q=0.1\r\n"; #vnd.wap.wml
        }
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
        $_request_headers .= 'ACCEPT_LANGUAGE: ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . "\r\n";
        }
    if ($_flags['show_referer'] && isset($_SERVER['HTTP_REFERER']) && preg_match('#^\Q' . $_script_url . '?' . $_config['url_var_name'] . '=\E([^&]+)#', $_SERVER['HTTP_REFERER'], $matches))
        {
        $_request_headers .= 'Referer: ' . decode_url($matches[1]) . "\r\n";
        }
    if (!empty($_COOKIE))
        {
        $_cookie = '';
        $_auth_creds = array();
        
        foreach ($_COOKIE as $cookie_id => $cookie_content)
        {
            $cookie_id = explode(';', rawurldecode($cookie_id));
            if(isset($cookie_id[3])){
                $cookie_id[3] = dec($cookie_id[3],$key='');
                $cookie_id[3] = str_replace('.', '_', $cookie_id[3]);
                }
            $cookie_content = explode(';', rawurldecode($cookie_content));
            if ($cookie_id[0] === 'COOKIE')
            {
                $cookie_id[3] = str_replace('_', '.', $cookie_id[3]);  # stupid PHP can't have dots in var names
                
                
                if (count($cookie_id) < 4 || ($cookie_content[1] == 'secure' && $_url_parts['scheme'] != 'https'))
                    {
                    continue;
                    }
                
                if ((preg_match('#\Q' . $cookie_id[3] . '\E$#i', $_url_parts['host']) || strtolower($cookie_id[3]) == strtolower('.' . $_url_parts['host'])) && preg_match('#^\Q' . $cookie_id[2] . '\E#', $_url_parts['path']))
                    {
                    $_cookie .= ($_cookie != '' ? ';' : '') . (empty($cookie_id[1]) ? '' : $cookie_id[1] . '=') . $cookie_content[0];
                    }
                }
            else if ($cookie_id[0] === 'AUTH' && count($cookie_id) === 3)
                {
                $cookie_id[2] = str_replace('_', '.', $cookie_id[2]);
                
                if ($_url_parts['host'] . ':' . $_url_parts['port'] === $cookie_id[2])
                {
                    $_auth_creds[$cookie_id[1]] = $cookie_content[0];
                    }
                }
            }
        
        if ($_cookie != '')
        {
            $_request_headers .= "Cookie: $_cookie\r\n";
            }
        }
    if (isset($_url_parts['user'], $_url_parts['pass']))
        {
        $_basic_auth_header = base64_encode($_url_parts['user'] . ':' . $_url_parts['pass']);
        }
    if (!empty($_basic_auth_header))
        {
        $_set_cookie[] = add_cookie("AUTH;{$_basic_auth_realm};{$_url_parts['host']}:{$_url_parts['port']}", $_basic_auth_header);
        $_request_headers .= "Authorization: Basic {$_basic_auth_header}\r\n";
        }
    else if (!empty($_basic_auth_realm) && isset($_auth_creds[$_basic_auth_realm]))
        {
        $_request_headers .= "Authorization: Basic {$_auth_creds[$_basic_auth_realm]}\r\n";
        }
    else if (list($_basic_auth_realm, $_basic_auth_header) = each($_auth_creds))
        {
        $_request_headers .= "Authorization: Basic {$_basic_auth_header}\r\n";
        }
    if ($_request_method == 'POST')
    {
        if (!empty($_FILES) && $_system['uploads'])
            {
            $_data_boundary = '----' . md5(uniqid(rand(), true));
            $array = set_post_vars($_POST);
            
            foreach ($array as $key => $value)
            {
                $_post_body .= "--{$_data_boundary}\r\n";
                $_post_body .= "Content-Disposition: form-data;name=\"$key\"\r\n\r\n";
                $_post_body .= urldecode($value) . "\r\n";
                }
            
            $array = set_post_files($_FILES);
            
            foreach ($array as $key => $file_info)
            {
                $_post_body .= "--{$_data_boundary}\r\n";
                $_post_body .= "Content-Disposition: form-data;name=\"$key\";filename=\"{$file_info['name']}\"\r\n";
                $_post_body .= 'Content-Type: ' . (empty($file_info['type']) ? 'application/octet-stream' : $file_info['type']) . "\r\n\r\n";
                
                if (is_readable($file_info['tmp_name']))
                    {
                    $handle = fopen($file_info['tmp_name'], 'rb');
                    $_post_body .= fread($handle, filesize($file_info['tmp_name']));
                    fclose($handle);
                    }
                
                $_post_body .= "\r\n";
                }
            
            $_post_body .= "--{$_data_boundary}--\r\n";
            $_request_headers .= "Content-Type: multipart/form-data;boundary={$_data_boundary}\r\n";
            $_request_headers .= "Content-Length: " . strlen($_post_body) . "\r\n\r\n";
            $_request_headers .= $_post_body;
            }
        else
            {
            $array = set_post_vars($_POST);
            
            foreach ($array as $key => $value)
            {
                $_post_body .= !empty($_post_body) ? '&' : '';
                $_post_body .= $key . '=' . $value;
                }
            $_request_headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $_request_headers .= "Content-Length: " . strlen($_post_body) . "\r\n\r\n";
            $_request_headers .= $_post_body;
            $_request_headers .= "\r\n";
            }
        
        $_post_body = '';
        }
    else
        {
        #added by felix021 for block-divided downloading
        if(!empty($_SERVER['HTTP_RANGE'])){
            $_request_headers .= 'Range: ' . $_SERVER['HTTP_RANGE'] . "\r\n";
            }
        $_request_headers .= "\r\n";
        }
    
    fwrite($_socket, $_request_headers);
    
# 进程响应头
    
    $_response_headers = $_response_keys = array();
    
    $line = fgets($_socket, 8192);
    
    while (strspn($line, "\r\n") !== strlen($line))
    {
        @list($name, $value) = explode(':', $line, 2);
        $name = trim($name);
        $_response_headers[strtolower($name)][] = trim($value);
        $_response_keys[strtolower($name)] = $name;
        $line = fgets($_socket, 8192);
        }
    
    sscanf(current($_response_keys), '%s %s', $_http_version, $_response_code);
    
   /*
    *  # 提取header中的charset是错误的做法
    *  # 由于大公司的一台服务器往往由不同国家的人同时使用，然而服务器的默认编码只有一种，
    *  # 西方的大多是“iso-8859-1”，所以太多的时候从header中提取的charset就是这一编码，显
    *  # 然对不同编码的网页这是很不适合的，而且又浪费资源
    *
    * if (isset($_response_headers['content-type']))
    *   {
    *    $rh_content_type = str_replace(' ', '', strtolower($_response_headers['content-type'][0]));
    *    list($_content_type,) = explode(';', $rh_content_type , 2);
    *    @list($type , $header_charset) = explode('charset=', $rh_content_type , 2);
    *    }
    */
    if (isset($_response_headers['content-type']))
    {
        list($_content_type, ) = explode(';', str_replace(' ', '', strtolower($_response_headers['content-type'][0])), 2);
    }
    if (isset($_response_headers['content-length']))
        {
        $_content_length = $_response_headers['content-length'][0];
        unset($_response_headers['content-length'], $_response_keys['content-length']);
        }
    if (isset($_response_headers['content-disposition']))
        {
        $_content_disp = $_response_headers['content-disposition'][0];
        unset($_response_headers['content-disposition'], $_response_keys['content-disposition']);
        }
    if (isset($_response_headers['set-cookie']) && $_flags['accept_cookies'])
        {
        foreach ($_response_headers['set-cookie'] as $cookie)
        {
            $name = $value = $expires = $path = $domain = $secure = $expires_time = '';
            
            preg_match('#^\s*([^=;,\s]*)\s*=?\s*([^;]*)#', $cookie, $match) && list(, $name, $value) = $match;
            preg_match('#;\s*expires\s*=\s*([^;]*)#i', $cookie, $match) && list(, $expires) = $match;
            preg_match('#;\s*path\s*=\s*([^;,\s]*)#i', $cookie, $match) && list(, $path) = $match;
            preg_match('#;\s*domain\s*=\s*([^;,\s]*)#i', $cookie, $match) && list(, $domain) = $match;
            preg_match('#;\s*(secure\b)#i', $cookie, $match) && list(, $secure) = $match;
            
            $expires_time = empty($expires) ? 0 : intval(@strtotime($expires));
            $expires = ($_flags['session_cookies'] && !empty($expires) && time() - $expires_time < 0) ? '' : $expires;
            $path = empty($path) ? '/' : $path;
            
            if (empty($domain))
                {
                $domain = $_url_parts['host'];
                }
            else
                {
                $domain = '.' . strtolower(str_replace('..', '.', trim($domain, '.')));
                
                if ((!preg_match('#\Q' . $domain . '\E$#i', $_url_parts['host']) && $domain != '.' . $_url_parts['host']) || (substr_count($domain, '.') < 2 && $domain{0} == '.'))
                    {
                    continue;
                    }
                }
            $domain = enc($domain,$key='');
            if (count($_COOKIE) >= 15 && time() - $expires_time <= 0)
                {
                $_set_cookie[] = add_cookie(current($_COOKIE), '', 1);
                }
            $_set_cookie[] = add_cookie("COOKIE;$name;$path;$domain", "$value;$secure", $expires_time);
            }
        }
    if (isset($_response_headers['set-cookie']))
        {
        unset($_response_headers['set-cookie'], $_response_keys['set-cookie']);
        }
    if (!empty($_set_cookie))
        {
        $_response_keys['set-cookie'] = 'Set-Cookie';
        $_response_headers['set-cookie'] = $_set_cookie;
        }
    if (isset($_response_headers['p3p']) && preg_match('#policyref\s*=\s*[\'"]?([^\'"\s]*)[\'"]?#i', $_response_headers['p3p'][0], $matches))
        {
        $_response_headers['p3p'][0] = str_replace($matches[0], 'policyref="' . complete_url($matches[1]) . '"', $_response_headers['p3p'][0]);
        }
    if (isset($_response_headers['refresh']) && preg_match('#([0-9\s]*;\s*URL\s*=)\s*(\S*)#i', $_response_headers['refresh'][0], $matches))
        {
        $_response_headers['refresh'][0] = $matches[1] . complete_url($matches[2]);
        }
    if (isset($_response_headers['location']))
        {
        $_response_headers['location'][0] = complete_url($_response_headers['location'][0]);
        }
    if (isset($_response_headers['uri']))
        {
        $_response_headers['uri'][0] = complete_url($_response_headers['uri'][0]);
        }
    if (isset($_response_headers['content-location']))
        {
        $_response_headers['content-location'][0] = complete_url($_response_headers['content-location'][0]);
        }
    if (isset($_response_headers['connection']))
        {
        unset($_response_headers['connection'], $_response_keys['connection']);
        }
    if (isset($_response_headers['keep-alive']))
        {
        unset($_response_headers['keep-alive'], $_response_keys['keep-alive']);
        }
    if ($_response_code == 401 && isset($_response_headers['www-authenticate']) && preg_match('#basic\s+(?:realm="(.*?)")?#i', $_response_headers['www-authenticate'][0], $matches))
        {
        if (isset($_auth_creds[$matches[1]]) && !$_quit)
            {
            $_basic_auth_realm = $matches[1];
            $_basic_auth_header = '';
            $_retry = $_quit = true;
            }
        else
            {
            show_report(array('which' => 'index', 'category' => 'auth', 'realm' => $matches[1]));
            }
        }
    }
while ($_retry);

# 如果输出响应的处理识别是必须的，大概是浏览器信息的反馈

if (!isset($_proxify[$_content_type]))
    {
    @set_time_limit(0);
    
    $_response_keys['content-disposition'] = 'Content-Disposition';
    $_response_headers['content-disposition'][0] = empty($_content_disp) ? ($_content_type == 'application/octet_stream' ? 'attachment' : 'inline') . ';filename="' . $_url_parts['file'] . '"' : $_content_disp;
    
    if ($_content_length !== false)
    {
        if ($_config['max_file_size'] != -1 && $_content_length > $_config['max_file_size'])
        {
            show_report(array('which' => 'index', 'category' => 'error', 'group' => 'resource', 'type' => 'file_size'));
            }
        
        $_response_keys['content-length'] = 'Content-Length';
        $_response_headers['content-length'][0] = $_content_length;
        }
    
    $_response_headers = array_filter($_response_headers);
    $_response_keys = array_filter($_response_keys);
    
    header(array_shift($_response_keys));
    array_shift($_response_headers);
    
    foreach ($_response_headers as $name => $array)
    {
        foreach ($array as $value)
        {
            header($_response_keys[$name] . ': ' . $value, false);
            }
        }
    
    do
    {
        $data = fread($_socket, 8192);
        echo $data;
        }
    while (isset($data{0}));
    
    fclose($_socket);
    exit(0);
    }

do
{
    $data = @fread($_socket, 8192);  # silenced to avoid the "normal" warning by a faulty SSL connection
    $_response_body .= $data;
    }
while (isset($data{0}));

unset($data);
fclose($_socket);

# 修改和输出数据

if ($_content_type == 'text/css')
{
    $_response_body = proxify_css($_response_body);
    }
else
    {
    if ($_flags['strip_title'])
    {
        $_response_body = preg_replace('#(<\s*title[^>]*>)(.*?)(<\s*/title[^>]*>)#is', '$1$3', $_response_body);
        }
    if ($_flags['remove_scripts'])
    {
        $_response_body = preg_replace('#<\s*script[^>]*?>.*?<\s*/\s*script\s*>#si', '', $_response_body);
        $_response_body = preg_replace("#(\bon[a-z]+)\s*=\s*(?:\"([^\"]*)\"?|'([^']*)'?|([^'\"\s>]*))?#i", '', $_response_body);
        $_response_body = preg_replace('#<noscript>(.*?)</noscript>#si', "$1", $_response_body);
        }
    if (!$_flags['show_images'])
    {
        $_response_body = preg_replace('#<(img|image)[^>]*?>#si', '', $_response_body);
        }

# 处理 HTML 数据

    $tags = array
    (
        'a'          => array('href'),
        'img'        => array('src', 'longdesc'),
        'image'      => array('src', 'longdesc'),
        'body'       => array('background'),
        'base'       => array('href'),
        'frame'      => array('src', 'longdesc'),
        'iframe'     => array('src', 'longdesc'),
        'head'       => array('profile'),
        'layer'      => array('src'),
        'input'      => array('src', 'usemap'),
        'form'       => array('action'),
        'area'       => array('href'),
        'link'       => array('href', 'src', 'urn'),
        'meta'       => array('content'),
        'param'      => array('value'),
        'applet'     => array('codebase', 'code', 'object', 'archive'),
        'object'     => array('usermap', 'codebase', 'classid', 'archive', 'data'),
        'script'     => array('src'),
        'select'     => array('src'),
        'hr'         => array('src'),
        'table'      => array('background'),
        'tr'         => array('background'),
        'th'         => array('background'),
        'td'         => array('background'),
        'bgsound'    => array('src'),
        'blockquote' => array('cite'),
        'del'        => array('cite'),
        'embed'      => array('src'),
        'fig'        => array('src', 'imagemap'),
        'ilayer'     => array('src'),
        'ins'        => array('cite'),
        'note'       => array('src'),
        'overlay'    => array('src', 'imagemap'),
        'q'          => array('cite'),
        'ul'         => array('src')
        );
    
    preg_match_all('#(<\s*style[^>]*>)(.*?)(<\s*/\s*style[^>]*>)#is', $_response_body, $matches, PREG_SET_ORDER);
    
    for ($i = 0, $count_i = count($matches);$i < $count_i;++$i)
    {
        $_response_body = str_replace($matches[$i][0], $matches[$i][1] . proxify_css($matches[$i][2]) . $matches[$i][3], $_response_body);
        }
    
    preg_match_all("#<\s*([a-zA-Z\?-]+)([^>]+)>#S", $_response_body, $matches);
    
    for ($i = 0, $count_i = count($matches[0]);$i < $count_i;++$i)
    {
        if (!preg_match_all("#([a-zA-Z\-\/]+)\s*(?:=\s*(?:\"([^\">]*)\"?|'([^'>]*)'?|([^'\"\s]*)))?#S", $matches[2][$i], $m, PREG_SET_ORDER))
            {
            continue;
            }
        
        $rebuild = false;
        $extra_html = $temp = '';
        $attrs = array();
        
        for ($j = 0, $count_j = count($m);$j < $count_j;$attrs[strtolower($m[$j][1])] = (isset($m[$j][4]) ? $m[$j][4] : (isset($m[$j][3]) ? $m[$j][3] : (isset($m[$j][2]) ? $m[$j][2] : false))), ++$j);
        
        if (isset($attrs['style']))
            {
            $rebuild = true;
            $attrs['style'] = proxify_inline_css($attrs['style']);
            }
        
        $tag = strtolower($matches[1][$i]);
        
        if (isset($tags[$tag]))
            {
            switch ($tag)
            {
            case 'a':
                if (isset($attrs['href']))
                    {
                    $rebuild = true;
                    $attrs['href'] = complete_url($attrs['href']);
                    }
                break;
            case 'img':
                if (isset($attrs['src']))
                    {
                    $rebuild = true;
                    $attrs['src'] = complete_url($attrs['src']);
                    }
                if (isset($attrs['longdesc']))
                    {
                    $rebuild = true;
                    $attrs['longdesc'] = complete_url($attrs['longdesc']);
                    }
                break;
            case 'form':
                if (isset($attrs['action']))
                    {
                    $rebuild = true;
                    
                    if (trim($attrs['action']) === '')
                        {
                        $attrs['action'] = $_url_parts['path'];
                        }
                    if (!isset($attrs['method']) || strtolower(trim($attrs['method'])) === 'get')
                        {
                        $extra_html = '<input type="hidden" name="' . $_config['get_form_name'] . '" value="' . encode_url(complete_url($attrs['action'], false)) . '" />';
                        $attrs['action'] = '';
                        break;
                        }
                    
                    $attrs['action'] = complete_url($attrs['action']);
                    }
                break;
            case 'base':
                if (isset($attrs['href']))
                    {
                    $rebuild = true;
                    url_parse($attrs['href'], $_base);
                    $attrs['href'] = complete_url($attrs['href']);
                    }
                break;
            case 'meta':
$meta_charset = '';
foreach($attrs as $keys => $values){
$keys = strtolower($keys);
$values = strtolower($values);


if (strstr($keys,'charset')) $meta_charset = $values;
if (strstr($values,'charset')){
$values = str_replace(' ','',$values);
$meta_charset = substr($values,strpos($values,'charset='));
}
}
                  $fp = fopen('xx.log','a+'); fwrite($fp,$meta_charset); fclose($fp);

                if ($_flags['strip_meta'] && isset($attrs['name']))
                    {
                    $_response_body = str_replace($matches[0][$i], '', $_response_body);
                    }
                if (isset($attrs['http-equiv'], $attrs['content']) && preg_match('#\s*refresh\s*#i', $attrs['http-equiv']))
                    {

                    if (preg_match('#^(\s*[0-9]*\s*;\s*url=)(.*)#i', $attrs['content'], $content))
                        {
                        $rebuild = true;
                        $attrs['content'] = $content[1] . complete_url(trim($content[2], '"\''));
                        }
                    }
                break;
            case 'head':
                if (isset($attrs['profile']))
                    {
                    $rebuild = true;
                    $attrs['profile'] = implode(' ', array_map('complete_url', explode(' ', $attrs['profile'])));
                    }
                break;
            case 'applet':
                if (isset($attrs['codebase']))
                    {
                    $rebuild = true;
                    $temp = $_base;
                    url_parse(complete_url(rtrim($attrs['codebase'], '/') . '/', false), $_base);
                    unset($attrs['codebase']);
                    }
                if (isset($attrs['code']) && strpos($attrs['code'], '/') !== false)
                    {
                    $rebuild = true;
                    $attrs['code'] = complete_url($attrs['code']);
                    }
                if (isset($attrs['object']))
                    {
                    $rebuild = true;
                    $attrs['object'] = complete_url($attrs['object']);
                    }
                if (isset($attrs['archive']))
                    {
                    $rebuild = true;
                    $attrs['archive'] = implode(',', array_map('complete_url', preg_split('#\s*,\s*#', $attrs['archive'])));
                    }
                if (!empty($temp))
                    {
                    $_base = $temp;
                    }
                break;
            case 'object':
                if (isset($attrs['usemap']))
                    {
                    $rebuild = true;
                    $attrs['usemap'] = complete_url($attrs['usemap']);
                    }
                if (isset($attrs['codebase']))
                    {
                    $rebuild = true;
                    $temp = $_base;
                    url_parse(complete_url(rtrim($attrs['codebase'], '/') . '/', false), $_base);
                    unset($attrs['codebase']);
                    }
                if (isset($attrs['data']))
                    {
                    $rebuild = true;
                    $attrs['data'] = complete_url($attrs['data']);
                    }
                if (isset($attrs['classid']) && !preg_match('#^clsid:#i', $attrs['classid']))
                    {
                    $rebuild = true;
                    $attrs['classid'] = complete_url($attrs['classid']);
                    }
                if (isset($attrs['archive']))
                    {
                    $rebuild = true;
                    $attrs['archive'] = implode(' ', array_map('complete_url', explode(' ', $attrs['archive'])));
                    }
                if (!empty($temp))
                    {
                    $_base = $temp;
                    }
                break;
            case 'param':
                if (isset($attrs['valuetype'], $attrs['value']) && strtolower($attrs['valuetype']) == 'ref' && preg_match('#^[\w.+-]+://#', $attrs['value']))
                    {
                    $rebuild = true;
                    $attrs['value'] = complete_url($attrs['value']);
                    }
                break;
            case 'frame':
            case 'iframe':
                if (isset($attrs['src']))
                    {
                    $rebuild = true;
                    $attrs['src'] = complete_url($attrs['src']) . '&nf=1';
                    }
                if (isset($attrs['longdesc']))
                    {
                    $rebuild = true;
                    $attrs['longdesc'] = complete_url($attrs['longdesc']);
                    }
                break;
            default:
                foreach ($tags[$tag] as $attr)
                {
                    if (isset($attrs[$attr]))
                        {
                        $rebuild = true;
                        $attrs[$attr] = complete_url($attrs[$attr]);
                        }
                    }
                break;
                }
            }
        
        if ($rebuild)
        {
            $new_tag = "<$tag";
            foreach ($attrs as $name => $value)
            {
                $delim = strpos($value, '"') && !strpos($value, "'") ? "'" : '"';
                $_value = strtolower($value);
                if(strstr($_value,'<br') or strstr($_value,'<b') or strstr($_value,'<p') or strstr($_value,'<u') or strstr($_value,'<font') or strstr($_value,'<clockquote')){
                    $new_tag .= ' ' . $name . ($value !== false ? '=' . $delim . $value : '');
                    }else{
                    $new_tag .= ' ' . $name . ($value !== false ? '=' . $delim . $value . $delim : '');
                    }
                }
            
            $_response_body = str_replace($matches[0][$i], $new_tag . '>' . $extra_html, $_response_body);
            }
        }
	}
    
# 此处追加编码处理 

# 提取meta中的charset,判断 charset

if ($_content_type == 'text/html' && !$_flags['source_page']){
    
    # preg_match_all("/<meta.+?charset=([-\w]+)/i", $_response_body, $meta_charset); 
    $char = str_replace(array('charset =','charset="','charset= '), "charset=", $_response_body);
    preg_match("'<meta.*?charset=(.+?)\".*?\>'s",$char,$meta_charset);
    if (isset($iso) && !empty($iso)) $charset = $iso; 
    elseif (isset($meta_charset[1]) && !empty($meta_charset[1])){
	$charset = str_replace(array(' ','"'), "", $meta_charset[1]);
        $charset = strtolower($charset); 
        if(strstr($charset,'iso-8859-1') && $lang == 'zh-cn') $charset = 'GBK';
        if(strstr($charset,'iso-8859-1') && $lang == 'zh-tw') $charset = 'BIG5';
        if(strstr($charset,'iso-8859-1') && $lang == 'zh-hk') $charset = 'BIG5-HKSCS';
    }else $charset = 'false';  // chkcode($_response_body);
  
    if(strstr($charset,'gb2312')) $charset = 'GBK';

# 转为 UTF-8 之后，HTML 实体
	
    if($charset !== 'utf-8' and $charset !== 'false'){
        if(extension_loaded('mbstring')) $_response_body = mb_convert_encoding($_response_body, 'UTF-8', $charset);
        elseif(extension_loaded('iconv')) $_response_body = iconv($charset, 'UTF-8//IGNORE//TRANSLIT', $_response_body);
        }
    if($charset !== 'false') $_response_body = utf2html($_response_body);
    }
	
# 附加微型URL表单框

if ($_flags['include_form'] && !isset($_GET['nf']))
    {
    $enc_url_var = 'document.form.' . $GLOBALS['_config']['url_var_name'] . '.value';
    $_url_form = '<div style="margin:0;text-align:center;border-bottom:1px #725554;color:#000000;background-color:#99CC66;font-size:12px;font-weight:bold;font-family:Bitstream Vera Sans,arial,sans-serif;padding:0px;">'
     . '<script src="./js/encode.js" type="text/javascript"></script>'   
     . '<form name="form" method="post" action="' . $_script_url . '" onsubmit="' . $enc_url_var . '=str2hex(window.btoa(' . $enc_url_var . '));">'
     . ' <label for="____' . $_config['url_var_name'] . '"><a href="' . $_url . '">' .$address.'</a>:</label> <input id="____' . $_config['url_var_name'] . '" type="text" size="80" name="' . $_config['url_var_name'] . '" value="' . $_url . '" />'
     . ' <input type="submit" name="go" value="'.$go.'" />'
     . ' [<a href="' . $_script_url . '?' . $_config['url_var_name'] . '=' . encode_url($_url_parts['prev_dir']) . ' ">'.$updir.'</a>, <a href="' . $_script_base . '">'.$mp.'</a>]'
     . '<br /><hr />';
        
    foreach ($_flags as $flag_name => $flag_value)
        {
        if(!$_frozen_flags[$flag_name]){
            $_url_form .= '<label><input type="checkbox" name="' . $_config['flags_var_name'] . '[' . $flag_name . ']"' . ($flag_value ? ' checked="checked"' : '') . ' /> ' . $_labels[$flag_name][0] . '</label> ';
            }
        }   
    $_url_form .= '</form></div>';
    if(extension_loaded('mbstring')) $_url_form = mb_convert_encoding($_url_form, 'UTF-8', $form_charset);
    elseif(extension_loaded('iconv')) $_url_form = iconv($form_charset, 'UTF-8//IGNORE//TRANSLIT', $_url_form);
    $_url_form = utf2html($_url_form);
    $_response_body = preg_replace('#\<\s*body(.*?)\>#si', "$0\n$_url_form" , $_response_body, 1);
    }


# 数据编码转换完毕，下面加密 HTML

if (!$_flags['source_page'] && $_flags['encrypt_page'] && !isset($_GET['nf']) && $_content_type == 'text/html' && $charset !== 'false'){
    
    $key = rand(0, 1) . rand(1, 9);
    $_response_body = encrypt($_response_body, $key);
    $_response_body = '<script type="text/javascript" src="./js/encode.js"></script><script type="text/javascript">' . "\n"
     . 'var data="' . $_response_body . '";'
     . 'var string=decrypt(data, "' . $key . '"); document.write(string); </script>';
    }

$_response_keys['content-disposition'] = 'Content-Disposition';
$_response_headers['content-disposition'][0] = empty($_content_disp) ? ($_content_type == 'application/octet_stream' ? 'attachment' : 'inline') . ';filename="' . $_url_parts['file'] . '"' : $_content_disp;
$_response_keys['content-length'] = 'Content-Length';
$_response_headers['content-length'][0] = strlen($_response_body);
$_response_headers = array_filter($_response_headers);
$_response_keys = array_filter($_response_keys);

header(array_shift($_response_keys));
array_shift($_response_headers);

foreach ($_response_headers as $name => $array){
    foreach ($array as $value){
        header($_response_keys[$name] . ': ' . $value, false);
        }
    }
# 若果指定压缩数设置

/*
if ($_config['compress_output'] && $_system['gzip'])
{
    ob_start('ob_gzhandler');
    }
*/
/*
if ($_config['compress_output'] && $_system['gzip'] && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && strstr($_content_type,'text/')){
    if(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'deflate') !== false){
        header('Content-Encoding: deflate');    
        //header("Vary: Accept-Encoding");    
        //header("Content-Length: ".strlen($_response_body)); 
        $_response_body = gzdeflate($_response_body, 9);
    }elseif(strpos($_SERVER['HTTP_ACCEPT_ENCODING'],'gzip') !== false){
        header('Content-Encoding: gzip');    
        //header("Vary: Accept-Encoding");    
        //header("Content-Length: ".strlen($_response_body)); 
        $_response_body = gzcompress($_response_body, 9);
    }else{   
        header('Content-Encoding: gzip');    
        //header("Vary: Accept-Encoding");  
        $_response_body = gzencode($_response_body, 3);
	    }
    }
*/
// header("Content-type: text/html;charset=$charset");
echo $_response_body;
?>
