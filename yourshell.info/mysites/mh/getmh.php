
<html> 
<head> 
<title>PUT FILE NAME</title> 
</head> 
<body> 

<form action="" method="GET" /> 
<b>PUT FILE NAME : <input type="text" name="name" value="2010-7-1.html" />  <input type="submit" value="Send" /></b> 
</form> 
</body> 
</html> 

<?php
//header("Content-type: text/html; charset=GBK"); 
$to='zhongxiaolee@gmail.com';
//echo date_default_timezone_get (); 
date_default_timezone_set ('America/New_York'); 
if(isset($_GET['name']) and !strstr($_GET['name'], '/')){
$txt = $_GET['name'];
$time2 = explode('.', $txt) ;
$time1 = str_replace('-', '/',$time2[0]) ;
$time1 = '/'.$time1.'/';
$zip=$time2[0].'.zip';
$mhurl='http://minghui.org/mh/articles'.$time1.$txt;
}elseif(isset($_GET['name']) and strstr($_GET['name'], 'http://')){
$url0 = $_GET['name'];
$url = parse_url(trim($url0));
$txt = substr(@$url['path'], strrpos(@$url['path'], "/") + 1);
if($txt == ""){
$txt = "index.html";
}
$zip=$txt.'.zip';
$mhurl = $url0;
}else{
$time1=date("/Y/n/j/",time());
$time2=date("Y-n-j.",time());
$txt=$time2.'txt';
$zip=$time2.'zip';
$mhurl='http://minghui.org/mh/articles'.$time1.$txt;
}
$p7m_txt='p7m_'.$txt;

//echo $mhurl;

//==========================文件名定义结束==========================//
//==========================由于不能加密ZIP文件，所以加密TXT后再压缩==========================//

//$mhnews = @GetPage($mhurl);
$mhnews = @file_get_contents($mhurl);

//echo $mhnews;
file_put_contents($txt, $mhnews); 
pkcs7_encrypt($txt);
compress ($p7m_txt, $zip);
compress ($txt, 'data'.$zip);
@unlink($txt);
@unlink($p7m_txt);

//==========================发送文件==========================//
$ziptype = 'application/x-zip-compressed';
$zipdata = file_get_contents("mhdata/data".$zip);
$zipdata = chunk_split(base64_encode($zipdata));
$mhdata = '';
$mhdata = "Content-Type: {$ziptype};name=\"{$zip}\"\n" .
	   "Content-Transfer-Encoding: base64\n\n" .
          $zipdata . "\n\n" ;

//smtp_mail($to,$txt,$mhdata);

//====================================================//
//加密函数h
function pkcs7_encrypt($txt){
$headers = array("To" => "info@yourshell.info",
	   "From" => "webmaster <postmaster@yourshell.info>", 
          "Reply-to" => "support@example.com",
          "Subject" => "Test",
	   "Date" => date("r"),
	   "X-Mailer" => "By news (PHP/".phpversion().")");
//$cwd = getcwd();
$cwd = $_SERVER['DOCUMENT_ROOT'];

$cert = file_get_contents("mh.cer");
$source = './'.$txt;
$enc= './p7m_'.$txt;
openssl_pkcs7_encrypt($source, $enc, $cert,$headers);  //   $headers0 替换 null
return file_get_contents($enc); 

//unlink($enc);
}

// GetPage函数，使用CURL取得URL内容。
function GetPage ($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $tmp = curl_exec ($ch);
     curl_close ($ch);
     // if (strlen($tmp) < 420){
    if(ereg('pache', $tmp)){
         return false;
         }else{
         $tmp = preg_replace('/(?s)<meta http-equiv="Expires"[^>]*>/i', '', $tmp);
         return $tmp;
         }
     }
