<?php
/**
 *
 * 整理归档 MMH-Daily.
 *
 * date()接受年-月-日和月/日/年这种格式
 * 数组以$array_开头,文件名以$fn_开头,网址以$url_开头,
 * 不带文件名的路径以$path_开头,带文件名的路径以$file_开头,
 *
 */

ignore_user_abort();
set_time_limit(0);
error_reporting(1);
date_default_timezone_set('America/New_York');
$current_date = date("Y-n-j", time());

$cwd = getcwd();
$cwd = '/home/daily/mmh';
$path = 'mhdata/';

$path_archives = $path .'archives/';
$path_source = $path .'source/';
if(!is_dir($path_archives)) mkdir($path_archives, 0777, true);
if(!is_dir($path_source)) mkdir($path_source, 0777, true);
if(!file_exists($path_archives .'/加密的档案.txt')) file_put_contents($path_archives .'/加密的档案.txt', 'S/MIME');
if(!file_exists($path_source .'/未加密的档案.txt')) file_put_contents($path_source .'/未加密的档案.txt', 'source');
rm_empty_dir($cwd .'/'. $path);

echo "<br>\r\n<table border=\"0\" align=\"center\" style=\"margin-left:60px;\" width=\"600px\">
    <tr><td height=\"30px\">$current_date</td></tr>
    <tr><td height=\"30px\">空文件夹已删除</td></tr>\r\n";

$array_file = scandir($path);
if(count($array_file) > 8) echo "    <tr><td><b>正在移动的文件</b></td></tr>\r\n";;
$x = 0;
$x = 0;
foreach($array_file as $file){
    if(!is_file($file) == false) continue;
    if($file === '.' or $file === '..') continue;
    # 整理未加密的压缩文件
    if(strtotime(substr($file, 0, -4))){
        if(substr($file, 0, -4) === $current_date) continue;
        echo "    <tr><td>". $file .'</td><td>=> '. $path_source . $file ."</td></tr>\r\n";
        rename($path . $file, $path_source . $file);
        $status_source = ++$x;
    }

    # 整理加密的压缩文件和记录文件
    if(strpos($file, '.log')){
        $date = strstr($file, '-t', true);
        if($date === $current_date) continue;
        $ts = strtotime($date);
        $fn_zip = substr(md5($date), 8, 16) .'.zip';
        echo "    <tr><td>". $date .'</td><td>=> '. $fn_zip . "</td></tr>\r\n";
        $path_date = $path_archives . date('Ym', $ts) . '/';
        if(!file_exists($path_date)) mkdir($path_date, 0755, true);
        rename($path . $fn_zip, $path_date . $fn_zip);
        rename($path . $file, $path_date . $file);
        // unlink($path.$fn_zip);// unlink($path.$file);
        $status_archives = ++$y;
    }
}
//echo $status_source;
echo "    <tr><td height=\"60px\">文件整理完毕</td></tr>\r\n";

$path_current_date = $path_archives . date('Ym', strtotime($current_date));
# 如果只看当前月的文档，则把下面的 $path_archives 更改为 $path_current_date;

$array_list = scandir($path_archives);
foreach($array_list as $list){
    if($list === '.' or $list === '..') continue;
    if(is_file($path_archives .'/'. $list)) continue;
    $list_1 = $path_archives . $list;
    echo "    <tr><td><b>". $list ."</b></td><tr>\r\n";
    
    # 如果是目录
    // if(is_dir($list_1)){
    $array_list_2 = scandir($list_1);
    foreach($array_list_2 as $list_2){
        if($list_2 === '.' or $list_2 === '..') continue;
        if(is_dir($list_2)) continue;
        if(strpos($list_2, '.log')){
            $date = strstr($list_2, '-t', true);
            $ts = strtotime($date);
            $fn_zip = substr(md5($date), 8, 16) .'.zip';
            $size = bytesize(filesize($list_1 .'/'. $fn_zip));
            $link_log = "<a href=\"$list_1/$list_2\">$date</a>";
            $link_zip = "<a href=\"$list_1/$fn_zip\">$fn_zip</a>";
            echo "    <tr><td>". $link_log .'</td><td>'. $link_zip .'</td><td width="20%">['. $size ."]</td><tr>\r\n";
        }
    }
}

echo "</table>";
























/** =========函数区========= */

# 删除所有空目录 @param String $path 目录路径
function rm_empty_dir($path){
    if(is_dir($path) && ($handle = opendir($path))!==false){
        while(($file=readdir($handle))!==false){     // 遍历文件夹
            if($file!='.' && $file!='..'){
                $curfile = $path.'/'.$file;          // 当前目录
                if(is_dir($curfile)){                // 目录
                    rm_empty_dir($curfile);          // 如果是目录则继续遍历
                    if(count(scandir($curfile))==2){ // 目录为空,=2是因为. 和 ..存在
                        rmdir($curfile);             // 删除空目录
                    }
                }
            }
        }
        closedir($handle);
    }
}

# 删除非空目录
function rmdir_recursive($dirPath){
    if(!empty($dirPath) && is_dir($dirPath) ){
        $dirObj= new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS); # 不包含上层目录,否则发生灾 :)
        $files = new RecursiveIteratorIterator($dirObj, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $path) 
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
        rmdir($dirPath);
        return true;
    }
    return false;
}

# 单位转换和字符长度补齐
function bytesize($num){
    $bt = pow(1024, 1);
    $kb = pow(1024, 2);
    $mb = pow(1024, 3);
    $gb = pow(1024, 4);
	if(!is_numeric($num)         ) $size = '值不是数字';
    if($num <  0                 ) $size = '值不能小于 0 ';
    if($num >= 0   and $num < $bt) $size = $num . ' B';
    if($num >= $bt and $num < $kb) $size = floor(($num / $bt) * 100) / 100 . ' KB';
    if($num >= $kb and $num < $mb) $size = floor(($num / $kb) * 100) / 100 . ' MB';
    if($num >= $mb and $num < $gb) $size = floor(($num / $mb) * 100) / 100 . ' GB';
    if($num >= $gb               ) $size = floor(($num / $gb) * 100) / 100 . ' TB';
    return $size;
}

function bytecomplement($str){
    $bv = 50;
    $length = strlen($str);
    if($length < $bv){
        $dv = $bv - $length;
        $space = '';
        for($i = 0; $i < $dv; $i++){
            $space .= ' ';
        }
    }else{
        $space = '  ';
    }
    return $space;
}