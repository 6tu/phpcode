<?php
//ini_set('SMTP', '74.125.93.27');
//ini_set('smtp_port', '465');
$db='./data/messages.db';

date_default_timezone_set('Asia/Shanghai');
$ip=$_SERVER["REMOTE_ADDR"];
$time=date('Ymd His');
$log=$ip.'@'.$time;
if(!isset($_POST['message']) && !isset($_POST['email']) ){
echo message_form();
exit();
}
if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['email']) && !empty($_POST['email'])){

$email=$_POST['email'];
$message=$_POST['message'];
$_torf=eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$",$email);
if(!$_torf==true){
$message=$email."\r\n".$message;
$emai="Error@yourshell.info";
}
$headers = 'From: '.$email . "\r\n" .
    'Reply-To: '.$email . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$message=htmlspecialchars(addslashes(trim(ltrim(strtolower($message)))));
@mail('yourshell.info@gmail.com', '��ѯ����', $message ,$headers);
//smtp_mail($to,$subject,$body);
if(!file_exists($db)) {
mkdir($db, 0744);
} 
$old_message=file_get_contents($db);
$_data=$email."\r\n".$message."\r\n------------$log------------\r\n\r\n".$old_message;
file_put_contents($db,$_data);
echo message_form();

echo "\r\n<br /><center>лл����Ϣ���ͳɹ�\r\n<br />";
if(!$_torf==true) echo "����email���������޷���ϵ��������д��ȷ��EMAIL\r\n<br />";

//header('Location: index.php');

exit();
}

function smtp_mail($to,$subject,$body){
    
$host = "smtp.yeah.net";
$port ='25';
$username = "mail2www";
$passwd = "qq0000000";
$from = "<mail2www@yeah.net>";
$to ='<'.$to.'>';


$host = "ssl://smtp.gmail.com";
$port='465';
$username = "mail23w";
$passwd = "qq0000000";
$from = "<mail23w@gmail.com>";
$to =$to;



// ���� header ����
$mime_boundary = md5(time());
$header = "MIME-Version:1.0\r\n";
$header .= "Content-Type: multipart/mixed;" ."boundary=" . $mime_boundary . "\r\n";
$header .= "To: ".$to."\r\n";
$header .= "From: sendnews $from \r\n";
$header .= "Subject: ".$subject."\r\n";
$header .= "Date: ".date("r")."\r\n";
$header .= "X-Mailer:By sendnews (PHP/".phpversion().")\r\n";

// ���� body ����

$body ="--{$mime_boundary}\n" .
     "Content-Type: text/html; charset=\"GBK\"\n" .
     "Content-Transfer-Encoding: 7bit\r\n\r\n" .
     $body . "\n\n".
     "--{$mime_boundary}--\r\n" ;

    // socket����
    $sock = fsockopen($host, $port);
    if ($sock){
         set_socket_blocking($sock, true);
         $info = fgets($sock, 512);

        // �û���֤
        fputs($sock, "HELO sendnews" . "\r\n");
        $info = fgets($sock, 2000);
         fputs($sock, "AUTH LOGIN" . "\r\n");
        $info = fgets($sock, 2000);
         fputs($sock, base64_encode($username) . "\r\n");
        $info = fgets($sock, 2000);
        fputs($sock, base64_encode($passwd) . "\r\n");
        $info = fgets($sock, 2000);

        // �������ʼ�
        fputs($sock, "MAIL FROM:$from" . "\r\n");
         $info = fgets($sock, 512);
        fputs($sock, "RCPT TO:$to" . "\r\n");
        $info = fgets($sock, 2000);
        fputs($sock, "DATA" . "\r\n");
        $info = fgets($sock, 2000);




        // ����ɹ��������ʼ�
        if (ereg("^354", $info)){
             // echo "����������������ʼ����ݳɹ���" .$info. "<br>";
fputs($sock, $header."\r\n".$body);
             fputs($sock, "." . "\r\n");
             $info = fgets($sock, 2000);
            
            
            // ���ͽ������
            if (ereg("^250", $info)){
                 return "OK\r\n<br>";
                 }else{
                 return "�����ʼ�����ʧ�ܣ�" . $info . "\r\n<br>";
                 }
             }else{
             return "����������������ʼ�����ʧ�ܣ�" . $info . "<br>";
             }
        
        // �ʼ�������ɣ����Ͽ�socket����
        fputs($sock, "QUIT" . "\r\n");
         $info = fgets($sock, 2000);
        
        fclose($sock);
        }
    }



function message_form(){
print<<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>��ϵ</title>
</head>
<body>
<br /><br /><br />
<center>������д����</center>
<table style="width:400px"  align="center"  cellspacing="1">

<tr><td style="width:400px" align="center">
<form name="form1" method="post" action="./message.php">       
<tr><td style="width:50px" align="right">email:</td>
<td style="width:350px" align="left">
<input name="email" type="text" style="width:300px" maxlength="100"></td></tr>

<tr><td style="width:50px" align="right">����:</td>
<td style="width:350px" align="left">
<textarea name="message" style="height:150px;width:300px" ></textarea></td></tr>
<tr><td style="width:20px"  align="right"></td>
<td style="width:350px" align="center"><br />
<input type="submit" value="�� ��" id="sub"></td></tr>
</form> 
</td></tr>
</table>
HTML;
}
?>