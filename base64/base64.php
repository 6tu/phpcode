<?php
$html = '<center><br><br><form action="" method="post">';
$html .= '<input type="text" size="50" name="encode" value="base64_encode" /><br><br>';
$html .= '<textarea name="decode" cols="52" rows="5" ></textarea><br><br>';
$html .= '<input type="submit" value="提交" /><br><br></form>';
if(empty($_POST['decode']) or empty($_POST['encode'])) exit($html);
else{
	$base64 = $_POST['decode'];
	$base64 = base64_decode($base64);
	file_put_contents('base64.zip', $base64);
	echo $html . '<a href="./base64.zip" >base64.zip</a>';
}
?>