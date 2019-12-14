<?php

$encKey="12";
$str="1234abcASDF/?";
echo 'PHP:'.$str."\r\n<br>";
$ex=encrypt_string($str);
function encrypt_string($input)
{
    global $encKey;
    $line = "";
    for($i = 0; $i < strlen($input); $i++) {
        $line .= chr(ord($input[$i]) + $encKey);//ord()返回字符的 ASCII 值。十进制
    }                                           //chr()从指定的 ASCII 值返回字符。
    return $line;
}
function deccrypt_string($input)
{
    global $encKey;
    $line = "";
    for($i = 0; $i < strlen($input); $i++){
        $line .= chr(ord($input[$i]) - $encKey);
    }
    return $line;
}
?>
JS:
<script>
var e="<? echo $ex; ?>";
var s= decrypt_string(e);
document.write(s);

function decrypt_string(estr){
var key = 12;
var str="";
var n = estr.length;
var i;
for(i=0; i<n; i++) {
dstr =estr.charCodeAt(i) - key;
str+=String.fromCharCode(dstr);
}
return str;
}
</script>













