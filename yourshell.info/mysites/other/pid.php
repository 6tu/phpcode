<?php
$PID = getmypid(); 
exec("ps $PID", $ProcessState);
print_r($ProcessState);
?>