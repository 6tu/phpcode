<?php 
//$lang=$_SERVER["HTTP_ACCEPT_LANGUAGE"];
if (basename(__FILE__) == basename($_SERVER['PHP_SELF']))
{
    exit(0);
}
$charset = strtolower($GLOBALS['form_charset']);
if(strstr($charset,'iso') !==false){
$charset = 'GBK' ;
}

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
$scheme = 'https://';
}else{
$scheme = 'http://';
}
$peurl = base64_encode($scheme.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].' version=' .$GLOBALS['_version']);
$url = base64_decode('aHR0cDovL3lvdXJzaGVsbC5pbmZvL3BlL25ld3Zlci5waHA=').'?peurl='.$peurl;

header("Content-type: text/html; charset=$charset"); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=<?php echo $charset; ?>">
<style type="text/css"> 
body, input
{
    font-family: "Bitstream Vera Sans", Arial, Helvetica, sans-serif;
    color: #44352C;
}

#container
{
    border: 1px #97CCA8 solid;
    -moz-border-radius: 8px;
    margin: auto;
    padding: 5px;
    width: 711px;
}

#title
{
    color: #99CC66;
    margin: 0;
}

#footer
{
    color: #9B9C83;
    font-size: 10px;
    text-align: right;
}

#address_bar
{
    border-top: 2px #BFAA9B solid;
    border-bottom: 3px #44352C solid;
    background-color: #99CC66;
    text-align: center;
    padding: 5px 0;
    color: #ffffff;
}

#go
{
    background-color: #ffffff;
    font-weight: bold;
    color: #AA8E79;
    border: 0 #ffffff solid;
    padding: 2px 5px;
}

#address_box
{
    width: 500px;
}

#error, #auth
{
    background-color: #BF6464;
    border-top: 1px solid #44352C;
    border-bottom: 1px solid #44352C;
    width: 700px;
    clear: both;
}

#auth
{
    background-color: #94C261;
}

#error p, #auth p, #auth form
{
    margin: 5px;
}
</style>



</head>
<script type="text/javascript" src="./js/encode.js"></script> 
<script language="javascript" >
function autojs(){
document.form.<?php echo $GLOBALS['_config']['url_var_name'] ?>.value=str2hex(window.btoa(document.form.<?php echo $GLOBALS['_config']['url_var_name'] ?>.value)); 
document.form.submit();
}
</script>
<body onload="document.getElementById('address_box').focus()">
<div id="container">
  <h3 id="title">PHProxy</h3><br />
<?php

switch ($data['category'])
{
    case 'auth':
?>
  <div id="auth"><p>
  <b>Enter your username and password for "<?php echo htmlspecialchars($data['realm']) ?>" on <?php echo $GLOBALS['_url_parts']['host'] ?></b>
  <form method="post" action="">
    <input type="hidden" name="<?php echo $GLOBALS['_config']['basic_auth_var_name'] ?>" value="<?php echo base64_encode($data['realm']) ?>" />
    <label>Username <input type="text" name="username" value="" /></label> <label>Password <input type="password" name="password" value="" /></label> <input type="submit" value="Login" />
  </form></p></div>
<?php
        break;
    case 'error':
        echo '<div id="error"><p>';
        
        switch ($data['group'])
        {
            case 'url':
                echo '<b>URL Error (' . $data['error'] . ')</b>: ';
                switch ($data['type'])
                {
                    case 'internal':
                        $message = 'Failed to connect to the specified host. '
                                 . 'Possible problems are that the server was not found, the connection timed out, or the connection refused by the host. '
                                 . 'Try connecting again and check if the address is correct.';
                        break;
                    case 'external':
                        switch ($data['error'])
                        {
                            case 1:
                                $message = 'The URL you\'re attempting to access is blacklisted by this server. Please select another URL.';
                                break;
                            case 2:
                                $message = 'The URL you entered is malformed. Please check whether you entered the correct URL or not.';
                                break;
                        }
                        break;
                }
                break;
            case 'resource':
                echo '<b>Resource Error:</b> ';
                switch ($data['type'])
                {
                    case 'file_size':
                        $message = 'The file your are attempting to download is too large.<br />'
                                 . 'Maxiumum permissible file size is <b>' . number_format($GLOBALS['_config']['max_file_size']/1048576, 2) . ' MB</b><br />'
                                 . 'Requested file size is <b>' . number_format($GLOBALS['_content_length']/1048576, 2) . ' MB</b>';
                        break;
                    case 'hotlinking':
                        $message = 'It appears that you are trying to access a resource through this proxy from a remote Website.<br />'
                                 . 'For security reasons, please use the form below to do so.';
                        break;
                }
                break;
        }
        
        echo 'An error has occured while trying to browse through the proxy. <br />' . $message . '</p></div>';
        break;
}
?>
  <form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>"  onsubmit="autojs();" >

      <div id="address_bar"><label><?php echo $GLOBALS['address']; ?>
       <input id="address_box" type="text" name="<?php echo $GLOBALS['_config']['url_var_name'] ?>" value="<?php echo isset($GLOBALS['_url']) ? htmlspecialchars($GLOBALS['_url']) : '' ?>" onfocus="this.select()" /></label> 
       <input id="go" type="submit" value="<?php echo $GLOBALS['go']; ?>" />&nbsp;&nbsp;<a href="<?php echo $GLOBALS['_script_base'] ?>"> <b><font color=#ffffff>[<?php echo $GLOBALS['reset']; ?>]</font></b></a>
     </div><br /><center>
      <?php
      
      foreach ($GLOBALS['_flags'] as $flag_name => $flag_value)
      {
          if (!$GLOBALS['_frozen_flags'][$flag_name])
          {
              echo '<label><input type="checkbox" name="' . $GLOBALS['_config']['flags_var_name'] . '[' . $flag_name . ']"' . ($flag_value ? ' checked="checked"' : '') . ' />' . $GLOBALS['_labels'][$flag_name][0] . '</label>' . "\n";
          }
      }
      ?>

</form><br />

<?php
$update = getpage($url);
if(!strstr($update,'false')) echo $update;
?>

<hr color=#99CC66 /><br />
</center>
<?php

echo $GLOBALS['mylink'];

?>

<br /><br />
  <div id="footer"><a href="http://epoxy.sf.net/">PHProxy Encrypt</a> <?php echo $GLOBALS['_version']; ?><a href="<?php echo $_SERVER['PHP_SELF'] ?>?msg=contact">联系方式</a></div>
</div><br />

</body>
</html>