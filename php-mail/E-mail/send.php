<?php
header("Content-type: text/html; charset=utf-8");
echo '<html><head><title>PHPMailer - get ip test</title></head><body>';

set_time_limit(30);
date_default_timezone_set('Asia/Shanghai');
if(file_exists('log/time.log')){
$t0=file_get_contents('log/time.log');
}else{
mkdir('log',0777);
@chmod('log');
$t0=0;
file_put_contents('log/time.log',$t0);
}
list($usec,$sec)=explode(' ',microtime());
if(($sec-$t0) < 600){
echo file_get_contents('ip.htm');
exit(0);
}

require_once('inc/class.phpmailer.php');
//include("inc/class.smtp.php");                    // ��ѡ, �� class.phpmailer.php ��û�м��أ���˴�����
//include('pop.php');

$mail = new PHPMailer(true);                        // true ��ʾ���� errors, 
$mail->SetLanguage("zh-cn", "inc/language/");       // �������ã���ʡ
$mail->IsSMTP();                                    // ����ʹ�� SMTP ģʽ
//$mail->IsSendmail();                              // ����ʹ�� SendMail ģʽ
                                                    //���˴�û����������ʹ�� Mail ģʽ

try {

if(0 < date("H") <= 6 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // ������Э�飬�Ǽ���ʱ�˾�ע�͵�
  $mail->Host       = "smtp.gmail.com";             // ��������ַ
  $mail->Port       = 465;                          // �������˿�
  $mail->Username   = "yourshell.info@gmail.com";   // �û���
  $mail->Password   = "qq0000000";                  // ����
}
if(6 < date("H") <= 12 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // ������Э�飬�Ǽ���ʱ�˾�ע�͵�
  $mail->Host       = "smtp.gmail.com";             // ��������ַ
  $mail->Port       = 465;                          // �������˿�
  $mail->Username   = "yourshell.info@gmail.com";   // �û���
  $mail->Password   = "qq0000000";                  // ����
}
if(12 <  date("H") <= 18){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // ������Э�飬�Ǽ���ʱ�˾�ע�͵�
  $mail->Host       = "smtp.gmail.com";             // ��������ַ
  $mail->Port       = 465;                          // �������˿�
  $mail->Username   = "yourshell.info@gmail.com";   // �û���
  $mail->Password   = "qq0000000";                  // ����
}
if(18 <  date("H") <= 24 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // ������Э�飬�Ǽ���ʱ�˾�ע�͵�
  $mail->Host       = "smtp.gmail.com";             // ��������ַ
  $mail->Port       = 465;                          // �������˿�
  $mail->Username   = "yourshell.info@gmail.com";   // �û���
  $mail->Password   = "qq0000000";                  // ����
}


  $mail->SMTPDebug  = 2;                            // debug ģʽ
  $mail->SMTPAuth   = true;                         // ��½��֤
  $mail->SetFrom($mail->Username, 'Foo');    //���͵�ַ
  $mail->AddReplyTo('name@yourdomain.com', 'tw'); //�ظ���ַ
  $mail->AddAddress('whoto@upup.info', 'John Doe'); //����Ŀ���ַ

  
  $mail->Subject = 'PHPMailer Test';                      //�ʼ�����
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // ��ѡ - MsgHTML ���滻����
//$mail->MsgHTML(file_get_contents('contents.html'));     // ��ȡ�ļ�����
//$mail->AddAttachment('images/phpmailer.gif');           // ����
//$mail->AddAttachment('images/phpmailer_mini.gif');      // ����
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage();                               //PHPMailer ����� error
} catch (Exception $e) {
  echo $e->getMessage();                                 //���е� error!
}

//include('pop.php');
list($usec,$sec)=explode(' ',microtime());
file_put_contents('log/time.log',$sec);
echo file_get_contents('log/ip.htm');
?>

</body>
</html>
