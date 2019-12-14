<SCRIPT LANGUAGE="JavaScript">
<!--
function show(s) { 
s2=''
for( i=0; i<s.length/4; i++)
 s2+='%u'+s.substring(i*4,i*4+4)
document.write( unescape(s2) )
}

hexnum='0123456789ABCDEF'
function  hex4(n){
h3=n>>12  
h2=(n&0x0F00)>>8 
h1=(n&0x00F0)>>4 
h0=(n&0x000F) 
return hexnum.charAt(h3)+hexnum.charAt(h2)+''+hexnum.charAt(h1)+''+hexnum.charAt(h0)
}
function u4u(msg){
u4=''
for(i=0;i<msg.length;i++) 
u4+= hex4( msg.charCodeAt(i) )	
return u4
}
 
function check4https(){ 
document.f2.mao2URL.value = u4u(document.f1.mao2URL.value); 
document.f2.action='https://g02.appspot.com/f'
document.f2.submit()
return false 
}
function check(){ 
document.f1.mao2URL.value=document.f1.mao2URL.value.replace(/\s*/g,"");
url=document.f1.mao2URL.value.toLowerCase( );

if( url =='http://' ) {
alert( 'Please input your url' )
return false
}
if( url.indexOf('http://http://')==0 || url.indexOf('http://https://')==0 ) {
url=url.substring(7)
document.f1.mao2URL.value=url
}
if( url.indexOf('http//')==0 ) {
url=url.substring(6)
document.f1.mao2URL.value=url
}

if( url.indexOf('http:///')==0 ) {
url=url.substring(8)
document.f1.mao2URL.value=url
}

if( url.indexOf('http:/')==0 && url.indexOf('http://')!=0 ) {
url=url.substring(6)
document.f1.mao2URL.value=url
} 

if( url.length<4 || url.indexOf('.')<0 ){
	str='The url ['+url+'] may be not correct.' 
    if( url.indexOf('http')==0 )
       url=url.substring(7)
    if( !window.confirm( str+'\nDo you mean www.'+url+'.com?' ) )
        return false
    document.f1.mao2URL.value='www.'+url+'.com'
}
     
if( document.f1.mao2URL.value.substring(0,4)!='http' ) 
    document.f1.mao2URL.value='http://'+document.f1.mao2URL.value
document.f2.mao2URL.value = u4u(document.f1.mao2URL.value);
document.f2.submit(); 
return false;
}
timename=window.setInterval("mao2delall()",2000);   
 

//-->
</SCRIPT> 