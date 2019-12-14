<?php
$PID = getmypid(); 
if(PHP_OS == 'WINNT') exec("tasklist |find \"$PID\"", $ProcessState);
else exec("ps $PID", $ProcessState);
print_r($ProcessState);
?>