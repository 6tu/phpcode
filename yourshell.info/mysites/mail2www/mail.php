<?php
date_default_timezone_set ('Asia/Shanghai'); 
if(!isset($_POST['to'])){
    echo mailform();
    exit(0);
    }

// ��ȡ POST ����ֵ
$to = $_POST['to'];
$from = $_POST['from'];
$subject = $_POST['subject'];
$message = base64_encode($_POST['message']);

// �����ϴ����ļ�
$fileatt = $_FILES['fileatt']['tmp_name'];
$fileatt_type = $_FILES['fileatt']['type'];
$fileatt_name = $_FILES['fileatt']['name'];

$headers = "From: $from\nTo: $to\nSubject: $subject\nDate: " .date('r'). "\nX-Mailer: http://yourshell.info";

if (is_uploaded_file($fileatt)){
    // �����Ʒ�ʽ��ȡ���� ('rb' = read binary)
    $file = fopen($fileatt, 'rb');
    $data = fread($file, filesize($fileatt));
    fclose($file);
    
    // ����һ���ֽ���(boundary)��
    $semi_rand = md5(time());
    $mime_boundary = "{$semi_rand}";
    
    // ��Ӹ����� headers
    $headers .= "\nMIME-Version: 1.0\nContent-Type: multipart/mixed;\t boundary=\"{$mime_boundary}\"\n\n";
    // ������ĳ��ı�������һ�����طֽ���(multipart boundary),����������ʼ��忪ʼ
    $message = "This is a multi-part message in MIME format.\n\n"
     . "--{$mime_boundary}\nContent-Type: text/plain; charset=\"GBK\"\nContent-Transfer-Encoding: base64\n\n" .$message . "\n\n";
    
    // Base64 ���븽������ӵ��ʼ���
    $data = chunk_split(base64_encode($data));
    $message .= "--{$mime_boundary}\nContent-Type: {$fileatt_type};\t name=\"{$fileatt_name}\"\n"
     //. "Content-Disposition: attachment;\n\t filename=\"{$fileatt_name}\"\n"
     . "Content-Transfer-Encoding: base64\n\n" .$data . "\n\n"
     . "--{$mime_boundary}--\n";
    }
echo 'To: ' . $to . "\r\n" . $headers . $message;
// ���ʼ�
// $ok = @mail ($to, $subject, $message, $headers);
$ok = '0';
if ($ok){
     echo "<p>�ѷ���! Yay PHP!</p>";
    }else{
     echo "<p>����ʧ��!</p>";
    }
function mailform(){
    $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=GBK" /><title>�����ʼ�</title></head>'
     . '<body><center><h2>�������ʼ�</h2><table with=80%>'
     . '<form action="' . $_SERVER['PHP_SELF'] . '" method="POST" enctype="multipart/form-data">'
     . '<tr><td>�ռ���: </td><td><input type="text" name="to" value="" style="width:521px" /></td></tr>'
     . '<tr><td>������: </td><td><input type="text" name="from" value="" style="width:521px" /></td></tr>'
     . '<tr><td>����:   </td><td><input type="text" name="subject" value="" style="width:521px"/></td></tr>'
     . '<tr><td>����:   </td><td><textarea name="message" style="width:521px; height:300px"></textarea></td></tr>'
     . '<tr><td>����:   </td><td><input type="file" name="fileatt" size="60" style="width:460px"/></td></tr></table>'
     . '<br /><input type="submit" value=" �� �� " style="width:80px"/></center>'
     . '</form></body></html>';
    return $html;
    }
?>