<?php

date_default_timezone_set('Asia/Shanghai');
$log = $_SERVER["REMOTE_ADDR"] . '@' . date('Ymd His');
$db = './msg.log';
//message = 'msg_d642340b7'; email = 'email_efab23731';
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
    file_put_contents($db, $_data);
    echo msg_form();
    
    if($_torf == true){
        echo '<br />谢谢，信息发送成功<br /></center></body></html>';
        exit(0);
        }else{
        echo '<br />您的email_efab23731可能有误，无法联系您，请填写正确的email_efab23731<br /></center></body></html>';
        // header('Location: index.php');
        exit(0);
    }
    }
function msg_form(){
    $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>联系</title></head>'
     . '<body style="background-color=#99CC66"><br /><br /><br /><center><h3>留 言 本</h3>'
     . '<form name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">'
     . '<table><tr><td><input name="email_efab23731" type="text" value="E-mail:" style="width:521px"></td></tr>'
     . '<tr><td><textarea name="msg_d642340b7" style="height:200px;width:521px" ></textarea></td></tr></table>'
     . '<br /><input type="submit" value="提 交"></form> ';
    return $form;
    }
?>