<?php
// ��⺯��֧��
function isfun($funName)
{
     return (false !== function_exists($funName))?YES:NO;
     }
// ���PHP���ò���
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
 define("YES", "����");
 define("NO", "NO");

 if (@$_GET['act'] == "phpinfo"){
     phpinfo();
     exit();
     }
 if(@$_POST['funName']){
     $funReShow = "show";
     $funRe = $_POST['funName'] . "()��" . isfun($_POST['funName']);
     }
if (function_exists('apache_get_modules')){
    $apache_mod = apache_get_modules();
    if(@in_array('mod_rewrite', $apache_mod)){
        $mod_rewrite = '�Ѽ���';
        }else{
        $mod_rewrite = 'û�м���';
        }
    }else{
    $mod_rewrite = '�޷��ж�';
    }
?>

<html><title>PHP:���纯��̽��</title></head><body>
<pre>
        PHP(<?=PHP_VERSION?>) �������       <?=(false !== eregi("phpinfo", $disFuns))?NO:"<a href='$_SERVER[PHP_SELF]?act=phpinfo'>PHPINFO</a>"?>                     
================================================
fsockopen()         Socket֧��                   <b><font color=red><?=isfun("fsockopen")?></font></b> 
curl_init()         CURL ��չ                    <b><?=isfun("curl_init")?></b>
file_get_contents() ȡ������Ϊ�ַ���             <b><?=isfun("file_get_contents")?></b> 
pfsockopen()        Socket֧�ֳ���               <?=isfun("pfsockopen")?> 
fopen()             allow_url_fopen              <?=getcon("allow_url_fopen")?> 
include()           allow_url_include            <?=getcon("allow_url_include")?> 
file()              ȡ������Ϊ����               <?=isfun("file")?>  

mbstring ��չ       ����ת��                     <?=isfun("mb_check_encoding")?> 
iconv()             ����ת������                 <?=isfun("iconv")?> 
Zlib ��չ           ѹ������                     <?=isfun("gzclose")?> 
ZipArchive(php_zip) ѹ������                     <?=isfun("zip_open")?> 
MCrypt ��չ         ���ܴ���                     <?=isfun("mcrypt_cbc")?> 
BCMath ��չ         �߾�����ѧ����               <?=isfun("bcadd")?> 
openssl ��չ        ���ܺ�֤�����               <?=isfun("openssl_verify")?> 
exec()              ִ��һ������ϵͳ������       <?=isfun("exec")?> 
����̬�������ӿ�enable_dl                      <?=getcon("enable_dl")?> 
MySQL���ݿ�                                      <?=isfun("mysql_close")?>   

�����õĺ���disable_functions                    <?=("" == ($disFuns))?"��":"\r\n" . str_replace(",", "<br />", $disFuns)?> 
mod_rewriteģ��     URL ��д                     <b><?php echo $mod_rewrite;?></b>
================================================
               <b>����֧��״��</b></pre>
<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
��������:<input type="text" name="funName" size="10" /><input type="submit" value="���" />&nbsp; &nbsp; 
<?php if("show" == $funReShow){
    echo $funRe;
}
?></form><pre>                   <s>&copy;2009</s></pre>          
</body>
</html>



























