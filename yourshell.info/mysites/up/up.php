<?php
	require('uploader.php');

  //echo the upload form
	echo "
	<hr /><form method=\"post\" enctype=\"multipart/form-data\">
	<input type=\"file\" name=\"file_to_be_uploaded\">
	<input type=\"submit\" name=\"upload\" value=\"Upload\">
	</form>";


if (isset($_POST['upload']))
{
	//instantiate a object. Set debug to silent. Errors will not be diplayed
	$uploader = new uploader( './updata/', UPLOADER::SILENT );
	
	//check the file 
	
	// set the type of files that can be allowed
	$types 	= array('.zip', '.rar', '.txt','.php', '.7z', '.doc','.png', '.gif', '.jpeg');
	
	// set max size as 2MB. remember that all the other params are optional
	$limits = array('max_size' => 2 );
	
	//check if file passes validity checks
	$check 	= $uploader->checkFile('file_to_be_uploaded', $types, $limits );
	
	if ( $check )
	{
		//upload file as zip and keep the original
		if ( !$uploader->uploadZipped( '', '', true ) )var_dump( $uploader->getError() );
		

		//upload the file resized to 64 x 64. 
		//Script will determine correct ratio and upload file as such. notice that strict mode is set to false. 
		//If thumbnail mode and strict are set to true, thumbnail mode takes precedence.  
		if ( !$uploader->uploadFile( '', '', false, array(true, 'width' => 64, 'height' => 64) )) var_dump( $uploader->getError() );
		
	}
	else var_dump( $uploader->getError() );
	
}
?>
