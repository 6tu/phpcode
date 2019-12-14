<?php
echo memory_get_usage(), '<br />'; // 313864

$tmp = str_repeat('http://blog.huachen.me/', 4000);

echo memory_get_usage(), '<br />'; // 406048

unset($tmp);

echo memory_get_usage(); // 313952
?>
<?php printf(' memory usage: %01.2f MB', memory_get_usage()/1024/1024); ?>