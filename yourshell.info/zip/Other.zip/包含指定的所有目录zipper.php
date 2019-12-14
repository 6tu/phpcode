<?php
class Zipper extends ZipArchive {
public function addDir($path) {
print 'adding ' . $path . '<br>';
//$this->addEmptyDir($path);
$nodes = glob($path . '/*'); //
foreach ($nodes as $node) {
print $node . '<br>';
if (is_dir($node)) {
$this->addDir($node);
} else if (is_file($node))  {
$this->addFile($node);
}
}
} 
}
$zip = new Zipper;
$res = $zip->open('test.zip', ZipArchive::CREATE);
if ($res === TRUE) {
$zip->addDir('mail2www\wwwdata\master@walk.net');
$zip->close();
echo 'ok';
} else {
echo 'failed';
}