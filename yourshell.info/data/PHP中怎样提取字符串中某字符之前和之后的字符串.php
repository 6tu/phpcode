PHP中怎样提取字符串中某字符之前和之后的字符串？

请高手指点：PHP中怎样提取字符串中某字符之前和之后的字符串？
例如$m=abcd_xyz:怎样提取下划线“_”之前和之后的字符串？
其中“abcd”和“xyz”处的字符串个数不是固定的
（不用substr("$m",0,4)）和substr("$m",-3)因为4和-3不固定）
 

substr($m,0,strpos($m,"_"));
substr($m,strpos($m,"_")+1);
大概是这样,可能会有偏差,自己改改就好了
$m_array = explode("_", $m);
echo $m_array[0], " ", $m_array[1];
 
 
<?
$url = file_get_contents("http://www.apache.org/index.html");
$m_array = explode("</a>", $url);
echo crypt($m_array[60])."</a>";//[0]表示首次出现处
?>
