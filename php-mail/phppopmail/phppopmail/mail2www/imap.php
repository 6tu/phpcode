<?php
header("Content-type: text/html; charset=GBK");

$mbox = imap_open("{imap.gmail.com:993/imap/ssl}INBOX", "yourshell.info@gmail.com", "qq0000000") or die("can't connect: " . imap_last_error());
$check_array = imap_check($mbox);
$total = $check_array -> Nmsgs;

// ȡ����Ҫ����ֵ
for($i = $total;$i >= 1;$i--){
    $msg_structure = imap_fetchstructure($mbox, $i);
    $foo = imap_header($mbox, $i);
    // print_r($foo);
    $subject = $foo -> Subject;
    $subject_bak = $subject;
    $from_array = $foo -> from;
    $fr_array = $from_array[0];
    $personal = @$fr_array -> personal;
    $mailbox = $fr_array -> mailbox;
    $host = $fr_array -> host;
    $from_address = $mailbox . "@" . $host;
    $maildate = $foo -> MailDate;
    $udate = $foo -> udate;
    $unseen = $foo -> Unseen; //echo $unseen;
    
    
    $sendbody = "��лʹ�� mail2www �ʼ�ϵͳ���������ʹ�����������⼰���飬�뷢�ʼ��� mail2www@gmail.com\r\n<br>";
    $sendbody .= "ʹ�ð�����\r\n<br>";
    $sendbody .= "�����к��� 'get'\r\n<br>";
    $sendbody .= "�ʼ������к��� 'local /path/file'\r\n<br>";
    $sendbody .= "�����ȡ�ñ��������е��ļ����ʼ������к��� 'local /path/file'\r\n<br>";
    $sendbody .= "�����ȡ�������������е��ļ����ʼ������к��� 'www url'\r\n<br>";
    $sendbody .= "�����к��� 'help' ��ȡ�ñ����������ļ��б�����\r\n<br>";
    
    $from = 'mail23w@gmail.com';
    // $subject = 'test';
    $to = $from_address;
    $headers = "From: $from";
     $mime_boundary = md5(time());
     $headers .= "\nMIME-Version: 1.0\n" .
     "Content-Type: multipart/mixed;" .
     "boundary=" . $mime_boundary . "\n\n";
    $message =
     "--{$mime_boundary}\n" .
     "Content-Type: text/plain; charset=\"GBK\"\n" .
     "Content-Transfer-Encoding: 7bit\r\n\r\n" .
     $sendbody . "\n\n";
    
    // ������Ѷ����ʱ�ż���������
    // δ�������Ѷ����

    $nowtime = date("d-m-Y H:i:s");
    $lastdate = strtotime($nowtime)-600; //10����תΪ��

    /**
     * if ($lastdate>$udate){
     * continue;
     * }
     * 
     * if ($unseen==" ") {
     * continue;
     * }
     */
    
    $status = imap_setflag_full($mbox, $i, "\\Seen"); //"\\Seen \\Flagged"
    // echo gettype($status) . "\n";
    // echo $status . "\n";
    // imap_delete($mbox, $i);
    // ת������
    // ����
    if (strstr($subject, '?B?')){
        $sub_b = '';
        $n = substr_count($subject, '?B?');
        for($b = 0;$b < $n;$b++){
            $c = explode('?=', $subject) ;
            $cc = $c[$b];
            $c2 = explode('?B?', $cc);
            $sub = base64_decode($c2[1]);
            $sub_b .= $sub;
            }
        $subject = $sub_b;
        $subject = iconv("gbk", "utf-8//IGNORE", $subject);
        }
    
    // UTF-8
    if (strstr($subject, '=?UTF-8?')){
        $n = substr_count($subject, '=?UTF-8?');
        $sub_q = '';
        $subject = str_replace('=?UTF-8?Q?', '', $subject);
        $subject = str_replace('?=', '', $subject);
        $subject = str_replace('=', '%', $subject);
        $sub = urldecode($subject);
        $sub_q .= $sub;
        $subject = $sub_q;
        }
    
    // �жϱ��⣬�ʼ�����Ϊ��л�ͽ��鷢�͵�ר�����䡣
    // ��ȡ���ݣ�����������ļ����������Ϊ�ջ���û���������Ĭ���ļ��������ӱ���һ���������¼email��ַ�� mail.txt ��
    if (!strstr($subject, 'get')){
        $subject="ʹ�ð���";
        $message .= "--{$mime_boundary}--\n\n";
        @mail($to, $subject, $message, $headers);

         continue;
         }
    
    
    if(strstr($subject, 'get')){
        
        $body = imap_body($mbox, $i);
        
        // echo $body."\r\n<br>\r\n<br>";
        // ��������
        if(strstr($body, 'Content-Type: multipart')){
            $ccc = explode('Content-Disposition: attachment', $body) ;
            $body = $ccc[0];
            }
        if(strstr($body, 'Content-Transfer-Encoding: quoted-printable')){
            $body = @imap_qprint($body);
            }elseif(strstr($body, 'Content-Transfer-Encoding: base64')){
            $sub_bb = '';
            $n = substr_count($body, 'base64');
            for($bb = 0;$bb < $n;$bb++){
                $c = explode("\r\n\r\n", $body) ;
                $cc = $c[$bb + 1];
                $c2 = explode("\r\n--", $cc);
                $sub = base64_decode($c2[0]);
                $sub_bb .= $sub;
                }
            $body = $sub_bb;
            $body = iconv("gbk", "utf-8//IGNORE", $body);
            }elseif(strstr($subject_bak, '?B?') and !strstr($body, 'Content-Transfer-Encoding')){
            $body = base64_decode($body);
            $body = iconv("gbk", "utf-8//IGNORE", $body);
            }else{
            $body = $body;
            }
        $body = str_replace('get <a', 'getbad', $body);

        // �������ݣ�ȡ���ļ������ļ�����
        if(!strstr($body, "\r\nget ")){

       $subject="û��ָ����ʼ�";
        $message .= "--{$mime_boundary}--\n\n";
        @mail($to, $subject, $message, $headers);
             continue;
            }
        
        $n1 = substr_count($body, "\r\nget ");
        if($n1 > 10){
            $n1 = 10;
            }
        
        $ln = substr_count($body, "\r\n");
        $body = explode("\r\n", $body);
        $url1 = '';
        for($bc = 0;$bc < $ln;$bc++){
            if(strstr($body[$bc], 'get ')){
                $url0 = $body[$bc];
                $url1 .= $url0;
                $url0 = str_replace("get ", "", $url0); //ȡ�����е���ַ
                $url = parse_url(trim($url0));
                $file_name = substr(@$url['path'], strrpos(@$url['path'], "/") + 1);
                 if($file_name == ""){
                     $file_name = "index.html";
                     }
                 $url["path"] = str_replace('/', '.', @$url["path"]);
                 $file_name = sha1($url["host"] . @$url["path"]) . "_" . $file_name;
                 $new_file_name = $file_name . '.zip';
                
                 // ������ݣ������ļ�
                 $data = @file_get_contents(trim($url0));
                 file_put_contents("$file_name", $data);
		 compress ($file_name, $new_file_name);
                
                
                 $new_file_type = 'application/x-zip-compressed';
                 $data = file_get_contents($new_file_name);
                 $data = chunk_split(base64_encode($data));
                 $message .= "--{$mime_boundary}\n" .
                 "Content-Type: {$new_file_type};\n" .
                 " name=\"{$new_file_name}\"\n" .
                 // "Content-Disposition: attachment;\n" .
                // " filename=\"{$fileatt_name}\"\n" .
                "Content-Transfer-Encoding: base64\n\n" .
                 $data . "\n\n" ;
                 @unlink($file_name);
                 // @unlink($new_file_name);
            }
            $n2 = substr_count($url1, "get ");
            if($n2 == $n1){
                break;
                }
            }
        $message .= "--{$mime_boundary}--\n\n";
        // echo $headers.$message;
        // �����ʼ�
        $ok = @mail($to, $subject, $message, $headers);
        if ($ok){
             echo $to . "<p>�ʼ����ͳɹ�!</p>";
             }else{
             echo "<p>�ʼ�����ʧ��!</p>";
             }
        
        }
    
    
    
    
    
    
    /**
     * echo " \r\n<br>"; 
     * echo "No ".$i." >>>>>>>>>>>>> ".$subject." \r\n<br>"; 
     * echo "Sender: $from_address <br>"; 
     * //".$msg_structure->bytes.$personal \r\n  echo $from_address."\r\n<br>";
     * //echo  $body;
     */
    }
imap_close($mbox);


function compress ($txtname, $zipname){
    if(false !== function_exists("zip_open")){
         $zip = new ZipArchive();
         if ($zip -> open("$zipname", ZIPARCHIVE :: CREATE) !== TRUE){
             exit("cannot open <$zipname>\n");
             }
         $zip -> addFile($txtname);
         $zip -> close();
        }else{
         include('archive.inc.php');
         $test = new zip_file($zipname);
         $test -> add_files(array($txtname));
         $test -> create_archive();
        
        }
     }
?> 