//压缩函数
function compress ($txtname, $zipname){
/*
    if(false !== function_exists("zip_open")){
         $zip = new ZipArchive();
         if ($zip -> open("mhdata/".$zipname, ZIPARCHIVE :: CREATE) !== TRUE){
             exit("cannot open <$zipname>\n");
             }
         $zip -> addFile($txtname);
         $zip -> close();
        }else{
*/		
         //include('zip.class.php');
         $test = new zip_file("mhdata/".$zipname);
         $test -> add_files(array($txtname));
         $test -> create_archive();
        
        //}
     }
//发送邮件函数
function smtp_mail($to,$subject,$body){
   
$host = "smtp.yeah.net";
$port ='25';
$username = "mail2www";
$passwd = "qq0000000";
$from = "<mail2www@yeah.net>";
$to ='<'.$to.'>';
/* 
$host = "ssl://smtp.gmail.com";
$port='465';
$username = "3w2mail";
$passwd = "qq0000000";
$from = "<3w2mail@googlemail.com>";
$to =$to;
*/


// 定义 header 部分
$mime_boundary = md5(time());
$header = "MIME-Version:1.0\r\n";
$header .= "Content-Type: multipart/mixed;" ."boundary=" . $mime_boundary . "\r\n";
$header .= "To: ".$to."\r\n";
$header .= "From: sendnews $from \r\n";
$header .= "Subject: ".$subject."\r\n";
$header .= "Date: ".date("r")."\r\n";
$header .= "X-Mailer:By sendnews (PHP/".phpversion().")\r\n";

// 定义 body 部分
if(strstr($body,'Content-Type: application/x-zip-compressed')){
$body=str_replace('Content-Type: application/x-zip-compressed',"--{$mime_boundary}\r\nContent-Type: application/x-zip-compressed",$body);
}

$body ="--{$mime_boundary}\n" .
     "Content-Type: text/html; charset=\"GBK\"\n" .
     "Content-Transfer-Encoding: 7bit\r\n\r\n" .
     $body . "\n\n".
     "--{$mime_boundary}--\r\n" ;

    // socket连接
    $sock = fsockopen($host, $port);
    if ($sock){
         set_socket_blocking($sock, true);
         $info = fgets($sock, 512);

        // 用户认证
        fputs($sock, "HELO sendnews" . "\r\n");
        $info = fgets($sock, 2000);
         fputs($sock, "AUTH LOGIN" . "\r\n");
        $info = fgets($sock, 2000);
         fputs($sock, base64_encode($username) . "\r\n");
        $info = fgets($sock, 2000);
        fputs($sock, base64_encode($passwd) . "\r\n");
        $info = fgets($sock, 2000);

        // 请求发送邮件
        fputs($sock, "MAIL FROM:$from" . "\r\n");
         $info = fgets($sock, 512);
        fputs($sock, "RCPT TO:$to" . "\r\n");
        $info = fgets($sock, 2000);
        fputs($sock, "DATA" . "\r\n");
        $info = fgets($sock, 2000);




        // 请求成功，发送邮件
        if (ereg("^354", $info)){
             // echo "请求与服务器发送邮件数据成功：" .$info. "<br>";
fputs($sock, $header."\r\n".$body);
             fputs($sock, "." . "\r\n");
             $info = fgets($sock, 2000);
            
            
            // 发送结果报告
            if (ereg("^250", $info)){
                 return "OK\r\n<br>";
                 }else{
                 return "发送邮件数据失败：" . $info . "\r\n<br>";
                 }
             }else{
             return "请求与服务器发送邮件数据失败：" . $info . "<br>";
             }
        
        // 邮件动作完成，并断开socket连接
        fputs($sock, "QUIT" . "\r\n");
         $info = fgets($sock, 2000);
        
        fclose($sock);
        }
    }






// 压缩和解压缩的类 archive
/**
* --------------------------------------------------
* | TAR/GZIP/BZIP2/ZIP ARCHIVE CLASSES 2.1
* | By Devin Doucette
* | Copyright (c) 2005 Devin Doucette
* | Email: darksnoopy@shaw.ca
* +--------------------------------------------------
* | Email bugs/suggestions to darksnoopy@shaw.ca
* +--------------------------------------------------
* | This script has been created and released under
* | the GNU GPL and is free to use and redistribute
* | only if this copyright statement is not removed
* +--------------------------------------------------
*/

