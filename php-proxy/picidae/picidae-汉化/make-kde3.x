make-kde3.x



Linux - �� Konstruct ��װ KDE 3.x 

�Ҹ���ͼ���� khtml2png ��http://khtml2png.sourceforge.net/����

���ڶ��� python  ��� webkit2png �����ò�Ʊ���̭�� 
https://pypi.python.org/pypi/webkit2png/
https://github.com/AdamN/python-webkit2png/

���и���Դ�� http://cutycapt.sourceforge.net/

http://www.th7.cn/Program/Python/201404/191646.shtml
http://blog.chinaunix.net/uid-20357359-id-1963700.html



 khtml2png ��Ҫ kde3.x ,���̫���ˣ�ubuntu ���ṩ���߰�װ���ѵ����뷽��

ԭ�� http://ccm.net/faq/4900-linux-installing-kde-3-5-4-with-konstruct

ԭ���ṩ���������Ӳ����ã��Ҹ����õġ�

���� kde3.5.3 ,

  mkdir /root/kde3.5.3-sources  
  cd /root/kde3.5.3-sources
  wget -r h http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/*tar.bz2
  ls -1 /root/kde3.5.4-sources

wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/arts-1.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeaccessibility-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeaddons-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeadmin-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeartwork-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdebase-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdebindings-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeedu-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdegames-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdegraphics-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdelibs-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdemultimedia-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdenetwork-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdepim-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdesdk-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdetoys-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdeutils-3.5.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdevelop-3.3.3.tar.bz2
wget http://ftp.heanet.ie/mirrors/ftp.kde.org/Attic/3.5.3/src/kdewebdev-3.5.3.tar.bz2

���ؽ�ѹ konstruct-stable.tar.bz2 ��༭ /root/konstruct/gar.conf.mk
  mkdir /root/src  
  cd /root/src
  wget http://www.sourcefiles.org/System/Administration/Installation/konstruct-stable.tar.bz2
  tar xjvf konstruct-stable.tar.bz2  
  vim /root/konstruct/gar.conf.mk

  GARCHIVEDIR= (HOME)/kde3.5.3-sources  

  :wq

��װ

  cd /root/src  
  cd konstruct/meta/kde  
  make install  

��Ҫ���ļ�

http://ftp.osuosl.org/pub/blfs/conglomeration/glib/glib-2.10.3.tar.bz2
https://pkg-config.freedesktop.org/releases/pkg-config-0.20.tar.gz
https://ftp.gwdg.de/pub/linux/troll/qt/source/qt-x11-free-3.3.6.tar.bz2







ftp//ftp.trolltech.com/qt/source/qt-x11-free-3.3.6.tar.bz2

https://download.qt.io/archive/qt/3/







cd /root/kde3.5.3-sources





















