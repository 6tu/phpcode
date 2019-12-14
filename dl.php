<?php
# Open README file for descriptions and help.
# 
# $config = array(
#     'passive_mode'   => true,
#     'transfer_mode'  => 'FTP_ASCII',
#     'reattempts'     => 1,
#     'log_path'       => './log/log.log',
#     'verbose'        => false,
#     'create_mask'    => 0777,
# 	);
# @$ftp = new ftp($config);
# 
# ZZT4_yourshell:ha%oLWAZSby2Fy[x@ftp-sg1.ctl.io

$ftpHost = "ftp-sg1.ctl.io";    // 服务器地址ַ
$ftpPort = "21";                // 服务器端口
$ftp_user='ZZT4_yourshell';     // 账号
$ftp_pass='ha%oLWAZSby2Fy[x';   // 密码
$ftpDir  = "";                  // 远程目录
$serverTmp = "./tmp";           // 本地临时目录

global $ftpHost;
global $ftpPort; 
global $ftp_user;
global $ftp_pass;
global $ftpDir;  
global $serverTmp;
if (isset($_GET["dl"])){
$file      = quotesUnescape($_GET["dl"]);
$file_name = getFileFromPath($file);
$fp1       = createTempFileName($file_name);
$fp2       = $file;
}elseif(isset($_GET["list"])){
header("Content-type: text/html; charset=utf-8"); 
$dir = $_GET['list'];
echo '<br><center><p>该目录内的文件<br></center><pre>';
$conn = ftp_connect($ftpHost) or die("Could not connect");
ftp_login($conn,$ftp_user,$ftp_pass) OR die("<br>ftp-login failed");
ftp_pasv($conn, true);
$list = ftp_rawlist($conn,$dir,true);
ftp_close($conn);
print_r ($list);
exit(0);
}else{
echo '<pre><br><center><p>通过GET方式获取FTP服务器上的文件 </p><br>';
echo '<p>使用方法： 通过 ?dl= 下载文件，?list= 查看目录内的文件</p><br>';
echo '根目录一般为 . 或者是  /</center>';
exit();
}

require 'ftp.php';
@$ftp = new ftp();
$ftp->conn($ftpHost, $ftp_user, $ftp_pass);
$ftp->get($fp1, $fp2);

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . quotesEscape($file_name, "d") . "\""); // quotes required for spacing in filename
header("Content-Length: " . filesize($fp1));

flush();

$fp = @fopen($fp1, "r");
while (!feof($fp)) {
    echo @fread($fp, 65536);
    @flush();
}
@fclose($fp);
// Delete tmp file
unlink($fp1);




function quotesUnescape($str)
{
    $str = str_replace("\'", "'", $str);
    $str = str_replace('\"', '"', $str);
    
    return $str;
}
function quotesEscape($str, $type)
{
    
    if ($type == "s" || $type == "")
        $str = str_replace("'", "\'", $str);
    if ($type == "d" || $type == "")
        $str = str_replace('"', '\"', $str);
    
    return $str;
}
function getFileFromPath($str)
{
    
    $str = preg_replace("/^(.)+\//", "", $str);
    $str = preg_replace("/^~/", "", $str);
    
    return $str;
}
function createTempFileName($file_name)
{
    global $serverTmp;
    
    //return $serverTmp . "/" . $file_name . "." . uniqid("mftp.", true);
    
    // Attempt to get a $serverTmp var if not set by user
    if ($serverTmp == "")
        $serverTmp = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
    
    return tempnam($serverTmp, $file_name);
}



?>