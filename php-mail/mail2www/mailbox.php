<?php
/*��ʱ���������ú����ɼ�¼�ļ�����*/
set_time_limit(0);
date_default_timezone_set('Asia/Shanghai');
$log = $_SERVER["REMOTE_ADDR"] . '@' . date('Ymd His');
$mail_log = './log/mail.log';
$temp_log = './log/'.rand(1000,9999). '.tmp';
$datadir='wwwdata/';

$from = 'mail23w@gmail.com';
$mime_boundary = md5(time());

include_once('lib/mime_parser.php');
include_once('lib/rfc822_addresses.php');
include_once("lib/pop3.php");
include_once("lib/urlcheck.php");
include_once('lib/pclzip.lib.php');
//include_once("lib/sasl/sasl.php");              # ��ʹ�� SASL ��֤��ʽʱ��Ҫע��

stream_wrapper_register('pop3', 'pop3_stream');   # ע�� pop3 ���̿���(handler)��
$pop3 = new pop3_class;
  
$user     = "walk";                               # Authentication user name
$password = "*********";                          # Authentication password 
$pop3 -> hostname = "xx.xxx.net";                 # POP 3 server host name
$pop3 -> port = 110;                              # POP 3 server host port,Gmail uses 995
$pop3 -> tls = 0;                                 # Establish secure connections using TLS
$pop3 -> realm = "";                              # Authentication realm or domain 
$pop3 -> workstation = "";                        # Workstation for NTLM authentication
$apop = 0;                                        # Use APOP authentication
$pop3 -> authentication_mechanism = "USER";       # SASL authentication mechanism
$pop3 -> debug = 1;                               # Output debug information
$pop3 -> html_debug = 1;                          # Debug information is in HTML
$pop3 -> join_continuation_header_lines = 1;      # Concatenate headers split in multiple lines
if(($error = $pop3 -> Open()) == ""){
    //echo "<PRE>Connected to the POP3 server &quot;" . $pop3 -> hostname . "&quot;.</PRE>\n";
if(($error = $pop3 -> Login($user, $password, $apop)) == ""){
    //echo "<PRE>User &quot;$user&quot; logged in.</PRE>\n";
    if(($error = $pop3 -> Statistics($messages, $size)) == ""){
        #echo "<PRE>$messages messages, total of $size bytes.</PRE>\n";
        echo '';
        if($messages == 0) exit;
        if($messages > 0){
            $pop3 -> GetConnectionName($connection_name);
            $message = 1;
            $message_file = 'pop3://' . $connection_name . '/' . $message;
            $mime = new mime_parser_class;
            $mime -> decode_bodies = 1;            # Set to 0 for not decoding the message bodies
	    $parameters = array(
	        'File'  => $message_file, 
		//'Data'=>'My message data string',# Read a message from a string instead of a file
		//'SaveBody'=>'/tmp',              # Save the message body parts to a directory
		'SkipBody' => 0,                   # Do not retrieve or save message body parts
		);
            $success = $mime -> Decode($parameters, $decoded);
            if($success && $mime -> Analyze($decoded[0], $results)){
                //echo '<h2>Message analysis</h2>' . "\n";
                //echo '<pre>';
                //var_dump($results);
                //echo '</pre>';

                $encoding  = $results['Encoding'];
                $date      = $results['Date'];
                //$from_name = $results['From'][0]['name'];
                $from_addr = $results['From'][0]['address'];
                $subject   = $results['Subject'];

                $data = $results['Data'];
                $data = html2wml($results['Data']);
                $data = str_replace("<p><br/></p>\n", '', $data);
                $data = str_replace("<p>", '', $data);
                $data = str_replace("</p>\n", '', $data);
                //echo $from_addr . "\r\n<br />" . $subject . "\r\n<br /><br />\r\n\r\n";
                //echo $data;
                }
            else echo 'MIME message analyse error: ' . $mime -> error . "\n";
            $m = $from_addr. '; ' .$date. '; ' .$encoding. '; ' .$subject. ";\r\n" .$data. "\r\n=========\r\n";
            $reply = str_replace(';','<br />',$m);
	    $old_m = @file_get_contents($mail_log);
	    $m .= $old_m;
	    @file_put_contents($mail_log,$m);
	    //$pop3 -> DeleteMessage($message);
            }
	     
        }
    if($error == "" && ($error = $pop3 -> Close()) == "") echo "done \n<br />"; //$pop3 -> hostname ." \n";
    }
}
if($error != "") echo "<H2>Error: ", HtmlSpecialChars($error), "</H2>";

# ��ȡ $data �е���Ч URL �Ͷ�Ӧ���ļ���  
$_url = new urlcheck();

