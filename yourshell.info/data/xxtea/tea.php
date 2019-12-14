<?php
header("Content-Type: text/plain; charset=utf-8");
include('xxtea.php');
$str = 'test此时很高兴';
$str = file_get_contents('bmjf.htm');
$key = '5';
$enc = xxtea_encrypt($str, $key);
$enc = addJsSlashes($enc, $flag = true);
function addJsSlashes($str, $flag) {
    if ($flag) {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134\177..\377");
    }else {
        $str = addcslashes($str, "\0..\006\010..\012\014..\037\042\047\134");
    }
    return str_replace(array(chr(7), chr(11)), array('\007', '\013'), $str);
}

?>
<html><head><title>测试解密</title>
<script type="text/javascript" src="./js/utf.js"></script> 
<script type="text/javascript" src="./js/xxtea.js"></script> 
<script language="javascript" >
var enc = "<?php echo $enc?>";
var key = '5';
var m_xxtea    = XXTEA;
str = m_xxtea.decrypt(enc, key).toUTF16();
document.write(str);
</script>
</head><body></body></html>