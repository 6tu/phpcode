<?php
# http://www.helloweba.com/view-blog-253.html
session_start();
$_SESSION["user_name"]="home80";

header("Content-type:text/html;charset=gbk");
date_default_timezone_set("Asia/Shanghai");
//$cookie = tempnam('./', 'cookie');
$cookie = dirname(__FILE__).'/temp/cookie.txt';

if(!isset($_GET['vf'])){

# ��ȡ��֤��

$date = date("D M j Y H:i:s ").' GMT+0800';
$date = str_replace(' ','%20',$date);
$vf='http://c3.ys168.com/sjzx/ys_vf_img.aspx?lx=home80ly&sj='.$date;
$vf = get_cookie($vf,$cookie);
file_put_contents ('./temp/ys_vf.jpg',$vf);


# ��ȡ������ַ	

$url = 'http://home80.ys168.com';
$header=array('Referer: http://zy.ys168.com/zy/b_old/ck/D_yzck.htm');
$res = get_content($url, $cookie, $header);
$iframe = explode('<iframe',$res); 
$lyurl = '';
for($i=0;$i<count($iframe);$i++) 
{
if (strstr($iframe[$i], 'id="frxcx"')){
$lyurl .=$iframe[$i];
break;
}
}
$lyurl=str_replace(' ','',$lyurl);
$lyurl=str_replace('"','',$lyurl);
$lyurl=substr($lyurl, strpos($lyurl, "src=") + 4);
$lyurl=substr($lyurl, 0,strpos($lyurl, "style"));
file_put_contents ('./temp/ly.log',$lyurl);


# ������ͻ��� ���ֶ��ύ����
echo html();

}else{
# ��ȡ���Բ��ύ��������
$yz2=$_GET['bt'];
$yz3=$_GET['nr'];
$yz4=$_GET['vf'];
$data = array(
    'Yz0' => 'lytj',
    'Yz1' => 'n1',
    'Yz2' => $yz2,
    'Yz3' => $yz3,
    'Yz4' => $yz4
	);
$url = file_get_contents ('./temp/ly.log');
$header=array('Referer: http://zy.ys168.com/zy/b_old/ck/D_yzck.htm');
$login = login_post($url, $cookie, $data);

#�Է��ص����ݽ��д���
$login = explode('value="<p>',$login); 
$login = '<p>'.$login[1];
$login = str_replace(array('</p>','</div>"/>','</form>','</body>','</html>'),array("</p>\r\n",'</div>','','',''),$login);
echo '<br>'.$login;

//unlink($cookie);
//unlink('./temp/ly.log');
//unlink('./temp/ys_vf.jpg');
}


function html(){
    $html = '<html><head><meta http-equiv="Content-Type" content="text/html;charset=GBK"/>'
	. '<title>���Ա�</title></head>'
	. '<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />'
	. '<meta name="description" content="���Ա�" />'
	. '<body><br/><br/><center><h2>�� �� ��</h2><table with=80%><br>'
	. '<form action="' . $_SERVER['PHP_SELF'] . '" method="GET" >'
	. '<tr><td width=50 height=40 >���� </td><td><input type="text" name="bt" value="" style="width:600px;height:50px" /></td></tr>' 
	. '<tr><td width=50 height=40 >���� </td><td><textarea name="nr" cols="70" rows="8"></textarea></td></tr>'
	. '<tr><td width=50 height=40 >��֤�� </td><td><img src="ys_vf.jpg" /><input type="text" name="vf" value="" style="width:500px;height:40px" /></td></tr>' 	
	. '<tr><td width=50 height=20 > </td><td><center><input type="submit" value="����" style="width:200px;height:40px" /></center></td></tr>'
	. '</form></center></body></html>';
    return $html;
}

function login_post($url, $cookie, $post){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);	
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function get_content($url, $cookie, $header){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    $result = curl_exec($ch);
    curl_close($ch);
    return$result;
}
function get_cookie($url,$cookie){
$ch = curl_init($url); //��ʼ��
curl_setopt($ch, CURLOPT_HEADER, false); //������header����
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //�����ַ���������ֱ�����
//curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie); //�洢cookies
$res = curl_exec($ch);
curl_close($ch);
return $res;
}
?>