PHP��������ȡ�ַ�����ĳ�ַ�֮ǰ��֮����ַ�����

�����ָ�㣺PHP��������ȡ�ַ�����ĳ�ַ�֮ǰ��֮����ַ�����
����$m=abcd_xyz:������ȡ�»��ߡ�_��֮ǰ��֮����ַ�����
���С�abcd���͡�xyz�������ַ����������ǹ̶���
������substr("$m",0,4)����substr("$m",-3)��Ϊ4��-3���̶���
 

substr($m,0,strpos($m,"_"));
substr($m,strpos($m,"_")+1);
���������,���ܻ���ƫ��,�Լ��ĸľͺ���
$m_array = explode("_", $m);
echo $m_array[0], " ", $m_array[1];
 
 
<?
$url = file_get_contents("http://www.apache.org/index.html");
$m_array = explode("</a>", $url);
echo crypt($m_array[60])."</a>";//[0]��ʾ�״γ��ִ�
?>