class archive
{
     function archive($name)
    {
         $this -> options = array (
            'basedir' => ".",
             'name' => $name,
             'prepend' => "",
             'inmemory' => 0,
             'overwrite' => 0,
             'recurse' => 1,
             'storepaths' => 1,
             'followlinks' => 0,
             'level' => 3,
             'method' => 1,
             'sfx' => "",
             'type' => "",
             'comment' => ""
            );
         $this -> files = array ();
         $this -> exclude = array ();
         $this -> storeonly = array ();
         $this -> error = array ();
         }
   
     function set_options($options)
    {
         foreach ($options as $key => $value)
         $this -> options[$key] = $value;
         if (!empty ($this -> options['basedir']))
            {
             $this -> options['basedir'] = str_replace("\\", "/", $this -> options['basedir']);
             $this -> options['basedir'] = preg_replace("/\/+/", "/", $this -> options['basedir']);
             $this -> options['basedir'] = preg_replace("/\/$/", "", $this -> options['basedir']);
             }
         if (!empty ($this -> options['name']))
            {
             $this -> options['name'] = str_replace("\\", "/", $this -> options['name']);
             $this -> options['name'] = preg_replace("/\/+/", "/", $this -> options['name']);
             }
         if (!empty ($this -> options['prepend']))
            {
             $this -> options['prepend'] = str_replace("\\", "/", $this -> options['prepend']);
             $this -> options['prepend'] = preg_replace("/^(\.*\/+)+/", "", $this -> options['prepend']);
             $this -> options['prepend'] = preg_replace("/\/+/", "/", $this -> options['prepend']);
             $this -> options['prepend'] = preg_replace("/\/$/", "", $this -> options['prepend']) . "/";
             }
         }
   
     function create_archive()
    {
         $this -> make_list();
       
         if ($this -> options['inmemory'] == 0)
        {
             $pwd = getcwd();
             chdir($this -> options['basedir']);
             if ($this -> options['overwrite'] == 0 && file_exists($this -> options['name'] . ($this -> options['type'] ==
                       
                         "gzip" || $this -> options['type'] == "bzip" ? ".tmp" : "")))
                {
                 $this -> error[] = "File {$this->options['name']} already exists.";
                 chdir($pwd);
                 return 0;
                 }
            else if ($this -> archive = @fopen($this -> options['name'] . ($this -> options['type'] == "gzip" || $this ->
                       
                        options['type'] == "bzip" ? ".tmp" : ""), "wb+"))
                 chdir($pwd);
             else
                {
                 $this -> error[] = "Could not open {$this->options['name']} for writing.";
                 chdir($pwd);
                 return 0;
                 }
             }
        else
             $this -> archive = "";
       
         switch ($this -> options['type'])
        {
         case "zip":
             if (!$this -> create_zip())
                {
                 $this -> error[] = "Could not create zip file.";
                 return 0;
                 }
             break;
         case "bzip":
             if (!$this -> create_tar())
                {
                 $this -> error[] = "Could not create tar file.";
                 return 0;
                 }
             if (!$this -> create_bzip())
                {
                 $this -> error[] = "Could not create bzip2 file.";
                 return 0;
                 }
             break;
         case "gzip":
             if (!$this -> create_tar())
                {
                 $this -> error[] = "Could not create tar file.";
                 return 0;
                 }
             if (!$this -> create_gzip())
                {
                 $this -> error[] = "Could not create gzip file.";
                 return 0;
                 }
             break;
         case "tar":
             if (!$this -> create_tar())
                {
                 $this -> error[] = "Could not create tar file.";
                 return 0;
                 }
             }
       
         if ($this -> options['inmemory'] == 0)
        {
         fclose($this -> archive);
         if ($this -> options['type'] == "gzip" || $this -> options['type'] == "bzip")
             unlink($this -> options['basedir'] . "/" . $this -> options['name'] . ".tmp");
         }
     }

function add_data($data)
{
     if ($this -> options['inmemory'] == 0)
         fwrite($this -> archive, $data);
     else
         $this -> archive .= $data;
     }

function make_list()
{
     if (!empty ($this -> exclude))
         foreach ($this -> files as $key => $value)
         foreach ($this -> exclude as $current)
         if ($value['name'] == $current['name'])
             unset ($this -> files[$key]);
         if (!empty ($this -> storeonly))
             foreach ($this -> files as $key => $value)
             foreach ($this -> storeonly as $current)
             if ($value['name'] == $current['name'])
                 $this -> files[$key]['method'] = 0;
             unset ($this -> exclude, $this -> storeonly);
             }
       
