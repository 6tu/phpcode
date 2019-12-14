在线修改S60V2 Opera mini6服务器 

OperaMini.sis(MD5: 6eebc1b488cf7c3863a63624bab9801e) 版本: 6.0.24458


下载安装mini6并初始化完毕，文件在 http://m.opera.com/OperaMini.sis?act=dl&tag=mini5s60_sdk2 ，使用的是 20110617 之前的软件

需要一个自建的代理网址，相关代码在古歌的opm-server-mirror上，服务器指向server4.operamini.com或者mini5.opera-mini.net都行；建成的网址不包含http://不得超过29个字符

把这个网址提交过来，然后下载修改好的*_OperaMini.app文件，把文件名改为OperaMini.app，覆盖 \system\apps\OperaMini\OperaMini.app 这个文件就OK了

* 浏览国外网站时，进入 工具 --> 设置 --> 网络协议 --> HTTP(选这个)，用g.cn测试一下，如果正常就正常了，浏览国内的网站，把“网络协议”更换到“Socket”，效果会更好一些 。

* 如果无法输入中文，那就装个输入法，或者是调成英文
