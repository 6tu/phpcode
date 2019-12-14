<?php

/**
 * Class IP
 * @Project: generateIP
 * @Description: 根据省份名（中文）随机生成IPV4地址
 * @Author: xiayulei@gmail.com
 * @Update: 2016.3.2
 */
class IP
{

    /**
     * 根据省份名（中文）随机生成IPV4地址
     * $province为空则随机国内省份
     * @param $province
     * @return string
     */
    public static function generate($province = '')
    {
        $ip_address = self::_IPSegment($province);
        $ip = self::_randomIP($ip_address['begin'], $ip_address['end']);
        return $ip;
    }

    /**
     * 更新各省IPV4地址段
     */
    public static function update()
    {
        require 'QueryList.class.php';
        $province = array(
            'BJ', 'GD', 'SD', 'ZJ', 'JS', 'SH', 'LN', 'SC', 'HA',
            'HB', 'FJ', 'HN', 'HE', 'CQ', 'SX', 'JX', 'SN', 'AH',
            'HL', 'GX', 'JL', 'YN', 'TJ', 'NM', 'XJ', 'GS', 'GZ',
            'HI', 'NX', 'QH', 'XZ'
        );
        $count = count($province);
        $ip_segment = array();
        for ($i = 0; $i < $count; $i++) {
            // 采集IP地址段目标网址
            $url = 'http://ips.chacuo.net/view/s_' . $province[$i];
            $ip_list = QueryList::Query($url, array('begin' => array('.v_l', 'text'), 'end' => array('.v_r', 'text')), '', 'UTF-8');
            $ip_array = $ip_list->jsonArr;
            $ip_segment[$province[$i]] = $ip_array;
        }
        $path = str_replace('\\', '/', __DIR__);
        $file = $path . '/ip_segment.php';
        $res = self::_write($file, $ip_segment);
        if ($res) {
            echo 'Update OK!';
        }
    }

    /**
     * 根据IPV4地址段随机生成IP
     * @param $ip_begin
     * @param $ip_end
     * @return string
     */
    private static function _randomIP($ip_begin, $ip_end)
    {
        $ip_begin_array = explode('.', $ip_begin);
        $ip_end_array = explode('.', $ip_end);
        $ip1 = mt_rand($ip_begin_array[0], $ip_end_array[0]);
        $ip2 = mt_rand($ip_begin_array[1], $ip_end_array[1]);
        $ip3 = mt_rand($ip_begin_array[2], $ip_end_array[2]);
        $ip4 = mt_rand($ip_begin_array[3], $ip_end_array[3]);

        // IPV4地址最后一位不为0或255
        if ($ip4 == 0 || $ip4 == 255) {
            $ip4 = mt_rand(2, 254);
        }
        $ip = $ip1 . '.' . $ip2 . '.' . $ip3 . '.' . $ip4;
        return $ip;
    }

    /**
     * 根据省份名称返回IPV4地址段
     * $province 为空则随机返回一个省份IPV4地址段
     * @param $province
     * @return array|string
     */
    private static function _IPSegment($province = '')
    {
        $ip_segment = [];
        require 'ip_segment.php';
        $province_array = array(
            '北京' => 'BJ',
            '广东' => 'GD',
            '山东' => 'SD',
            '浙江' => 'ZJ',
            '江苏' => 'JS',
            '上海' => 'SH',
            '辽宁' => 'LN',
            '四川' => 'SC',
            '河南' => 'HA',
            '湖北' => 'HB',
            '福建' => 'FJ',
            '湖南' => 'HN',
            '河北' => 'HE',
            '重庆' => 'CQ',
            '山西' => 'SX',
            '江西' => 'JX',
            '陕西' => 'SN',
            '安徽' => 'AH',
            '黑龙江' => 'HL',
            '广西' => 'GX',
            '吉林' => 'JL',
            '云南' => 'YN',
            '天津' => 'TJ',
            '内蒙' => 'NM',
            '新疆' => 'XJ',
            '甘肃' => 'GS',
            '贵州' => 'GZ',
            '海南' => 'HI',
            '宁夏' => 'NX',
            '青海' => 'QH',
            '西藏' => 'XZ'
        );
        if (empty($province)) {
            $ip_segment_array = $ip_segment[$province_array[array_rand($province_array, 1)]];
        } elseif (array_key_exists($province, $province_array)) {
            $ip_segment_array = $ip_segment[$province_array[$province]];
        } else {
            return false;
        }
        return $ip_segment = $ip_segment_array[array_rand($ip_segment_array, 1)];
    }

    /**
     * 执行写入
     * @param $filename
     * @param $values
     * @param string $var
     * @param bool|false $format
     * @return bool
     */
    private static function _write($filename, $values, $var = 'ip_segment', $format = false)
    {
        $file = $filename;
        if (is_array($values)) {
            $text = "<?php\r\n" . '$' . $var . '=' . self::_arrayConvert($values, $format) . ";";
        } else {
            $text = $values;
        }
        return self::_writeFile($file, $text);
    }

    /**
     * 数组转换
     * @param $array
     * @param bool|false $format
     * @param int $level
     * @return string
     */
    private static function _arrayConvert($array, $format = false, $level = 0)
    {
        $space = $line = '';
        if (!$format) {
            for ($i = 0; $i <= $level; $i++) {
                $space .= "\t";
            }
            $line = "\n";
        }
        $data = 'Array' . $line . $space . '(' . $line;
        $comma = $space;
        foreach ($array as $key => $val) {
            $key = is_string($key) ? '\'' . addcslashes($key, '\'\\') . '\'' : $key;
            $val = !is_array($val) && (!preg_match('/^\-?\d+$/', $val) || strlen($val) > 12) ? '\'' . addcslashes($val, '\'\\') . '\'' : $val;
            if (is_array($val)) {
                $data .= $comma . $key . '=>' . self::_arrayConvert($val, $format, $level + 1);
            } else {
                $data .= $comma . $key . '=>' . $val;
            }
            $comma = ',' . $line . $space;
        }
        $data .= $line . $space . ')';
        return $data;
    }

    /**
     * 写入文件方法
     * @param $filename
     * @param $text
     * @param string $openMode
     * @return bool
     */
    private static function _writeFile($filename, $text, $openMode = 'w')
    {
        if (false !== $fp = fopen($filename, $openMode)) {
            flock($fp, 2);
            fwrite($fp, $text);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    }
}
