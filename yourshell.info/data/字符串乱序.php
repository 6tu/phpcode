<?php
/**
 * �ַ���ת���飬�ַ�������
 * ������ϰ��
 * �ļ�����ͺ����е�$charset����һ�£������������
 * 20110609 http://yourshell.info/
 * 
 * $array = str2array($str, 'utf-8', 1);
 * $new_array = arrayshuffle($array);
 * print_r(join($new_array));
 */



set_time_limit(0);
// $str='�Ұ���url��ַ������';
$str = 'url��ַ < a href = "127.0.0.1/pe" title="ժҪ"/ >�ط�la</a >gfb6�Ƿ�<body></html>';
// $str = file_get_contents('http://127.0.0.1/pe');

$array = str2array($str, 'utf-8', 1);
print_r(join($array));

$new_array = arrayshuffle($array);
echo "\r\n<br>";
print_r(join($new_array)) . "\r\n<br />";

function arrayshuffle($array){   # ����� str2array() �еı���($charset)һ�£�����shuffle() ����������
    
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

function str2array($str, $charset, $len = 1){   # �ַ���ת����

    $str = str_replace('< ', '<', $str);
    $str = str_replace(' >', '>', $str);    
    $n = mb_strlen($str, $charset);

    for($i = 0;$i < $n;$i += $len)
    {
	    $part1 = mb_substr($str, $i, $n, $charset);                            # ��ȡʣ��Ĳ���
        if($part1[0] == '<')
            {

            preg_match("/(\<.*?\>)(.*?)(\>)/i", $part1, $matches);                 # ���$part1��HTML�ǵ�һ��ǩ
            if(isset($matches[0]) && strstr($matches[0], '<style') || isset($matches[0]) && strstr($matches[0], '<script')){
                                                                                   # ����CSS��JS
                $strarr[] = $matches[0];
                $taglen = mb_strlen($matches[0], $charset);                        # $taglen�Ǳպϱ�ǩ�ĳ���
                }elseif(isset($matches[1])){                                       # �����ķǵ�һ��ǩ
                
                $strarr[] = $matches[1];
                $taglen = mb_strlen($matches[1], $charset);
                }else{
                preg_match("/(\<.*?\>)(.*?)/i", $part1, $matches);                 # ���$part1��HTML��һ��ǩ
                $strarr[] = $matches[1];
                $taglen = mb_strlen($matches[1], $charset);
                }
            $i += $taglen;                                                         # $i��ֵ
            $i -= 1;
            continue;                                                              # ��������ѭ����������һ��ѭ�� 
            }
		$tag = mb_strpos($part1, '<',0, $charset);
        $strarr[] = base64_encode(mb_substr($part1, 0, $tag, $charset));

		$i +=$tag;
		$i = ($i - 1);
        }
    return $strarr;
    }
?>