         function add_files($list)
        {
             $temp = $this -> list_files($list);
             foreach ($temp as $current)
             $this -> files[] = $current;
             }
       
         function exclude_files($list)
        {
             $temp = $this -> list_files($list);
             foreach ($temp as $current)
             $this -> exclude[] = $current;
             }
       
         function store_files($list)
        {
             $temp = $this -> list_files($list);
             foreach ($temp as $current)
             $this -> storeonly[] = $current;
             }
       
         function list_files($list)
        {
             if (!is_array ($list))
                {
                 $temp = $list;
                 $list = array ($temp);
                 unset ($temp);
                 }
           
             $files = array ();
           
             $pwd = getcwd();
             chdir($this -> options['basedir']);
             foreach ($list as $current)
            {
                 $current = str_replace("\\", "/", $current);
                 $current = preg_replace("/\/+/", "/", $current);
                 $current = preg_replace("/\/$/", "", $current);
                 if (strstr($current, "*"))
                    {
                     $regex = preg_replace("/([\\\^\$\.\[\]\|\(\)\?\+\{\}\/])/", "\\\\\\1", $current);
                     $regex = str_replace("*", ".*", $regex);
                     $dir = strstr($current, "/") ? substr($current, 0, strrpos($current, "/")) : ".";
                     $temp = $this -> parse_dir($dir);
                     foreach ($temp as $current2)
                     if (preg_match("/^{$regex}$/i", $current2['name']))
                         $files[] = $current2;
                     unset ($regex, $dir, $temp, $current);
                     }
                else if (@is_dir($current))
                    {
                     echo "dir";
                     $temp = $this -> parse_dir($current);
                     foreach ($temp as $file)
                     $files[] = $file;
                     unset ($temp, $file);
                     }
                else if (@file_exists($current))
                     $files[] = array ('name' => $current, 'name2' => $this -> options['prepend'] .
                         preg_replace("/(\.+\/+)+/", "", ($this -> options['storepaths'] == 0 && strstr($current, "/")) ?
                             substr($current, strrpos($current, "/") + 1) : $current),
                         'type' => @is_link($current) && $this -> options['followlinks'] == 0 ? 2 : 0,
                         'ext' => substr($current, strrpos($current, ".")), 'stat' => stat($current));
                 else{
                     echo "other error ";
                     }
                 }
           
             chdir($pwd);
           
             unset ($current, $pwd);
           
             usort($files, array ("archive", "sort_files"));
             // prt($files); //die;
            return $files;
           
             }
       
