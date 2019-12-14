<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
<title>Knh 代理服务器Theta- KNH.C </title>
<script language="javascript" type="text/javascript" src="js/denpa.js?<?php echo time();?>"></script>
<script language="javascript" type="text/javascript">
<!--
function clearBox(){
	KN_BFORM.url.value="";
}
function checkAndEncode(){
	if(document.getElementById("check_enc").checked){
		var random = Math.round(Math.random() * 10);
		var url_value = document.getElementById("url").value;
		document.getElementById("url").value = encryptText(document.getElementById("url").value,random);
		document.getElementById("encrypt_key").value = random;
	}else{
		return true;
	}
}
var currentState={
	logo:2
}
function setPicture(){
	if(currentState.logo==1){
		currentState.logo=2;
		document.getElementById("logo").src="logo_new.png";
		document.getElementById("url").style.color="#FFFFFF";
		document.getElementById("url").style.backgroundColor="#6FB8E3";
	}else{
		currentState.logo=1;
		document.getElementById("logo").src="logo.png";
		document.getElementById("url").style.color="#FF0000";
		document.getElementById("url").style.backgroundColor="#FFFFCC";
	}
}
-->
</script>
<style>
.copyright{font: 0.8em Verdana, "Lucida Grande", Arial, Helvetica, sans-serif;}
.options{font-size:0.95em; font-family:"微软雅黑", "Microsoft Yahei", "黑体", "Heiti" , Verdana, "Lucida Grande", Arial, Helvetica, sans-serif; }
.urlbox{background-color:#6FB8E3;color: #FFFFFF;font-family: Arial;font-weight: bold;font-size: 1em;}
</style>
</head>
<body bgcolor="FFFFFF">

<p align="center"><img id="logo" src="logo_new.png" ondblclick="setPicture();" width="320" height="150" title="双击切换到旧图标"></p>
<form method="GET" action="" name="KN_BFORM" onsubmit="return checkAndEncode();">
	<p align="center"><input type="text" id="url" name="url" size="55%" ondblclick="clearBox();" class="urlbox" value="http://"><br>
	<span class="options"><input type="checkbox" name="enc_pre" id="check_enc" value="1" CHECKED>发送地址前本机加密（避免第一次截获）<br>
	<input type="checkbox" name="enp" id="check_pen" value="true">加密页面（躲过内容关键字筛查）<br>
	<input type="checkbox" name="debug" id="dbg_sel" value="true">直接进入调试模式(Debug Mode)</span>&nbsp;
	<br><input type="hidden" name="encrypt_key" id="encrypt_key" value="">
	<input type="submit" value="I'm Feeling Lucky" name="LuckyButton"></p>
</form>
<div align="center">
	<table border="0" width="50%" id="table1" class="copyright">
		<tr>
			<td valign="top" align="center">Copyright <?php echo date('Y'); ?> - Knh Internet Services Limited - CQZ 制作</td>
		</tr>
	</table>
</div>
</body>