$url = explode('<br/>',$data);
$url = str_replace('get ','',$url);
$n = count($url);
$eff_url='';
for($i = 0; $i < $n; $i++){
    $url[$i] = trim(ltrim($url[$i]));
    $url_sub = @parse_url($url[$i]);
    if(!isset($url_sub['scheme'])) $url[$i] = 'http://'. $url[$i];
	if(!isset($url_sub['host'])) $url[$i] = '';
    $url[$i] = str_replace(' ','%20',$url[$i]);
    if($_url -> check($url[$i]) == 1)  $eff_url .= $url[$i]."\r\n";
    }

# û�п��õ� URL ��ֹ��������ʾ�ʼ�
$headers = "From: $from\nDate: " .date('r'). "\nX-Mailer: http://yourshell.info\n";
$headers .= "MIME-Version: 1.0\nContent-Type: multipart/mixed;\t boundary=\"{$mime_boundary}\"\n\n";

$htmlbody = "This is a multi-part message in MIME format.\n\n"
      . "--{$mime_boundary}\nContent-Type: text/html; charset=\"GBK\"\nContent-Transfer-Encoding: base64\n\n" .help(). "\n\n";

if($eff_url == ''){
$subject = 'û�п��õ� URL';
$htmlbody .= "\n\n--{$mime_boundary}--\n";
$ok = @mail($from_addr, $subject, $htmlbody, $headers);
if (!$ok) echo smtp_mail($from_addr, $subject, $htmlbody, $headers);
exit(0);
}

# ��ȡ��ЧURL���ļ�
$url = explode("\r\n",$eff_url);
$n = count($url) - 1; if($n > 2) $n = 2;

for($i = 0; $i < $n; $i++){
   $temp = getpage($url[$i],$referer='',$header=1);

   # ����ʧ�ܵ�URL����ֹ��������ʾ�ʼ�
   if(($temp[0] == 'false')){
      $subject = '�޷���ȡURL������ļ�';
      $htmlbody .= "\n\n--{$mime_boundary}--\n";
      $ok = @mail($from_addr, $subject, $htmlbody, $headers);
      if (!$ok) echo smtp_mail($from_addr, $subject, $htmlbody, $headers);
      exit(0);
      }
   preg_match('/Content-Length:(.*)/si',$temp[0],$lenth);
   
   # ����3M����ֹ��������ʾ�ʼ�
   if(trim($lenth[1]) > 3146000){
      $subject = 'ĳ��URL������ļ�����3M';
      $htmlbody .= "\n\n--{$mime_boundary}--\n";
      $ok = @mail($from_addr, $subject, $htmlbody, $headers);
      if (!$ok) echo smtp_mail($from_addr, $subject, $htmlbody, $headers);
      exit(0);
      }
   $cmd = "wget -b -c -p --no-check-certificate -P $datadir$from_addr -o $temp_log $url[$i]";
   exec($cmd, $output, $status);
   preg_match("'pid (.+).'s",$output[0],$match);
   $PID = $match[1];
   $i = 1;
   while ($i <= 30) {
      $stat = is_running($PID);
      sleep(3);
      if($stat == 0) break;
      $i++;
      }



/* 
 * #�������ļ�·�����ļ�����������֮��Ȼ��ѹ��
 * if (!file_exists('./temp')) (@mkdir("/temp", 0777))? $temp='temp/': 
 * $url_sub = @parse_url($url[$i]);
 * $file_name = substr(@$url_sub['path'], strrpos(@$url_sub['path'], "/") + 1);
 * if($file_name == '') $file_name = rand(10,99).'_index.html';
 * //echo $file_name;
 * $html = getpage($url[$i]);
 * file_put_contents($file_name,$html[1]);
 * if(strstr(strtolower($html[0]),'content-type: text/html')){
 * # ����ץȡCSS��JS����ͼƬ·������������
 * }
 */
    }
	

# ѹ���������ʼ�
$zip_file_name = $from_addr.'.zip';
$archive = new PclZip($zip_file_name);

$v_list = $archive->create($datadir.$from_addr,
                           PCLZIP_OPT_REMOVE_PATH, $datadir.$from_addr,
                           PCLZIP_OPT_ADD_PATH, '');
if ($v_list == 0) {

      $subject = '��������ȡ�ļ�ʧ��';
      $htmlbody .= "\n\n--{$mime_boundary}--\n";
      $ok = @mail($from_addr, $subject, $htmlbody, $headers);
      if (!$ok) echo smtp_mail($from_addr, $subject, $htmlbody, $headers);
    die("Error : ".$archive->errorInfo(true));
}

