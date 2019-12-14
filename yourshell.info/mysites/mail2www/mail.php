<?php
date_default_timezone_set ('Asia/Shanghai'); 
if(!isset($_POST['to'])){
    echo mailform();
    exit(0);
    }

// 读取 POST 请求值
$to = $_POST['to'];
$from = $_POST['from'];
$subject = $_POST['subject'];
$message = base64_encode($_POST['message']);

// 包含上传的文件
$fileatt = $_FILES['fileatt']['tmp_name'];
$fileatt_type = $_FILES['fileatt']['type'];
$fileatt_name = $_FILES['fileatt']['name'];

$headers = "From: $from\nTo: $to\nSubject: $subject\nDate: " .date('r'). "\nX-Mailer: http://yourshell.info";

if (is_uploaded_file($fileatt)){
    // 二进制方式读取附件 ('rb' = read binary)
    $file = fopen($fileatt, 'rb');
    $data = fread($file, filesize($fileatt));
    fclose($file);
    
    // 产生一个分界线(boundary)符
    $semi_rand = md5(time());
    $mime_boundary = "{$semi_rand}";
    
    // 添加附件到 headers
    $headers .= "\nMIME-Version: 1.0\nContent-Type: multipart/mixed;\t boundary=\"{$mime_boundary}\"\n\n";
    // 在上面的超文本中增加一个多重分界线(multipart boundary),下面这句标记邮件体开始
    $message = "This is a multi-part message in MIME format.\n\n"
     . "--{$mime_boundary}\nContent-Type: text/plain; charset=\"GBK\"\nContent-Transfer-Encoding: base64\n\n" .$message . "\n\n";
    
    // Base64 编码附件后添加到邮件中
    $data = chunk_split(base64_encode($data));
    $message .= "--{$mime_boundary}\nContent-Type: {$fileatt_type};\t name=\"{$fileatt_name}\"\n"
     //. "Content-Disposition: attachment;\n\t filename=\"{$fileatt_name}\"\n"
     . "Content-Transfer-Encoding: base64\n\n" .$data . "\n\n"
     . "--{$mime_boundary}--\n";
    }
echo 'To: ' . $to . "\r\n" . $headers . $message;
// 发邮件
// $ok = @mail ($to, $subject, $message, $headers);
$ok = '0';
if ($ok){
     echo "<p>已发送! Yay PHP!</p>";
    }else{
     echo "<p>发送失败!</p>";
    }
function mailform(){
    $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=GBK" /><title>匿名邮件</title></head>'
     . '<body><center><h2>匿名发邮件</h2><table with=80%>'
     . '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" enctype="multipart/form-data">'
     . '<tr><td>收件者: </td><td><input type="text" name="to" value="" style="width:521px" /></td></tr>'
     . '<tr><td>发送者: </td><td><input type="text" name="from" value="" style="width:521px" /></td></tr>'
     . '<tr><td>标题:   </td><td><input type="text" name="subject" value="" style="width:521px"/></td></tr>'
     . '<tr><td>内容:   </td><td><textarea name="message" style="width:521px; height:300px"></textarea></td></tr>'
     . '<tr><td>附件:   </td><td><input type="file" name="fileatt" size="60" style="width:460px"/></td></tr></table>'
     . '<br /><input type="submit" value=" 发 送 " style="width:80px"/></center>'
     . '</form></body></html>';
    return $html;
    }
?>