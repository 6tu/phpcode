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
//include("inc/class.smtp.php");                    // 可选, 若 class.phpmailer.php 中没有加载，则此处加载
//include('pop.php');

$mail = new PHPMailer(true);                        // true 表示返回 errors, 
$mail->SetLanguage("zh-cn", "inc/language/");       // 语言设置，可省
$mail->IsSMTP();                                    // 申明使用 SMTP 模式
//$mail->IsSendmail();                              // 申明使用 SendMail 模式
                                                    //若此处没有申明，则使用 Mail 模式

try {

if(0 < date("H") <= 6 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // 服务器协议，非加密时此句注释掉
  $mail->Host       = "smtp.gmail.com";             // 服务器地址
  $mail->Port       = 465;                          // 服务器端口
  $mail->Username   = "yourshell.info@gmail.com";   // 用户名
  $mail->Password   = "qq0000000";                  // 密码
}
if(6 < date("H") <= 12 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // 服务器协议，非加密时此句注释掉
  $mail->Host       = "smtp.gmail.com";             // 服务器地址
  $mail->Port       = 465;                          // 服务器端口
  $mail->Username   = "yourshell.info@gmail.com";   // 用户名
  $mail->Password   = "qq0000000";                  // 密码
}
if(12 <  date("H") <= 18){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // 服务器协议，非加密时此句注释掉
  $mail->Host       = "smtp.gmail.com";             // 服务器地址
  $mail->Port       = 465;                          // 服务器端口
  $mail->Username   = "yourshell.info@gmail.com";   // 用户名
  $mail->Password   = "qq0000000";                  // 密码
}
if(18 <  date("H") <= 24 ){
  $mail->Host       = "gmail.com";                  // SMTP server
  $mail->SMTPSecure = "ssl";                        // 服务器协议，非加密时此句注释掉
  $mail->Host       = "smtp.gmail.com";             // 服务器地址
  $mail->Port       = 465;                          // 服务器端口
  $mail->Username   = "yourshell.info@gmail.com";   // 用户名
  $mail->Password   = "qq0000000";                  // 密码
}


  $mail->SMTPDebug  = 2;                            // debug 模式
  $mail->SMTPAuth   = true;                         // 登陆认证
  $mail->SetFrom($mail->Username, 'Foo');    //发送地址
  $mail->AddReplyTo('name@yourdomain.com', 'tw'); //回复地址
  $mail->AddAddress('whoto@upup.info', 'John Doe'); //发送目标地址

  
  $mail->Subject = 'PHPMailer Test';                      //邮件标题
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // 可选 - MsgHTML 将替换这里
//$mail->MsgHTML(file_get_contents('contents.html'));     // 读取文件内容
//$mail->AddAttachment('images/phpmailer.gif');           // 附件
//$mail->AddAttachment('images/phpmailer_mini.gif');      // 附件
  $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage();                               //PHPMailer 定义的 error
} catch (Exception $e) {
  echo $e->getMessage();                                 //所有的 error!
}

//include('pop.php');
list($usec,$sec)=explode(' ',microtime());
file_put_contents('log/time.log',$sec);
echo file_get_contents('log/ip.htm');
?>

</body>
</html>
