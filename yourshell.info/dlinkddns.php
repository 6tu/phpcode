<?php
/*
 * D-Link ��̬�����ͻ���.������www.dlinkddns.com �� www.dlinkddns.com.cn
 * ���Ȼ�ȡ����IP����IPû�б仯����������У��������������IP����ִ�д�������EMAIL.
 * �õ��� CURL ��չ
 * 2013.5.28
*/
// ��ȡ���� IP
$ipurl        = 'http://iframe.ip138.com/ic.asp';
$ip           = get_onlineip($ipurl);
$ip = '14.134.204.99';
$iplog        = dirname(__FILE__) . '\ip.txt';
// �����˺��趨
$domain       = 'dlinkddns.com.cn';
$host         = 'lpszy';
$user         = 'tom112';
$pw           = 'qq000000';
// �ʼ��˺��趨
$smtp = array(
    'url'      => 'smtp.sina.com',           //�����������
    'port'     => '25', 
    'username' => 'safeboat@sina.com',       //�������˺�
    'password' => '0000000',                 //����������
    'from'     => 'safeboat@sina.com',       
    'to'       => '395636344@qq.com',        //��������
    'subject'  => '����� '.$host.'.'.$domain.' ��IP',
    'body'     => $ip,
    );
	
if(file_exists($iplog) == false) file_put_contents($iplog, '');
$oldip = file_get_contents($iplog);
if($oldip == $ip){
    echo "IP û�б仯";
    exit();
    }

file_put_contents($iplog, $ip);
$cookie_file = dirname(__FILE__) . "/cookie_" . md5(basename(__FILE__)) . ".txt"; 
vlogin('http://www.'.$domain.'/login', 'username=' . $user . '&pw=' . $pw);
$response_body = vlogin('http://www.'.$domain.'/host/'.$host.'.'.$domain, 'modify='.$host.'&host='.$host.'&domain='.$domain.'&ip='.$ip.'&commit=%E4%BF%9D+++%E5%AD%98');
header("Content-type: text/html; charset=gb2312");
if(stristr($response_body, 'updated')){
    @unlink($cookie_file); 
    echo "����IP�Ѹ���.";
    exit();
    }

@unlink($cookie_file); 
echo "�ύ���˺š�������IP���󣬻����Ƿ����������˲�����ԭ��\r\n";
echo '<br>�Ժ󽫷���IP��������䣬���ֶ�����<br><br> ';
echo 'һ���Ƿ��ʼ����̣�<br>';
echo '<pre>' . smtp($smtp) . '</pre>';

//����Ϊ��������

function get_onlineip($ipurl){
     $ch = curl_init($ipurl);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $a = curl_exec($ch);
     preg_match('/\[(.*)\]/', $a, $ip);
     return @$ip[1];
     }
function vlogin($url, $data){
     $curl = curl_init(); 
     curl_setopt($curl, CURLOPT_URL, $url); 
     curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); 
     curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); 
     curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); 
     curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
     curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
     curl_setopt($curl, CURLOPT_POST, 1); 
     curl_setopt($curl, CURLOPT_POSTFIELDS, @$data);
     curl_setopt($curl, CURLOPT_COOKIEJAR, $GLOBALS['cookie_file']);
     curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']);
     curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
     curl_setopt($curl, CURLOPT_HEADER, 0);
     curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
     $tmpInfo = curl_exec($curl);
     if (curl_errno($curl)){
         echo 'Errno' . curl_error($curl);
         }
     curl_close($curl);
     return $tmpInfo;
    }
function smtp($smtp){
    $CRLF = "\r\n";
    $header = array(
        'Return-path' => '<' . $smtp['from'] . '>',
        'Date' => date('r'),
        'From' => '<' . $smtp['from'] . '>',
        'MIME-Version' => '1.0',
        'Subject' => trim($smtp['subject']),
        'To' => $smtp['to'],
        'Content-Type' => 'text/plain; charset=gb2312',
        'Content-Transfer-Encoding' => 'base64'
        );
    $ret = '';
    foreach($header as $k => $v){
        $ret .= $k . ': ' . $v . "\n";
        }   
    $data = $ret . $CRLF . chunk_split(base64_encode($smtp['body']));
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $smtp['url']);
    curl_setopt($curl, CURLOPT_PORT, $smtp['port']);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    $content = "EHLO " . $smtp["url"] . $CRLF;
    $content .= "AUTH LOGIN" . $CRLF . base64_encode($smtp["username"]) . $CRLF . base64_encode($smtp["password"]) . $CRLF; 
    $content .= "MAIL FROM:" . $smtp["from"] . $CRLF;
    $content .= "RCPT TO:" . $smtp["to"] . $CRLF;
    $content .= "DATA" . $CRLF . $data . $CRLF . "." . $CRLF;
    $content .= "QUIT" . $CRLF;
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $content);
    curl_exec($curl); 
    curl_close($curl);
    return $content;
    }

?>