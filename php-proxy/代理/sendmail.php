<?php

date_default_timezone_set('Asia/Shanghai');
$log = $_SERVER["REMOTE_ADDR"] . '@' . date('Ymd His');
$db = './msg.log';
if(isset($_GET['msg']) && !isset($_POST['message']) && !isset($_POST['email'])){
    echo msg_form();
    exit();
    }
if(isset($_POST['message']) && !empty($_POST['message']) && isset($_POST['email']) && !empty($_POST['email'])){    
    $email = $_POST['email'];
    $message = $_POST['message'];
    $message = $email . "\r\n" . $message . "------------$log------------\r\n\r\n";
    $message = htmlspecialchars(addslashes(trim(ltrim(strtolower($message)))));
    $_torf = eregi("^[_.0-9a-z-]+@([0-9a-z][0-9a-z-]+.)+[a-z]{2,3}$", $email);
    
    $emai = "Error@yourshell.info";
    $headers = 'From: ' . $email . "\r\n" . 'Reply-To: ' . $email . "\r\n" . 'X-Mailer: PHP/' . phpversion();
    @mail('yourshell.info@gmail.com', '����', $message , $headers);
    
    if(!file_exists($db)) file_put_contents($db, $message);
    $old_message = file_get_contents($db);
    $_data = $message . $old_message;
    file_put_contents($db, $_data);
    echo msg_form();
    
    if($_torf == true){
        echo '<br />лл����Ϣ���ͳɹ�<br /></center></body></html>';
        exit(0);
        }else{
        echo '<br />����email���������޷���ϵ��������д��ȷ��EMAIL<br /></center></body></html>';
        // header('Location: index.php');
        exit(0);
    }
    }
function msg_form(){
    $form = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=gb2312" /><title>��ϵ</title></head>'
     . '<body style="background-color=#99CC66"><br /><br /><br /><center><h3>�� �� ��</h3>'
     . '<form name="form1" method="post" action="' . $_SERVER['PHP_SELF'] . '">'
     . '<table><tr><td><input name="email" type="text" value="E-mail:" style="width:521px"></td></tr>'
     . '<tr><td><textarea name="message" style="height:200px;width:521px" ></textarea></td></tr></table>'
     . '<br /><input type="submit" value="�� ��"></form> ';
    return $form;
    }
?>