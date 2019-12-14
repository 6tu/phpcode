<?php
/**
 * 字符串转数组，字符串乱序
 * 个人练习题
 * 文件编码和函数中的$charset必须一致，否则可能乱码
 * 20110609 http://yourshell.info/
 * 
 * $array = str2array($str, 'utf-8', 1);
 * $new_array = arrayshuffle($array);
 * print_r(join($new_array));
 */



set_time_limit(0);
// $str='我爱你url网址，你呢';
$str = 'url网址 < a href = "127.0.0.1/pe" title="摘要"/ >地方la</a >gfb6是否<body></html>';
// $str = file_get_contents('http://127.0.0.1/pe');

$array = str2array($str, 'utf-8', 1);
print_r(join($array));

$new_array = arrayshuffle($array);
echo "\r\n<br>";
print_r(join($new_array)) . "\r\n<br />";

function arrayshuffle($array){   # 必须和 str2array() 中的编码($charset)一致，否则shuffle() 将导致乱码
    
    $key = array_keys($array);
    $key2 = shuffle($key);
    $n = count($array);
    $new_key = '';
    $new_str = '';
    $new_array = array();

    for($i = 0; $i < $n; $i++){
        $new_key .= $key[$i] . ',';
        $new_str = $array[$key[$i]];
        $new_array[$key[$i]] = $array[$key[$i]];
        }
    return $new_array;
    }

function str2array($str, $charset, $len = 1){   # 字符串转数组

    $str = str_replace('< ', '<', $str);
    $str = str_replace(' >', '>', $str);    
    $n = mb_strlen($str, $charset);

    for($i = 0;$i < $n;$i += $len)
    {
	    $part1 = mb_substr($str, $i, $n, $charset);                            # 截取剩余的部分
        if($part1[0] == '<')
            {

            preg_match("/(\<.*?\>)(.*?)(\>)/i", $part1, $matches);                 # 检测$part1中HTML非单一标签
            if(isset($matches[0]) && strstr($matches[0], '<style') || isset($matches[0]) && strstr($matches[0], '<script')){
                                                                                   # 分析CSS和JS
                $strarr[] = $matches[0];
                $taglen = mb_strlen($matches[0], $charset);                        # $taglen是闭合标签的长度
                }elseif(isset($matches[1])){                                       # 其它的非单一标签
                
                $strarr[] = $matches[1];
                $taglen = mb_strlen($matches[1], $charset);
                }else{
                preg_match("/(\<.*?\>)(.*?)/i", $part1, $matches);                 # 检测$part1中HTML单一标签
                $strarr[] = $matches[1];
                $taglen = mb_strlen($matches[1], $charset);
                }
            $i += $taglen;                                                         # $i的值
            $i -= 1;
            continue;                                                              # 跳出本次循环，进行下一次循环 
            }
		$tag = mb_strpos($part1, '<',0, $charset);
        $strarr[] = base64_encode(mb_substr($part1, 0, $tag, $charset));

		$i +=$tag;
		$i = ($i - 1);
        }
    return $strarr;
    }
?>