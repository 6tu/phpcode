<?php
header("Content-type: text/html; charset=GBK");

$mbox = imap_open("{imap.gmail.com:993/imap/ssl}INBOX", "yourshell.info@gmail.com", "qq0000000") or die("can't connect: " . imap_last_error());
$check_array = imap_check($mbox);
$total = $check_array -> Nmsgs;

// 取出需要变量值
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
    
    
    $sendbody = "感谢使用 mail2www 邮件系统，如果您在使用中遇到问题及建议，请发邮件至 mail2www@gmail.com\r\n<br>";
    $sendbody .= "使用帮助：\r\n<br>";
    $sendbody .= "标题中含有 'get'\r\n<br>";
    $sendbody .= "邮件正文中含有 'local /path/file'\r\n<br>";
    $sendbody .= "如果想取得本服务器中的文件，邮件正文中含有 'local /path/file'\r\n<br>";
    $sendbody .= "如果想取得其它服务器中的文件，邮件正文中含有 'www url'\r\n<br>";
    $sendbody .= "标题中含有 'help' ，取得本服务器上文件列表及帮助\r\n<br>";
    
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
    
    // 如果是已读或过时信件，则跳过
    // 未读的作已读标记

    $nowtime = date("d-m-Y H:i:s");
    $lastdate = strtotime($nowtime)-600; //10分钟转为秒

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
    // 转换编码
    // 中文
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
    
    // 判断标题，邮件正文为感谢和建议发送到专用信箱。
    // 读取内容，根据命令发送文件，如果内容为空或者没有命令，则发送默认文件：帮助加表单和一个软件。记录email地址于 mail.txt 中
    if (!strstr($subject, 'get')){
        $subject="使用帮助";
        $message .= "--{$mime_boundary}--\n\n";
        @mail($to, $subject, $message, $headers);

         continue;
         }
    
    
    if(strstr($subject, 'get')){
        
        $body = imap_body($mbox, $i);
        
        // echo $body."\r\n<br>\r\n<br>";
        // 解码内容
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

        // 分析内容，取得文件名及文件内容
        if(!strstr($body, "\r\nget ")){

       $subject="没有指令的邮件";
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
                $url0 = str_replace("get ", "", $url0); //取得行中的网址
                $url = parse_url(trim($url0));
                $file_name = substr(@$url['path'], strrpos(@$url['path'], "/") + 1);
                 if($file_name == ""){
                     $file_name = "index.html";
                     }
                 $url["path"] = str_replace('/', '.', @$url["path"]);
                 $file_name = sha1($url["host"] . @$url["path"]) . "_" . $file_name;
                 $new_file_name = $file_name . '.zip';
                
                 // 获得数据，生成文件
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
        // 发送邮件
        $ok = @mail($to, $subject, $message, $headers);
        if ($ok){
             echo $to . "<p>邮件发送成功!</p>";
             }else{
             echo "<p>邮件发送失败!</p>";
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

