<?php
/*
Linux �ռ��ļ�ɾ����
����:���V�е��
�汾1.0
��Ȩ:�������,���⴫��
####����####
�����Ϊ�ռ�ά������,ʹ�����֮��������ɾ�����ļ�
*/
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk">
<title>�ռ��ļ���/�ļ�ɾ������</title>
<style>
body {font-family:"����"; font-size:12px;}
imput { border:1px #ccc solid;}
b { color:#FF0000;}
</style>
</head>
<body>
<form action="?action=dirdel" method="post">
ɾ���ļ���,<b>��ȷ����д������ٽ���ɾ������!</b><br>
�������ļ���·��,����ļ�����ʹ��";"����
<input type="text" name="all_folder" size="50">
<input type="submit" value="ɾ��">
</form>
<br>
<form action="?action=filedel" method="post">
ɾ���ļ�,<b>��ȷ����д������ٽ���ɾ������!</b><br>
�������������ļ�·��,����ļ���ʹ��";"����
<input type="text" name="all_files" size="50">
<input type="submit" value="ɾ��">
</form>
<br>
<?php
$action = $_GET['action'];

//ɾ��Ŀ¼����
if($action=='dirdel') {
$all_folder = $_POST['all_folder'];
if(!empty($all_folder)) {
  //���ݷֺ�ʶ�����ļ���
  $folders = explode(';',$all_folder);
  if(is_array($folders)) {
   foreach($folders as $folder) {
    deldir($folder);
    echo $folder . 'ɾ���ɹ�<Br>';
   }
  }
}
}

if($action=='filedel') {
$all_files = $_POST['all_files'];
if(!empty($all_files)) {
  //���ݷֺ�ʶ�����ļ�
  $files = explode(';',$all_files);
  if(is_array($files)) {
   foreach($files as $file) {
    if(is_file($file)) {
     if(unlink($file)) {
      echo $file . 'ɾ���ɹ�<Br>';
     } else {
      echo $file . '�޷�ɾ��,����Ȩ��<Br>';
     }
    } else {
     echo $file . '������<br>';
    }
   }
  }
}
}





//ɾ��Ŀ¼���������ļ�����
function deldir($dir) {
//���ļ�Ŀ¼
$dh = opendir($dir);
//ѭ����ȡ�ļ�
while ($file = readdir($dh)) {
  if($file != '.' && $file != '..') {
   $fullpath = $dir . '/' . $file;
   //�ж��Ƿ�ΪĿ¼
   if(!is_dir($fullpath)) {
    //�������,ɾ�����ļ�
    if(!unlink($fullpath)) {
     echo $fullpath . '�޷�ɾ��,������û��Ȩ��!<br>';
    }
   } else {
    //�����Ŀ¼,�ݹ鱾��ɾ���¼�Ŀ¼
    deldir($fullpath);
   }
  }
}
//�ر�Ŀ¼
closedir($dh);
//ɾ��Ŀ¼
if(rmdir($dir)) {
  return true;
} else {
  return false;
}
}
?>
</body>
</html>