         function parse_dir($dirname)
        {
             if ($this -> options['storepaths'] == 1 && !preg_match("/^(\.+\/*)+$/", $dirname))
                 $files = array (array ('name' => $dirname, 'name2' => $this -> options['prepend'] .
                         preg_replace("/(\.+\/+)+/", "", ($this -> options['storepaths'] == 0 && strstr($dirname, "/")) ?
                             substr($dirname, strrpos($dirname, "/") + 1) : $dirname), 'type' => 5, 'stat' => stat
                       
                         ($dirname)));
             else
                 $files = array ();
             $dir = @opendir($dirname);
           
             while ($file = @readdir($dir))
            {
                 $fullname = $dirname . "/" . $file;
                 if ($file == "." || $file == "..")
                     continue;
                 else if (@is_dir($fullname))
                    {
                     if (empty ($this -> options['recurse']))
                         continue;
                     $temp = $this -> parse_dir($fullname);
                     foreach ($temp as $file2)
                     $files[] = $file2;
                     }
                else if (@file_exists($fullname))
                     $files[] = array ('name' => $fullname, 'name2' => $this -> options['prepend'] .
                         preg_replace("/(\.+\/+)+/", "", ($this -> options['storepaths'] == 0 && strstr($fullname, "/")) ?
                             substr($fullname, strrpos($fullname, "/") + 1) : $fullname),
                         'type' => @is_link($fullname) && $this -> options['followlinks'] == 0 ? 2 : 0,
                         'ext' => substr($file, strrpos($file, ".")), 'stat' => stat($fullname));
                 }
           
             @closedir($dir);
           
             return $files;
             }
       
         function sort_files($a, $b)
        {
             if ($a['type'] != $b['type'])
                 if ($a['type'] == 5 || $b['type'] == 2)
                     return -1;
                 else if ($a['type'] == 2 || $b['type'] == 5)
                     return 1;
                 else if ($a['type'] == 5)
                     return strcmp(strtolower($a['name']), strtolower($b['name']));
                 else if ($a['ext'] != $b['ext'])
                     return strcmp($a['ext'], $b['ext']);
                 else if ($a['stat'][7] != $b['stat'][7])
                     return $a['stat'][7] > $b['stat'][7] ? -1 : 1;
                 else
                     return strcmp(strtolower($a['name']), strtolower($b['name']));
                 return 0;
                 }
           