$file_type = 'application/x-zip-compressed';
$data = file_get_contents($zip_file_name);
$data = chunk_split(base64_encode($data));

# Base64 ���븽������ӵ��ʼ���
$body = $htmlbody . "--{$mime_boundary}\nContent-Type: {$file_type};\t name=\"{$zip_file_name}\"\n"
       . "Content-Transfer-Encoding: base64\n\n" .$data . "\n\n--{$mime_boundary}--\n";

# �����ʼ�
$subject = 'Re: get';
$ok = @mail($from_addr, $subject, $body, $headers);
if(!$ok) echo smtp_mail($from_addr, $subject, $body, $headers);

$dir = getcwd().'/'.$datadir.$from_addr;
$dir = str_replace('./','/',$dir);
@del_dir($dir,$only_empty = FALSE);
@unlink($temp_log);
@unlink($zip_file_name);
exit(0);




function is_running($PID){
    if(PHP_OS == 'WINNT'){
       exec("tasklist |find \"$PID\"", $ProcessState);
       return(count($ProcessState) >= 1); 
       }else{
        exec("ps $PID", $ProcessState);
        return(count($ProcessState) >= 2);    
        }  
    }



function help(){
    global $reply;
    $html = '������������⣬�����������ʾ:' . "\r\n<br /><br />"
     . '* ��ҳ���ͼƬ�����⣬��������ļ�·���������޸�����ļ�����Ӧ·��' . "\r\n<br />"
     . '* û��ʶ�𵽿��õ���ַ���ʼ�����Ҫ��ÿ��һ����Ч����ַ����ַ�в�����������ź����ģ�ֻ����ǰ������ַ' . "\r\n<br />"
     . '* ����ȡ��ĳ���ļ�̫��Ϊ��Ӧ�ձ�� WWW �� EMAIL ���ļ���С�����ƣ������ļ�Ҫ�󲻴��� 3 MB' . "\r\n<br />"
     . '* ��ĳ����ַ��Ч'. "\r\n<br /><br />"
     . '* �������ʼ�������get������ᱻ gmail ����' . "\r\n<br /><br /><hr>\r\n<br />"
     . $reply. "\r\n<br />";
    return chunk_split(base64_encode($html));
    }

function getpage($url,$referer='',$header=0){

    if(!$_SERVER['HTTP_ACCEPT_LANGUAGE']){
        $lang = 'en';
        }else{
        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }

    $url = @parse_url($url);
    if(isset($url['query'])) $url_get = $url['path'] . '?' . $url['query'];
    elseif(isset($url['path'])) $url_get = $url['path'] ;
    else $url_get = '/';
    if (empty($referer)) $referer = $url['host'];   
    $temp = '';
    
    if(!strstr(get_cfg_var("disable_functions") , 'fsockopen')){
        $fp = @fsockopen($url['host'], 80, $errno, $errstr, 30);
        if (!$fp){
	    $http_code = '404';
            return array('false','');
            }else{
            $out = "GET $url_get HTTP/1.0\r\n";
            $out .= "Host: $url[host]\r\n";
            $out .= "Accept-Language: $lang\r\n";
            $out .= "Referer: $referer \r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            while (!feof($fp)){
                $temp .= fgets($fp, 128);
                if($header == 1 && strstr($temp,"\r\n\r\n")) break;
                }
            fclose($fp);
            $http_code = substr($temp, 9, 3);
			
            $temp = explode("\r\n\r\n", $temp, 2);
            $temp = array($temp[0],$temp[1]);
            }
        }elseif(extension_loaded('curl') && !strstr(get_cfg_var("disable_functions") , 'curl_init')){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $referer); 
	if($header == 1) curl_setopt($ch, CURLOPT_NOBODY, 1);  
        $temp = curl_exec ($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
	$temp = explode("\r\n\r\n", $temp, 2);
        $temp = array($temp[0],$temp[1]);
        }else{
        $http_code = '0';
        $temp = array('header: No','the hosting do not support curl or fsockopen()');
        }
    if ($http_code >= 400){ // 400 - 600���Ƿ���������
        return array('false','');
        // exit(0);
    }else{
        return $temp;
        }
    }

