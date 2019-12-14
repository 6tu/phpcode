<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>访客 IP 信息</title>

<script type="text/javascript" src="./jquery-1.11.3.min.js"></script>
<script type="text/javascript">
<!--
$(document).ready(function(){getJSONData();});
function getJSONData(){
    setTimeout("getJSONData()", 60000);
    $.getJSON("https://shideyun.com/ip/ip.php?act=rt&callback=?", displayData_info)
}
function displayData_info(dataJSON){
    $("#c_ip").html(dataJSON.c_ip);
    $("#c_info").html(dataJSON.c_info);
    $("#s_info").html(dataJSON.s_info);
}
-->
</script>
</head>
<body>
<br><center><b>
<?php
$cip = @$_SERVER['REMOTE_ADDR'];
echo $cip .  "\r\n\r\n<br><br>";
?>
<span id="c_ip"></span>
</b></center>
</body>


