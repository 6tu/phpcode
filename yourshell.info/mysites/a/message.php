<?php
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
@mail('yourshell.info@gmail.com', '查询反馈', $message ,$headers);
if(!file_exists($db)) {
mkdir($db, 0744);
} 
$old_message=file_get_contents($db);
$_data=$email."\r\n".$message."\r\n------------$log------------\r\n\r\n".$old_message;
file_put_contents($db,$_data);
echo message_form();

echo "\r\n<br /><center>谢谢，信息发送成功\r\n<br />";
if(!$_torf==true) echo "您的email可能有误，无法联系您，请填写正确的EMAIL\r\n<br />";

//header('Location: index.php');

exit();
}
function message_form(){
print<<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>联系</title>
</head>
<body>
<br /><br /><br />
<center>有问题写这里</center>
<table style="width:400px"  align="center"  cellspacing="1">

<tr><td style="width:400px" align="center">
<form name="form1" method="post" action="./message.php">       
<tr><td style="width:50px" align="right">email:</td>
<td style="width:350px" align="left">
<input name="email" type="text" style="width:300px" maxlength="100"></td></tr>

<tr><td style="width:50px" align="right">内容:</td>
<td style="width:350px" align="left">
<textarea name="message" style="height:150px;width:300px" ></textarea></td></tr>
<tr><td style="width:20px"  align="right"></td>
<td style="width:350px" align="center"><br />
<input type="submit" value="提 交" id="sub"></td></tr>
</form> 
</td></tr>
</table>
HTML;
}
?>