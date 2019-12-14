@echo off
color 3f
mode con:cols=40 lines=2
title 停止服务器

set www=%~dp0
set KEY_DIR=%www%apache22\bin\demoCA
set KEY_CONFIG=%www%apache22\bin\openssl.cnf

%~d0
cd %www%
cls
rem echo          将停止服务器,是否继续? &pause>nul 

net stop Apache2.2
apache22\bin\httpd -k uninstall
net stop MySQL
mysql5\bin\mysqld-nt -remove

del/F/Q %SystemRoot%\libmysql.dll
del/F/Q %SystemRoot%\libeay32.dll
del/F/Q %SystemRoot%\ssleay32.dll
del/F/Q %SystemRoot%\libmcrypt.dll
del/F/Q %SystemRoot%\libmhash.dll
del/F/Q %SystemRoot%\libswish-e.dll
del/F/Q %SystemRoot%\php5isapi.dll
cls
echo   服务器处于停止状态，需要启动服务器?  &pause>nul 

title 制作服务器证书
cls
copy php5\libmysql.dll %SystemRoot%\libmysql.dll
copy php5\libeay32.dll %SystemRoot%\libeay32.dll
copy php5\ssleay32.dll %SystemRoot%\ssleay32.dll
copy php5\libmhash.dll %SystemRoot%\libmhash.dll
copy php5\libmcrypt.dll %SystemRoot%\libmcrypt.dll
copy php5\libswish-e.dll %SystemRoot%\libswish-e.dll
copy php5\php5isapi.dll %SystemRoot%\php5isapi.dll
cls
cd apache22\bin\
openssl req -days 8650 -nodes -rand %KEY_DIR%\.rnd; -new  -keyout %KEY_DIR%\server.key -subj /emailAddress="postmaster@localhost"/CN="127.0.0.1"/OU="PHPER"/O="RP Co.,LTD"/L="Ningxia"/ST="YinChuan"/C="CN"  -out %KEY_DIR%\server.csr -config %KEY_CONFIG%
openssl ca -days 8650 -out %KEY_DIR%\server.crt -in %KEY_DIR%\server.csr -passin pass:0000000 -config %KEY_CONFIG%
cd %www%
del/F/Q apache22\conf\ssl.crt\ca.crt
del/F/Q apache22\conf\ssl.crt\server.crt
del/F/Q apache22\conf\ssl.key\server.key
del/F/Q apache22\bin\.rnd
del/F/Q %KEY_DIR%\*.old
cls
copy %KEY_DIR%\certs\cacert.pem  apache22\conf\ssl.crt\ca.crt
copy %KEY_DIR%\server.crt apache22\conf\ssl.crt\server.crt
copy %KEY_DIR%\server.key apache22\conf\ssl.key\server.key
cls
title 正在启动服务器
mysql5\bin\mysqld-nt install
net start MySQL
apache22\bin\httpd -D SSL -k install
net start Apache2.2
cls
rem echo      服务器已启动，需要打开LOCALHOST? &pause>nul 
start /max c:\"program files"\"internet explorer"\iexplore.exe https://127.0.0.1/