             function download_file()
            {
                 if ($this -> options['inmemory'] == 0)
                {
                     $this -> error[] = "Can only use download_file() if archive is in memory. Redirect to file otherwise, it

is faster.";
                     return;
                     }
                 switch ($this -> options['type'])
                {
                 case "zip":
                     header("Content-Type: application/zip");
                     break;
                 case "bzip":
                     header("Content-Type: application/x-bzip2");
                     break;
                 case "gzip":
                     header("Content-Type: application/x-gzip");
                     break;
                 case "tar":
                     header("Content-Type: application/x-tar");
                     }
                 $header = "Content-Disposition: attachment; filename=\"";
                 $header .= strstr($this -> options['name'], "/") ? substr($this -> options['name'], strrpos($this ->
                       
                        options['name'], "/") + 1) : $this -> options['name'];
                 $header .= "\"";
                 header($header);
                 header("Content-Length: " . strlen($this -> archive));
                 header("Content-Transfer-Encoding: binary");
                 header("Cache-Control: no-cache, must-revalidate, max-age=60");
                 header("Expires: Sat, 01 Jan 2000 12:00:00 GMT");
                 print($this -> archive);
                 }
             }
       
         class tar_file extends archive
        {
             function tar_file($name)
            {
                 $this -> archive($name);
                 $this -> options['type'] = "tar";
                 }
           
             function create_tar()
            {
                 $pwd = getcwd();
                 chdir($this -> options['basedir']);
               
                 foreach ($this -> files as $current)
                {
                     if ($current['name'] == $this -> options['name'])
                         continue;
                     if (strlen($current['name2']) > 99)
                        {
                         $path = substr($current['name2'], 0, strpos($current['name2'], "/", strlen($current['name2']) - 100)
                           
                             + 1);
                         $current['name2'] = substr($current['name2'], strlen($path));
                         if (strlen($path) > 154 || strlen($current['name2']) > 99)
                            {
                             $this -> error[] = "Could not add {$path}{$current['name2']} to archive because the filename is

too long.";
                             continue;
                             }
                         }
                     $block = pack("a100a8a8a8a12a12a8a1a100a6a2a32a32a8a8a155a12", $current['name2'], sprintf("%07o",
                             $current['stat'][2]), sprintf("%07o", $current['stat'][4]), sprintf("%07o", $current['stat']
                           
                             [5]),
                         sprintf("%011o", $current['type'] == 2 ? 0 : $current['stat'][7]), sprintf("%011o", $current
                           
                             ['stat'][9]),
                         "        ", $current['type'], $current['type'] == 2 ? @readlink($current['name']) : "", "ustar ", "

",
                         "Unknown", "Unknown", "", "", !empty ($path) ? $path : "", "");
                   
                     $checksum = 0;
                     for ($i = 0; $i < 512; $i++)
                     $checksum += ord(substr($block, $i, 1));
                     $checksum = pack("a8", sprintf("%07o", $checksum));
                     $block = substr_replace($block, $checksum, 148, 8);
                   
                     if ($current['type'] == 2 || $current['stat'][7] == 0)
                         $this -> add_data($block);
                     else if ($fp = @fopen($current['name'], "rb"))
                        {
                         $this -> add_data($block);
                         while ($temp = fread($fp, 1048576))
                         $this -> add_data($temp);
                         if ($current['stat'][7] % 512 > 0)
                        {
                             $temp = "";
                             for ($i = 0; $i < 512 - $current['stat'][7] % 512; $i++)
                             $temp .= "\0";
                             $this -> add_data($temp);
                             }
                         fclose($fp);
                         }
                    else
                         $this -> error[] = "Could not open file {$current['name']} for reading. It was not added.";
                     }
               
                 $this -> add_data(pack("a1024", ""));
               
                 chdir($pwd);
               
                 return 1;
                 }
           
             function extract_files()
            {
                 $pwd = getcwd();
                 chdir($this -> options['basedir']);
               
                 if ($fp = $this -> open_archive())
                    {
                     if ($this -> options['inmemory'] == 1)
                         $this -> files = array ();
                   
                     while ($block = fread($fp, 512))
                    {
                         $temp = unpack
                       
                         ("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/a8checksum/a1type/a100symlink/a6magic/a2temp/a32temp/a32temp/a8temp/a8temp/a15

5prefix/a12temp", $block);
                         $file = array (
                            'name' => $temp['prefix'] . $temp['name'],
                             'stat' => array (
                                2 => $temp['mode'],
                                 4 => octdec($temp['uid']),
                                 5 => octdec($temp['gid']),
                                 7 => octdec($temp['size']),
                                 9 => octdec($temp['mtime']),
                                ),
                             'checksum' => octdec($temp['checksum']),
                             'type' => $temp['type'],
                             'magic' => $temp['magic'],
                            );
                         if ($file['checksum'] == 0x00000000)
                             break;
                         else if (substr($file['magic'], 0, 5) != "ustar")
                            {
                             $this -> error[] = "This script does not support extracting this type of tar file.";
                             break;
                             }
                         $block = substr_replace($block, "        ", 148, 8);
                         $checksum = 0;
                         for ($i = 0; $i < 512; $i++)
                         $checksum += ord(substr($block, $i, 1));
                         if ($file['checksum'] != $checksum)
                             $this -> error[] = "Could not extract from {$this->options['name']}, it is corrupt.";
                       
                         if ($this -> options['inmemory'] == 1)
                        {
                             $file['data'] = fread($fp, $file['stat'][7]);
                             fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
                             unset ($file['checksum'], $file['magic']);
                             $this -> files[] = $file;
                             }
                        else if ($file['type'] == 5)
                        {
                             if (!is_dir($file['name']))
                                 mkdir($file['name'], $file['stat'][2]);
                             }
                        else if ($this -> options['overwrite'] == 0 && file_exists($file['name']))
                            {
                             $this -> error[] = "{$file['name']} already exists.";
                             continue;
                             }
                        else if ($file['type'] == 2)
                        {
                             symlink($temp['symlink'], $file['name']);
                             chmod($file['name'], $file['stat'][2]);
                             }
                        else if ($new = @fopen($file['name'], "wb"))
                            {
                             fwrite($new, fread($fp, $file['stat'][7]));
                             fread($fp, (512 - $file['stat'][7] % 512) == 512 ? 0 : (512 - $file['stat'][7] % 512));
                             fclose($new);
                             chmod($file['name'], $file['stat'][2]);
                             }
                        else
                            {
                             $this -> error[] = "Could not open {$file['name']} for writing.";
                             continue;
                             }
                         chown($file['name'], $file['stat'][4]);
                         chgrp($file['name'], $file['stat'][5]);
                         touch($file['name'], $file['stat'][9]);
                         unset ($file);
                         }
                     }
                else
                     $this -> error[] = "Could not open file {$this->options['name']}";
               
                 chdir($pwd);
                 }
           
             function open_archive()
            {
                 return @fopen($this -> options['name'], "rb");
                 }
             }
       
         class gzip_file extends tar_file
        {
             function gzip_file($name)
            {
                 $this -> tar_file($name);
                 $this -> options['type'] = "gzip";
                 }
           
             function create_gzip()
            {
                 if ($this -> options['inmemory'] == 0)
                {
                     $pwd = getcwd();
                     chdir($this -> options['basedir']);
                     if ($fp = gzopen($this -> options['name'], "wb{$this->options['level']}"))
                        {
                         fseek($this -> archive, 0);
                         while ($temp = fread($this -> archive, 1048576))
                         gzwrite($fp, $temp);
                         gzclose($fp);
                         chdir($pwd);
                         }
                    else
                        {
                         $this -> error[] = "Could not open {$this->options['name']} for writing.";
                         chdir($pwd);
                         return 0;
                         }
                     }
                else
                     $this -> archive = gzencode($this -> archive, $this -> options['level']);
               
                 return 1;
                 }
           
             function open_archive()
            {
                 return @gzopen($this -> options['name'], "rb");
                 }
             }
       
         class bzip_file extends tar_file
        {
             function bzip_file($name)
            {
                 $this -> tar_file($name);
                 $this -> options['type'] = "bzip";
                 }
           
             function create_bzip()
            {
                 if ($this -> options['inmemory'] == 0)
                {
                     $pwd = getcwd();
                     chdir($this -> options['basedir']);
                     if ($fp = bzopen($this -> options['name'], "wb"))
                        {
                         fseek($this -> archive, 0);
                         while ($temp = fread($this -> archive, 1048576))
                         bzwrite($fp, $temp);
                         bzclose($fp);
                         chdir($pwd);
                         }
                    else
                        {
                         $this -> error[] = "Could not open {$this->options['name']} for writing.";
                         chdir($pwd);
                         return 0;
                         }
                     }
                else
                     $this -> archive = bzcompress($this -> archive, $this -> options['level']);
               
                 return 1;
                 }
           
             function open_archive()
            {
                 return @bzopen($this -> options['name'], "rb");
                 }
             }
       
         class zip_file extends archive
        {
             function zip_file($name)
            {
                 $this -> archive($name);
                 $this -> options['type'] = "zip";
                 }
           
             function create_zip()
            {
                 $files = 0;
                 $offset = 0;
                 $central = "";
               
                 if (!empty ($this -> options['sfx']))
                     if ($fp = @fopen($this -> options['sfx'], "rb"))
                        {
                         $temp = fread($fp, filesize($this -> options['sfx']));
                         fclose($fp);
                         $this -> add_data($temp);
                         $offset += strlen($temp);
                         unset ($temp);
                         }
                    else
                         $this -> error[] = "Could not open sfx module from {$this->options['sfx']}.";
                   
                     $pwd = getcwd();
                     chdir($this -> options['basedir']);
                   
                     foreach ($this -> files as $current)
                    {
                         if ($current['name'] == $this -> options['name'])
                             continue;
                       
                         $timedate = explode(" ", date("Y n j G i s", $current['stat'][9]));
                         $timedate = ($timedate[0] - 1980 << 25) | ($timedate[1] << 21) | ($timedate[2] << 16) |
                         ($timedate[3] << 11) | ($timedate[4] << 5) | ($timedate[5]);
                       
                         $block = pack("VvvvV", 0x04034b50, 0x000A, 0x0000, (isset($current['method']) || $this -> options
                               
                                 ['method'] == 0) ? 0x0000 : 0x0008, $timedate);
                       
                         if ($current['stat'][7] == 0 && $current['type'] == 5)
                        {
                             $block .= pack("VVVvv", 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1,
                               
                                 0x0000);
                             $block .= $current['name2'] . "/";
                             $this -> add_data($block);
                             $central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this -> options['method'] == 0 ?
                               
                                 0x0000 : 0x000A, 0x0000,
                                 (isset($current['method']) || $this -> options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
                                 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']) + 1, 0x0000, 0x0000, 0x0000,
                               
                                 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
                             $central .= $current['name2'] . "/";
                             $files++;
                             $offset += (31 + strlen($current['name2']));
                             }
                        else if ($current['stat'][7] == 0)
                        {
                             $block .= pack("VVVvv", 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000);
                             $block .= $current['name2'];
                             $this -> add_data($block);
                             $central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this -> options['method'] == 0 ?
                               
                                 0x0000 : 0x000A, 0x0000,
                                 (isset($current['method']) || $this -> options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
                                 0x00000000, 0x00000000, 0x00000000, strlen($current['name2']), 0x0000, 0x0000, 0x0000,
                               
                                 0x0000, $current['type'] == 5 ? 0x00000010 : 0x00000000, $offset);
                             $central .= $current['name2'];
                             $files++;
                             $offset += (30 + strlen($current['name2']));
                             }
                        else if ($fp = @fopen($current['name'], "rb"))
                            {
                             $temp = fread($fp, $current['stat'][7]);
                             fclose($fp);
                             $crc32 = crc32($temp);
                             if (!isset($current['method']) && $this -> options['method'] == 1)
                                {
                                 $temp = gzcompress($temp, $this -> options['level']);
                                 $size = strlen($temp) - 6;
                                 $temp = substr($temp, 2, $size);
                                 }
                            else
                                 $size = strlen($temp);
                             $block .= pack("VVVvv", $crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000);
                             $block .= $current['name2'];
                             $this -> add_data($block);
                             $this -> add_data($temp);
                             unset ($temp);
                             $central .= pack("VvvvvVVVVvvvvvVV", 0x02014b50, 0x0014, $this -> options['method'] == 0 ?
                               
                                 0x0000 : 0x000A, 0x0000,
                                 (isset($current['method']) || $this -> options['method'] == 0) ? 0x0000 : 0x0008, $timedate,
                                 $crc32, $size, $current['stat'][7], strlen($current['name2']), 0x0000, 0x0000, 0x0000,
                               
                                 0x0000, 0x00000000, $offset);
                             $central .= $current['name2'];
                             $files++;
                             $offset += (30 + strlen($current['name2']) + $size);
                             }
                        else
                             $this -> error[] = "Could not open file {$current['name']} for reading. It was not added.";
                         }
                   
                     $this -> add_data($central);
                   
                     $this -> add_data(pack("VvvvvVVv", 0x06054b50, 0x0000, 0x0000, $files, $files, strlen($central),
                           
                             $offset,
                             !empty ($this -> options['comment']) ? strlen($this -> options['comment']) : 0x0000));
                   
                     if (!empty ($this -> options['comment']))
                         $this -> add_data($this -> options['comment']);
                   
                     chdir($pwd);
                   
                     return 1;
                     }
                 }
















?>


