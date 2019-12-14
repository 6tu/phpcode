<?php
// 检测函数支持
function isfun($funName)
{
     return (false !== function_exists($funName))?YES:NO;
     }
// 检测PHP设置参数
function getcon($varName)
{
     switch($res = get_cfg_var($varName))
    {
     case 0:
         return NO;
         break;
     case 1:
         return YES;
         break;
     default:
         return $res;
         break;
         }
     }
/**
 * ==============================================
 */
 $funReShow = "none";
 $disFuns = get_cfg_var("disable_functions");
 define("YES", "允许");
 define("NO", "NO");

 if (@$_GET['act'] == "phpinfo"){
     phpinfo();
     exit();
     }
 if(@$_POST['funName']){
     $funReShow = "show";
     $funRe = $_POST['funName'] . "()：" . isfun($_POST['funName']);
     }
if (function_exists('apache_get_modules')){
    $apache_mod = apache_get_modules();
    if(@in_array('mod_rewrite', $apache_mod)){
        $mod_rewrite = '已加载';
        }else{
        $mod_rewrite = '没有加载';
        }
    }else{
    $mod_rewrite = '无法判断';
    }
?>

<html><title>PHP:网络函数探针</title></head><body>
<pre>
        PHP(<?=PHP_VERSION?>) 函数检测       <?=(false !== eregi("phpinfo", $disFuns))?NO:"<a href='$_SERVER[PHP_SELF]?act=phpinfo'>PHPINFO</a>"?>                     
================================================
fsockopen()         Socket支持                   <b><font color=red><?=isfun("fsockopen")?></font></b> 
curl_init()         CURL 扩展                    <b><?=isfun("curl_init")?></b>
file_get_contents() 取得数据为字符串             <b><?=isfun("file_get_contents")?></b> 
pfsockopen()        Socket支持持续               <?=isfun("pfsockopen")?> 
fopen()             allow_url_fopen              <?=getcon("allow_url_fopen")?> 
include()           allow_url_include            <?=getcon("allow_url_include")?> 
file()              取得数据为数组               <?=isfun("file")?>  

mbstring 扩展       编码转换                     <?=isfun("mb_check_encoding")?> 
iconv()             编码转换函数                 <?=isfun("iconv")?> 
Zlib 扩展           压缩数据                     <?=isfun("gzclose")?> 
ZipArchive(php_zip) 压缩数据                     <?=isfun("zip_open")?> 
MCrypt 扩展         加密处理                     <?=isfun("mcrypt_cbc")?> 
BCMath 扩展         高精度数学运算               <?=isfun("bcadd")?> 
openssl 扩展        加密和证书管理               <?=isfun("openssl_verify")?> 
exec()              执行一个操作系统的命令       <?=isfun("exec")?> 
允许动态加载链接库enable_dl                      <?=getcon("enable_dl")?> 
MySQL数据库                                      <?=isfun("mysql_close")?>   

被禁用的函数disable_functions                    <?=("" == ($disFuns))?"无":"\r\n" . str_replace(",", "<br />", $disFuns)?> 
mod_rewrite模块     URL 重写                     <b><?php echo $mod_rewrite;?></b>
================================================
               <b>函数支持状况</b></pre>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
函数名称:<input type="text" name="funName" size="10" /><input type="submit" value="检测" />&nbsp; &nbsp; 
<?php if("show" == $funReShow){
    echo $funRe;
}
?></form><pre>                   <s>&copy;2009</s></pre>          
</body>
</html>



























