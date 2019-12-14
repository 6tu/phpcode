# generateIP
根据省份名称随机生成对应省份IPV4地址

## 目录文件
```php
QueryList.class.php             // PHP采集类
```
```php
phpQuery                        // QueryList类所需文件目录
```
```php
IP.class.php                    // 主IP生成类
```
```php
ip_segment.php                  // 采集生成的IPV4地址段
```

## 使用说明

### 引入IP类
```php
require 'IP.class.php';
```
### 方法使用

##### 不指定省份名，将随机生成国内IPV4地址
```php
$ip_address = IP::generate();
```

##### 指定省份名生成对应省份IPV4地址
```php
$ip_address = IP::generate('安徽');
```

##### 更新各省IPV4地址段
```php
IP::update();
```

文件包含省份：
```php
* 北京
* 广东
* 山东
* 浙江
* 江苏
* 上海
* 辽宁
* 四川
* 河南
* 湖北
* 福建
* 湖南
* 河北
* 重庆
* 山西
* 江西
* 陕西
* 安徽
* 黑龙江
* 广西
* 吉林
* 云南
* 天津
* 内蒙
* 新疆
* 甘肃
* 贵州
* 海南
* 宁夏
* 青海
* 西藏
 ```
