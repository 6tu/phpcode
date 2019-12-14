var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64DecodeChars = new Array(
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
　　52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
　　-1,　0,　1,　2,　3,  4,　5,　6,　7,　8,　9, 10, 11, 12, 13, 14,
　　15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
　　-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
　　41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
function base64encode(str) {
　　var out, i, len;
　　var c1, c2, c3;
　　len = str.length;
　　i = 0;
　　out = "";
　　while(i < len) {
 c1 = str.charCodeAt(i++) & 0xff;
 if(i == len)
 {
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt((c1 & 0x3) << 4);
　　 out += "==";
　　 break;
 }
 c2 = str.charCodeAt(i++);
 if(i == len)
 {
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
　　 out += base64EncodeChars.charAt((c2 & 0xF) << 2);
　　 out += "=";
　　 break;
 }
 c3 = str.charCodeAt(i++);
 out += base64EncodeChars.charAt(c1 >> 2);
 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
 out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
 out += base64EncodeChars.charAt(c3 & 0x3F);
　　}
　　return out;
}
function base64decode(str) {
　　var c1, c2, c3, c4;
　　var i, len, out;
　　len = str.length;
　　i = 0;
　　out = "";
　　while(i < len) {
 /* c1 */
 do {
　　 c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
 } while(i < len && c1 == -1);
 if(c1 == -1)
　　 break;
 /* c2 */
 do {
　　 c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
 } while(i < len && c2 == -1);
 if(c2 == -1)
　　 break;
 out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
 /* c3 */
 do {
　　 c3 = str.charCodeAt(i++) & 0xff;
　　 if(c3 == 61)
　return out;
　　 c3 = base64DecodeChars[c3];
 } while(i < len && c3 == -1);
 if(c3 == -1)
　　 break;
 out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
 /* c4 */
 do {
　　 c4 = str.charCodeAt(i++) & 0xff;
　　 if(c4 == 61)
　return out;
　　 c4 = base64DecodeChars[c4];
 } while(i < len && c4 == -1);
 if(c4 == -1)
　　 break;
 out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
　　}
　　return out;
}
function utf16to8(str) {
　　var out, i, len, c;
　　out = "";
　　len = str.length;
　　for(i = 0; i < len; i++) {
 c = str.charCodeAt(i);
 if ((c >= 0x0001) && (c <= 0x007F)) {
　　 out += str.charAt(i);
 } else if (c > 0x07FF) {
　　 out += String.fromCharCode(0xE0 | ((c >> 12) & 0x0F));
　　 out += String.fromCharCode(0x80 | ((c >>　6) & 0x3F));
　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
 } else {
　　 out += String.fromCharCode(0xC0 | ((c >>　6) & 0x1F));
　　 out += String.fromCharCode(0x80 | ((c >>　0) & 0x3F));
 }
　　}
　　return out;
}
function utf8to16(str) {
　　var out, i, len, c;
　　var char2, char3;
　　out = "";
　　len = str.length;
　　i = 0;
　　while(i < len) {
 c = str.charCodeAt(i++);
 switch(c >> 4)
 {
　 case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
　　 // 0xxxxxxx
　　 out += str.charAt(i-1);
　　 break;
　 case 12: case 13:
　　 // 110x xxxx　 10xx xxxx
　　 char2 = str.charCodeAt(i++);
　　 out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
　　 break;
　 case 14:
　　 // 1110 xxxx　10xx xxxx　10xx xxxx
　　 char2 = str.charCodeAt(i++);
　　 char3 = str.charCodeAt(i++);
　　 out += String.fromCharCode(((c & 0x0F) << 12) |
　　　　((char2 & 0x3F) << 6) |
　　　　((char3 & 0x3F) << 0));
　　 break;
 }
　　}
　　return out;
}
//base64编码文 转 明文
function b64toa(b64) {
　　var data = "";
　　data = utf8to16(base64decode(b64));
    return data;
}
// 明文 转 base64编码文
function atob64(data) {
　　var b64 = "";
　　b64 = base64encode(utf16to8(data));
    return b64;
}

function createRequest(){
        if(typeof XMLHttpRequest!="undefined")        {
                return new XMLHttpRequest();
        }else if(typeof ActiveXObject!="undefined"){
                var xmlHttp_ver  = false;
                var xmlHttp_vers = ["MSXML2.XmlHttp.5.0","MSXML2.XmlHttp.4.0","MSXML2.XmlHttp.3.0","MSXML2.XmlHttp","Microsoft.XmlHttp"];
                if(!xmlHttp_ver){
                        for(var i=0;i<xmlHttp_vers.length;i++){
                                try{
                                        new ActiveXObject(xmlHttp_vers[i]);
                                        xmlHttp_ver = xmlHttp_vers[i];
                                        break;
                                }catch(oError){;}
                        }
                }
                if(xmlHttp_ver){
                        return new ActiveXObject(xmlHttp_ver);
                }else{
                        throw new Error("Could not create XML HTTP Request.");
                }
        }else{
                throw new Error("Your browser doesn't support an XML HTTP Request.");
        }
}

var xmlHttp;

function sendPostRequest()
{    
                     xmlHttp = createRequest();
        var m=document.getElementById("m").value;// html页面中一个id为shownum的input表单
        var m = atob64(m);
        //alert(m);
        var url = "index2.php";//要发送到的URL
        var queryString = "m" + "=" + (m) + "&a=" + "search" + "&encode=" + "base64";


       //向服务端发送请求
        xmlHttp.open("post", url, true);//这里的第三个参数为true为异步方式处理
        xmlHttp.onreadystatechange = showData;//异步方式处理，当状态改变时会调用onreadystatechange属性指定的回调函数showData
        xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");//这一句是用post方法发送的时候必须写的
        xmlHttp.send(queryString);//发送你构建成的数据,如果为“get”方法时，这里可以写成xmlHttp.send(NULL);
}
function showData()
{ 
        var msg=document.getElementById("status");
                     //第４步
        if(xmlHttp.readyState==4)
        { 
                if(xmlHttp.status==200)
                { 
　　　　　　　　　　　　　　　　//只有当readyState为4并且status为200时，才表示符合要求
                                                               //下面这一句话，就相当于上面说的第５步，处理返回的结果
                        msg.innerHTML =  xmlHttp.responseText;
                }
        }
        else
        {
                switch(xmlHttp.readyState)
                {
                case 0:
                        msg.innerHTML="未初始化...";
                        break;
                case 1:
                        msg.innerHTML="加载中...";
                        break;
                case 2:
                        msg.innerHTML="连接完成...";
                        break;
                case 3:
                        msg.innerHTML="交换数据...";
                        break;
                default:
                        break;
                }
        }
}