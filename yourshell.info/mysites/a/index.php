<?php

if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'],'vnd.wap.wml')!==FALSE)) {
header('Location: ./index2.php');
exit;
}
if (!extension_loaded('mbstring') or !extension_loaded('iconv')){
header('Location: ./index2.php');
exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>IP/����/�绰���ֻ�����/�ʱ�/���֤���� �����ز�ѯ</title>

<style type="text/css">
#msg_win{position:absolute;border:2px solid #6699cc;width:200px;right:0px;font-size:12px;overflow:hidden;}
#msg_win .icos{position:absolute;top:2px;*top:0px;right:2px;z-index:9;}
.icos a{float:left;color:#FFFFFF;text-align:center;font-weight:bold;width:14px;height:22px;line-height:22px;padding:1px;text-decoration:none;font-family:webdings;}
.icos a:hover{color:#FFCC00;}
#msg_title{background:#6699cc;color:#FFFFFF;height:20px;line-height:20px;text-indent:5px;font-weight: bold;}
#msg_content{margin:5px;margin-right:0;width:200px;height:350px;overflow:hidden;}
</style>


<script type="text/javascript" src="./js/ajax.js"></script>
</head>
<body>
<center><br>IP/����/�绰���ֻ�����/�ʱ�/���֤���� �����ز�ѯ<br><br>
<div>
<input name="m" size="36" maxlength="100" id="m">
<input type="submit" value="�� ѯ" id="sub"  onClick="sendPostRequest()">

</div><br><br>
<div id="status"></div>
</center>







<p style="height:0px;"></p>
<div id="msg_win" style="display:block;top:20px;visibility:visible;opacity:1;">

<div class="icos"><a id="msg_min" title="��С��" href="javascript:void 0">_</a><a id="msg_close" title="�ر�" href="javascript:void 0">��</a></div>
<div id="msg_title">ʹ�ù���</div>

<div id="msg_content">
 <br> <center> <b>�� ӭ �� ��</b></center><br>
ϣ�����ҵ�׼ȷ����<br><br>
<li>֧��IP����������׼ȷ��ѯ</li> 
<li>֧�����֤��ѯ��15λ��18λ���������һλ</li>
<li>֧���ֻ���������ѯ��7λ��12λ����</li>
<li>֧���ʱ��ѯ��6λ����</li>
<li>��ʱ�����õ�����ѯ����</li>
<li>ʹ����ajax������������ʱ��û��JSҳ�� <a href="index2.php" />index2</a></li>
<li>�ֻ������� <a href="wap.wml" />test</a></li>
<li>�������ݾ����Ի��������������⣬����ϵ����<a href="message.php" />���Ա�</a></li>

<br><br><li> �鿴 <a href="./ua.php" /><b>USER AGENT </b></a></li>
<br><br> 
20110216
</div>
</div>
<script type="text/javascript" src="./js/help.js"></script>


</body>
</html>






