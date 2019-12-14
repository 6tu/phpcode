<?php

$self = htmlspecialchars(file_get_contents('d.php'));
if(!isset($_POST)){
	$e = $_POST['edit'];
	file_put_contents('temp.php', $e);
	if(!php_check_syntax("temp.php", $msg)){
		echo $msg;
	}else{
		echo "Woohoo, OK!";
	}
}else echo self_form($self);

# ¼ì²âÓï·¨´íÎó
if(!function_exists('php_check_syntax')){
    function php_check_syntax($file_name, &$error_message = null){
        $file_content = file_get_contents($file_name);
        $check_code = "return true; ?>";
        $file_content = $check_code . $file_content . "<?php ";
        if(!@eval($file_content)){
            $error_message = "file: " . realpath($file_name) . " have syntax error";
            return false;
        }
        return true;
    }
}

function self_form($self){
	$form  = '<br><br><br><center>';
	$form .= '<form id="dreamduform" action="d.php" method="post">';
	$form .= '<textarea cols="40" rows="30" id="edit" name="edit">' .$self. '</textarea><br>';
	$form .= '<input type="submit" id="button" value="Ìá½»">';
	$form .= '</form></center>';
	return $form;
}
?>