# text2wml,html2wml������ɾ���ַ����е�HTML��ǡ�
function text2wml($content){
$content = str_replace('$', '$$', $content);
$content = str_replace("\r\n", "\n", htmlspecialchars($content));
$content = explode("\n", $content);
for ($i = 0; $i < count($content); $i++){
    $content[$i] = trim($content[$i]);
    if (str_replace("��", "", $content[$i]) == "")  $content[$i] = "";
    }
$content = str_replace("<p><br /></p>\n", "", "<p>" . implode("<br/></p>\n<p>", $content) . "<br /></p>\n");
return $content;
}
function html2wml($content){
$content = preg_replace('#(<\s*title[^>]*>)(.*?)(<\s</123456789>title[^>]*>)#is', '', $content);
$content = preg_replace('#(<\s*style[^>]*>)(.*?)(<\s</123456789>style[^>]*>)#is', '', $content);
$content = preg_replace('#(<\s*script[^>]*>)(.*?)(<\s</123456789>script[^>]*>)#is', '', $content);
$content = preg_replace("/<br\s*\/?>/i", "\n", $content);
$content = preg_replace("/<\/?p>/i", "\n", $content);
$content = preg_replace("/<\/?td>/i", "\n", $content);
$content = preg_replace("/<\/?div>/i", "\n", $content);
$content = preg_replace("/<\/?blockquote>/i", "\n", $content);
$content = preg_replace("/<\/?li>/i", "\n", $content);
$content = preg_replace("/\&nbsp\;/i", " ", $content);
$content = preg_replace("/\&nbsp/i", " ", $content);
$content = strip_tags($content);
$content = html_entity_decode($content, ENT_QUOTES, "GB2312");
$content = preg_replace("/\&\#.*?\;/i", "", $content);
return text2wml($content);
}
function del_dir($dir,$only_empty = FALSE){
    $CWD = getcwd();
    if(chdir($dir) == FALSE) return FALSE;
    
    $dscan = array(realpath($dir));
    $darr = array();
    while(!empty($dscan)){
        $dcur = array_pop($dscan);
        $darr[] = $dcur;
        if($d = opendir($dcur)){
            while(($f = readdir($d)) !== FALSE){
                if(($f == '.') or ($f == '..')) continue;
                $f = $dcur . DIRECTORY_SEPARATOR . $f;
                
                if(is_dir($f) and (!is_link($f))) $dscan[] = $f;
                else unlink($f);
                }
            closedir($d);
            }
        }
    
    $i_until = ($only_empty) ? 1 : 0;
    for($i = count($darr) - 1; $i >= $i_until; $i--){
        // echo "\nDeleting '".$darr[$i]."' ... ";
        rmdir($darr[ $i ]);
        }
    
    $result = ($only_empty) ? (count(scandir) <= 2) : (!is_dir($dir));
    chdir($CWD);
    
    return $result;
    }

function smtp_mail($to, $subject, $body, $headers){
    
$host = "smtp.yeah.net";
$port ='25';
$username = "mail2www";
$passwd = "**************";
$from = "<mail2www@yeah.net>";
$to ='<'.$to.'>';

/*
$host = "ssl://smtp.gmail.com";
$port='465';
$username = "mail23w";
$passwd = "****************";
$from = "<mail23w@gmail.com>";
$to =$to;
*/
# ���� header ����
$headers = "To: $to\nSubject: $subject\n" .$headers;

# socket����
$sock = fsockopen($host, $port);
if ($sock){
    set_socket_blocking($sock, true);
    $info = fgets($sock, 512);
    
    # �û���֤
    fputs($sock, "HELO sendnews" . "\r\n");
    $info = fgets($sock, 2000);
    fputs($sock, "AUTH LOGIN" . "\r\n");
    $info = fgets($sock, 2000);
    fputs($sock, base64_encode($username) . "\r\n");
    $info = fgets($sock, 2000);
    fputs($sock, base64_encode($passwd) . "\r\n");
    $info = fgets($sock, 2000);
    
    # �������ʼ�
    fputs($sock, "MAIL FROM:$from" . "\r\n");
    $info = fgets($sock, 512);
    fputs($sock, "RCPT TO:$to" . "\r\n");
    $info = fgets($sock, 2000);
    fputs($sock, "DATA" . "\r\n");
    $info = fgets($sock, 2000);
    
    # ����ɹ��������ʼ�
    if (ereg("^354", $info)){
        # echo "����������������ʼ����ݳɹ���" .$info. "<br>";
        fputs($sock, $headers . "\r\n" . $body);
        fputs($sock, "." . "\r\n");
        $info = fgets($sock, 2000);
        
        # ���ͽ������
        if (ereg("^250", $info)){
            return "OK\r\n<br>";
            }else{
            return "�����ʼ�����ʧ�ܣ�" . $info . "\r\n<br>";
            }
        }else{
        return "����������������ʼ�����ʧ�ܣ�" . $info . "<br>";
        }
    
    # �ʼ�������ɣ��Ͽ�socket����
    fputs($sock, "QUIT" . "\r\n");
    $info = fgets($sock, 2000);
    
    fclose($sock);
    }
}


?>