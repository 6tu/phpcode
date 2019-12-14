<?php
error_reporting(0);


// 加入下载

// 获取网址
$site = get_vpn('http://www.vpngate.net/cn/sites.aspx');
$str = explode('<p>&nbsp;</p>',$site,3);
$url = explode('</li>',$str[1],3);
preg_match_all('/<a href=([^>]+)>([^<]+)</',$url[1],$rs);
$url0 = $rs[2][0];
echo '文件源  '.$url0;

$url = 'http://www.vpngate.net/cn/';
$vpn = get_vpn($url);

//$vpn = file_get_contents('d:\x.htm');
// 截取其中的一部分
$str = explode('<b>国家 / 地区</b>',$vpn,3);
$body0 = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /><title>VPN Gate 公共 VPN 中继服务器列表</title></head><body>';
$body1 = "<center><p><strong>公共 VPN 中继服务器<br /></strong></p>\r\n L2TP/IPsec参数: 用户名: 'vpn', 密码: 'vpn'。<br />";
$body2 = "<table border='1' id='vg_hosts_table_id' cellspacing='0' cellpadding='4'>\r\n";
$body = "<tr>\r\n<td><b>国家 / 地区</b>".$str[1].'</table>';

// 删除截取数据的指定列
$body = explode("<tr>\r\n",$body);
$n = count($body);
$str = '';
for($i = 0 ; $i < $n ; $i ++){
$str0 = explode('</td>',$body[$i]);
$str1 = $str0['0'].'</td>'.$str0['1'].'</td>'.$str0['4'].'</td>'.$str0['6'].'</td>'.$str0['7'].'</td>'."\r\n";
$str1 = str_replace(array('<p>','</p>',),array('','',),$str1);
$str .= "<tr>".$str1;
}

$str = preg_replace( "@<img(.*?)>@is", "", $str ); 
$str = preg_replace( "@<span(.*?)>@is", "", $str ); 
$del=array("/class=.+?['|\"]/i");
$c2 = preg_replace($del,"",$str);
$str = str_replace('" >', '">', $c2);

$del=array("/style=.+?['|\"]/i");
$c2 = preg_replace($del,"",$str);
$str = str_replace('" >', '">', $c2);
$str = str_replace(array(' >','</span>',),array( '>','',), $str);
$str = str_replace(array("<a href='howto_softether.aspx'><br><b>SSL-VPN<BR>连接指南</b></a>","<a href='howto_sstp.aspx'><br><b>MS-SSTP<BR>连接指南</b></a>",),array('','',),$str);
$str = str_replace(array('<br>Windows, Mac,<br>iPhone, Android','<br>Windows Vista,<BR>7, 8, RT<br>无需 VPN 客户端','<br>Windows<BR>(合适的)',),array('','','',), $str);

$str = str_replace(array('</td></td></td></td></td>','<tr><td></table>','<BR>',"<tr>\r\n<tr>",),array('','</table>','<br>','<tr>',),$str);
$str = preg_replace( "@\((.*?)\)@is", "", $str ); 
$str = str_replace(array('<td ><br>',"</td>\r\n",),array('<td>','</td>',),$str);
$str = str_replace(array('<tr>','<br><b>OpenVPN<br>配置文件</b>',),array("\r\n\r\n<tr>",'<b>配置文件</b>',),$str);

//echo $body0.$body1.$body2.$str;
$c = $body0.$body1.$body2.$str;
$c = str_replace("<a href='","<a href='".$url0,$c);
echo $c;

function get_vpn($url){
     $ch = curl_init($url);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     $vpn = curl_exec($ch);
//     preg_match('/\[(.*)\]/', $a, $ip);
//     return @$ip[1];
     return $vpn;

     }



?>