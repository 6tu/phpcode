<?
include("pop3.inc.php");
$host = "pop.126.com";
$user = "pubnews";
$pass = "qq0000000";
$rec = new pop3($host, 110, 2);
if (!$rec -> open()) die($rec -> err_str);
echo "open >>  $host ,";
if (!$rec -> login($user, $pass)) die($rec -> err_str);
echo "login >>$user \n";
if (!$rec -> stat()) die($rec -> err_str);
echo "����" . $rec -> messages . "���ż�����" . $rec -> size . "�ֽڴ�С<br>";

if ($rec -> messages > 0)
{
    if (!$rec -> listmail()) die($rec -> err_str);
    echo "<br><br>";
    for ($i = 1;$i <= count($rec -> mail_list);$i++)
    {
        echo "�ż�" . $rec -> mail_list[$i]['num'] . "��С��" . $rec -> mail_list[$i]['size'] . "<BR>";
        }
    $rec -> getmail(1);
    echo "�ʼ�ͷ�����ݣ�<br>";
    for ($i = 0;$i < count($rec -> head);$i++)
    echo htmlspecialchars($rec -> head[$i]) . "<br>\n";
    echo "�ʼ����ġ���<BR>";
    for ($i = 0;$i < count($rec -> body);$i++)
    echo htmlspecialchars($rec -> body[$i]) . "<br>\n";
    }
//$rec -> dele(1);
$rec -> close();
?>