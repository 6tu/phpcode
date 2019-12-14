<?php
/*
* 
*
* Copyright (c) 2011, frostymarvelous <sfroelich01@gmail.com>.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without modification, are permitted provided
* that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice, this list of conditions and the
*   following disclaimer.
* - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
*   following disclaimer in the documentation and/or other materials provided with the distribution.
* - Neither the name of the  author, nor the names of its contributors may be used to endorse or promote
*   products derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
* WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
* PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
* ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
* TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
* HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
* NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
*/

/*
*<?php
*	require('uploader.php');
*
*  echo the upload form
*	echo "
*	<hr /><form method=\"post\" enctype=\"multipart/form-data\">
*	<input type=\"file\" name=\"file_to_be_uploaded\">
*	<input type=\"submit\" name=\"upload\" value=\"Upload\">
*	</form>";
*
*
*if (isset($_POST['upload']))
*{
*	//instantiate a object. Set debug to silent. Errors will not be diplayed
*	$uploader = new uploader( './', UPLOADER::SILENT );
*	
*	//check the file 
*	
*	// set the type of files that can be allowed
*	$types 	= array('.png', '.gif', '.jpeg');
*	
*	// set max size as 2MB. remember that all the other params are optional
*	$limits = array('max_size' => 2 );
*	
*	//check if file passes validity checks
*	$check 	= $uploader->checkFile('file_to_be_uploaded', $types, $limits );
*	
*	if ( $check )
*	{
*		//upload file as zip and keep the original
*		if ( !$uploader->uploadZipped( '', '', true ) )var_dump( $uploader->getError() );
*		
*
*		//upload the file resized to 64 x 64. 
*		//Script will determine correct ratio and upload file as such. notice that strict mode is set to false. 
*		//If thumbnail mode and strict are set to true, thumbnail mode takes precedence.  
*		if ( !$uploader->uploadFile( '', '', false, array(true, 'width' => 64, 'height' => 64) )) var_dump( $uploader->*getError() );
*		
*	}
*	else var_dump( $uploader->getError() );
*	
*}
*?>
*/

/**
*   A class to help in securely uploading files.
*   
*   This class has functions to check if a file is valid for uploading and carries out the process of uploading.
*   It checks for valid files based on specifications set by the user.
*	It can check if image files are valid and also remove exif data which can be dangerous using GD2 resizing functions  
*	It can also upload an image as a zip file
*
*
*   @package uploader
*   @author frostymarvelous <sfroelich01@gmail.com>
*   @copyright Copyright (c) 2011, frostymarvelous
*   @link http://ifora.net  Free Website Builder
*   @link http://ifora.tk  Free Website Builder
*   @link http://froelich.tk  My Blog
*   @version 0.1a  
*/


/**
*   change log
*	made pclzip inlcluded only in the necessary method
*	changed error messages
*	fixed file extension array check where uppercase failed
*/



/**
*   The uploader class
*   
*   This class handles all the file validity checks and upload processess 
*
*
*   @author frostymarvelous <sfroelich01@gmail.com>
*   @copyright Copyright (c) 2011, frostymarvelous
*   @version 0.1a  
*   @package uploader
*/
class uploader
{
	const SILENT	= 0;
	const ERROR 	= 1;
	const EXCEPTION	= 2;
	
 	/**
    *   An array mapping file extensions to file types.
    *
    * 	This array will determine what file type/group a file extension belongs.
	*	This will help you determine how to handle files. 
	*   When an extension is not mapped, it defaults to application type.
    *
    *   @var mixed
    */
	private $file_mimes = array(
		'.css'			=>	'text',
		'.txt'			=>	'text',
		'.htm'			=>	'text',
		'.html'			=>	'text',
		'.js'			=>	'text',
		'.gif'			=>	'image',
		'.jpg'			=>	'image',
		'.jpe'			=>	'image2',
		'.jpeg'			=>	'image',
		'.png'			=>	'image',
		'.tif'			=>	'image2',
		'.tiff'			=>	'image2',
		'.bmp'			=>	'image2',
		'.ico'			=>	'image2',
		'.mid'			=>	'audio',
		'.mpa'			=>	'audio',
		'.mp3'			=>	'audio',
		'.ra'			=>	'audio',
		'.ram'			=>	'audio',
		'.wav'			=>	'audio',
		'.mp2'			=>	'video',
		'.mpe'			=>	'video',
		'.mpg'			=>	'video',
		'.mpeg'			=>	'video',
		'.mov'			=>	'video',
		'.qt'			=>	'video',
		'.avi'			=>	'video',
		'.doc'			=>	'document',
		'.dot'			=>	'document',
		'.docx'			=>	'document',
		'.pdf'			=>	'document',
		'.exe'			=>	'application',
		'.class'		=>	'application',
		'.swf'			=>	'application',
		'.iii'			=>	'application',
		'.zip'			=>	'compressed',
		'.tar'			=>	'compressed',
		'.gtar'			=>	'compressed',
		'.gz'			=>	'compressed',
		'.tgz'			=>	'compressed',
		'.tar.gz'		=>	'compressed',
		'.jar'			=>	'java',
		'.sis'			=>	'symbian',
	);
	
 	/**
    *   The name of the file being uploaded.
    *
	*	This name will be used to upload the file. 
	*	However, this name can be changed when calling any of the upload methods.
    *   
	*	@var string
    */
	private $file_name;
	
 	/**
    *   The extension of the file being uploaded.
    *
    *   @var string
    */
	private $file_ext;
	
 	/**
    *   The type of the file being uploaded.
    *
	*	This is determind from the $file_mimes array
	*
	*	@see $file_mimes
    *   @var string
    */
	private $file_type;
	
 	/**
    *   The data of the file being uploaded.
    *
	*	This data is the same data populated in the $_FILES superglobal
	*
	*   @var mixed
    */
	private $file_resource;
	
	/**
    *   The maximum filesize allowed.
    *
	*	This can be set by the user when checking if a file is valid.
	*	The value is in bytes and must be less than or equal to the maximum upload limit that the system allows
	*	Defaults to the maximum allowed system upload size.  
	*
	*   @var int
    */
	private $max_size;
	
	/**
    *   The minimum filesize allowed.
    *
	*	This can be set by the user when checking if a file is valid.
	*	The value is in bytes and defaults to 1 byte.  
	*
	*   @var int
    */
	private $min_size;
	

	/**
    *   The directory into which the file is uploaded.
    *
	*	This can be set at any point in the script.
	*	The directory must be an existing directory and be writable
	*	Defaults to the directory in which the script is run,	
	*
	*	@see setUploadDir()
	*   @var string
    */
	private $upload_dir;
	
	/**
    *   The dubugging method to use.
    *
	*	Default is UPLOADER::ERROR.
	*	Can be set at instantiation or at any time within the script.
	*	See the class constants for more info.  
	*
	*	@see setErrorMode()
	*   @var int
    */
	private $debug;
	
	/**
    *   Stores the error.
    *
	*	This is an array of the error message and error code of errors that have occurred during execution.  
	*
	*	@see getErrors()
	*   @var mixed
    */
	private $errors = null;
	
	
	/**
    *   The class constructor.
    *
	*	Uses the current directory as default upload directory.
	*	Will die() if the upload directory is not writeable/ does not exist
	*
    *   @param string $upload_dir the upload directory
	*	@param int $error_level the debugging method. 
    */
	function __construct( $upload_dir = './', $error_level = SELF::ERROR )
	{
		$this->setErrorMode( $error_level );
		if ( !$this->setUploadDir( $upload_dir ) ) die( $this->errors['error'] );
	}

	/**
    *   Used to upload a file as a zipped file.
    *
	*	Uploads the file with .zip appended to the filename.
	*	You need to have zip support enabled. 
	*	Consult PclZip.php for more information
	*
	*	@see checkFile()
    *   @param string $filename optional parameter - the name of the file when uploaded
	*	@param string $upload_dir optional parameter - the directory the file should be uploaded into.
	*	@param bool $keep_original optional parameter - choose whether to keep the file uploaded as well as the zipped file. Defaults to false. 
	*	@return mixed false on failure or an array of uploaded filename and filepath if successful. If $keep_original is set to true two extra values of the original filename and filepath are included
    */
	public function uploadZipped(  $filename = '', $upload_dir = '', $keep_original = false )
	{
		require ('PclZip.php');
		
		if ( !empty($filename) ) $this->file_name = $filename;
		
		if ( !empty($upload_dir) )
		{ 
			if ( !$this->setUploadDir( $upload_dir ) ) return false;
		}
		
		if ( !empty($upload_dir) )
		{ 
			if ( !$this->setUploadDir( $upload_dir ) ) return false;
		}
		
		$zip_target = $this->upload_dir.$this->file_name.'.zip';
		$archive = new PclZip( $zip_target );
		
		$target = $this->upload_dir.$this->file_name;
		
		if ( !move_uploaded_file($this->file_resource['tmp_name'], $target) )
		{
			$this->errorHandler( array('File upload failed', 500) );
			return false;		
		}
		else 
		{
			$archive->create( $target );	
			$return = array( 'filename' => $this->file_name.'.zip', 'filepath' => $zip_target );
						
			if ( !$keep_original ) unlink( $target );
			else 
			{	
				$return['original_filename'] = $this->file_name;
				$return['original_filepath'] = $target;
			}
		
			return $return;
		}
	}
	
	
	/**
    *   Upload files normally.
    *
	*	GD support is neccessary is images are to be uploaded. Else, change all image values in the $file_mimes array to image2
	*	if file extension is mapped as an 'image'. As determined from the the $file_mimes array, getimagesize is used to determine if the file is a true image. This is determined by your GD support and therefore, you should read what files your version can obtain image sizes from else, you will lose legitimate files.
	*	If strict mode is enabled, files classed as 'image' are resized to remove exif(overkill since exif is only in tiff and jpeg files) data from them. Due to certain limitations. If file is not jpeg, png, gif or png, and is still classed as an 'image', then it is uploaded as a jpg file. It is advisable to make .tiff files 'image' as they also contain exif data.
	*	Images can also be uploaded resized as thumbnails by setting the required $thumbnail values. This uploads resized images, but maintains ratio irrespective of your input values in order to maintain image quality.
	*	
	*	@see checkFile()
	*   @param string $filename optional parameter - the name of the file when uploaded
	*	@param string $upload_dir optional parameter - the directory the file should be uploaded into.
    *   @param bool $strict will enable resizing of images to remove exif data irrespective of file type.
	*	@param mixed $thumbnail an array of values to be set if you wish to upload a file resized.
	*	@return mixed false on failure or an array of filename and filepath to uploaded file on success
    */
	public function uploadFile( $filename = '', $upload_dir = '', $strict = false, $thumbnail = array( 0 => false, 'width' => 64,'height' =>  64), $filetype = '' )
	{
		if ( !empty($filename) ) $this->file_name = $filename;
		
		if ( !empty($upload_dir) )
		{ 
			if ( !$this->setUploadDir( $upload_dir ) ) return false;
		}
		
		if ( !empty($filetype) ) $this->filetype = $filetype;
		
		$target = $this->upload_dir.$this->file_name;
		
		if ( !move_uploaded_file($this->file_resource['tmp_name'], $target) )
		{
			$this->errorHandler( array('File upload failed', 500) );
			return false;		
		}
		else
		{
			if ( 'image' == $this->file_type )
			{
				if ( !getimagesize($target) )
				{
					$this->errorHandler( array('Image failed integrity test.', 501) );
					unlink( $target );
					return false;	
				}
				else if ( $thumbnail[0] ) $target = $this->uploadResized($target, $thumbnail['width'], $thumbnail['height']);
				else if ( $strict ) 
				{
					list($width, $height) = getimagesize($target);
					$target = $this->uploadResized($target, $width, $height);
				}
			}
		}
		
		if ( !$target ) return false;
		else return array( 'filename' => $this->file_name, 'filepath' => $target );
	}
	
	/**
    *   Resize uploaded images.
    *
	*	Takes a file and resizes it. The original file is overwritten.
	*   If a file type is not supported, it uploads it as a jpeg.
	*	This is used for both uploading of image files in their original forms or as thumbnails.
	*
	*	@access private
	*   @param string $originalImage The image to be resized. Fullpath.
	*	@param int $toWidth The new image width.
    *   @param int $toHeight The new image height.
	*	@return mixed false on failure or the new fullpath to the file
    */
	private function uploadResized($originalImage, $toWidth, $toHeight)
	{	
		
		// Get the original geometry and calculate scales
		list($width, $height) = getimagesize($originalImage);
		$xscale=$width/$toWidth;
		$yscale=$height/$toHeight;
		
		// Recalculate new size with default ratio
		if ($yscale>$xscale){
			$new_width = round($width * (1/$yscale));
			$new_height = round($height * (1/$yscale));
		}
		else {
			$new_width = round($width * (1/$xscale));
			$new_height = round($height * (1/$xscale));
		}
	
		// Resize the original image
		$imageResized 	= imagecreatetruecolor($new_width, $new_height);
		$imageContents 	= file_get_contents($originalImage);
		$imageTmp     	= imagecreatefromstring ($imageContents);
		imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		switch ( $this->file_ext )
		{
			case '.png':
				if ( imagepng($imageResized, $originalImage, 9) ) 
				{
					imagedestroy( $imageResized ); 
					return $originalImage;
				}
				else return false;
			break;
			
			case '.jpeg':
			case '.jpg':
				if ( imagejpeg($imageResized, $originalImage, 100) ) 
				{
					imagedestroy( $imageResized ); 
					return $originalImage;
				}
				else return false;
			break;
			
			case '.gif':
				if ( imagejpeg($imageResized, $originalImage) )
				{
					imagedestroy( $imageResized ); 
					return $originalImage;
				}
				else return false;
			break;
			
			default:
				$this->file_name 	= str_replace( strrchr( $this->file_name, '.' ), '',  $this->file_name ).'.jpg';
				$originalImage2		= str_replace( strrchr( $originalImage, '.' ), '',  $originalImage ).'.jpg';
				
				if ( imagejpeg($imageResized, $originalImage2, 100) )	
				{
					imagedestroy( $imageResized );
					unlink( $originalImage );
					return $originalImage2;
				}
				else return false;
			break;
			
			
		}
	}
	
	/**
    *   Check if a file is valid according to user specifications.
    *
	*	Use this to ensure that a file is safe for upload before calling any of the upload methods
	*
	*	@see uploadFile()
	*	@see uploadZipped()
	*   @param string $input_name The name of the file input in the form.
	*	@param mixed $allowed_types An array of file extensions that are allowed.
    *   @param mixed $limits Size and Filename length limits that should be checked. Each option is optional.
	*	@return mixed false on failure or an array of the type, base(filename without extension), extension and filesize(in MB)
    */
	public function checkFile( $input_name, $allowed_types= array(), $limits = array('max_size' => '', 'min_size' => '', 'max_len' => '', 'min_len' => '') )
	{
		if ( !isset($_FILES[$input_name]) )
		{
			$this->errorHandler( array('No file selected. Check file input name', 200) );
			return false;
		}
		else $this->file_resource = $_FILES[$input_name];
			
		if ( empty($this->file_resource['name']) )
		{
			$this->errorHandler( array('Please select a file', 100) );
			return false;	
		}
		
		if ( !$this->setSizeLimits($limits) ) return false;
		
		if ( $this->file_resource['size'] > $this->max_size )
		{
			$this->errorHandler( array('Filesize exceeds maximum size limit', 101) );
			return false;	
		}
		
		if ( $this->file_resource['size'] < $this->min_size )
		{
			$this->errorHandler( array('Filesize is smaller than minimum upload limit', 102) );
			return false;	
		}
		
		$this->file_name = $this->_trim( basename($this->file_resource['name']) );
		
		if ( ( isset($limits['max_len']) && !empty($limits['max_len']) ) && $this->utf8_strlen($this->file_name) > $limits['max_len'] )
		{
			$this->errorHandler( array('Filename is too long', 103) );
			return false;	
		}
		
		if ( ( isset($limits['min_len']) && !empty($limits['min_len']) ) && $this->utf8_strlen($this->file_name) < $limits['min_len'] )
		{
			$this->errorHandler( array('Filename is too short', 104) );
			return false;	
		}
		
		$this->file_ext = strtolower( strrchr($this->file_name, '.') );
		
		if ( !in_array( $this->file_ext, $allowed_types) )
		{
			$this->errorHandler( array('File type is not allowed', 105) );
			return false;	
		}
		
		if ( isset($this->file_mimes[$this->file_ext]) ) $this->file_type = $this->file_mimes[$this->file_ext];
		else $this->file_type = 'application';
		
		$name_base = str_replace( $this->file_ext, '',  $this->file_name );

		return array (
						'name'	=> $this->file_name,
						'type'	=> $this->file_type,
						'base'	=> $name_base,
						'ext'	=> $this->file_ext,
						'size'	=> $this->getMegabytes($this->file_resource['size']), );
	}

	
	/**
    *   Get the length of the filename. This is method is multibyte safe.
    *
	*
	*   @param string $string The string to be checked.
	*	@return int the length of the string submitted
	*	@access private
    */
	private function utf8_strlen($string)
	{
		return strlen(utf8_decode($string));
	}
	
	
	/**
    *   Clean and trim the filename.
    *
	*	Attempts to remove null bytes and also trims the filename.
	*
	*   @param string $filename The string to be checked.
	*	@return string the cleaned filename
	*	@access private
    */
	private function _trim( $filename )
	{
		$filename = trim( $filename );
		$filename = str_replace( "U+0000", 	'', $filename );
		$filename = str_replace( "\uooo", 	'', $filename );
		$filename = str_replace( "\01", 	'', $filename );
		$filename = str_replace( "\000", 	'', $filename );
		$filename = str_replace( "\x00", 	'', $filename );
		$filename = str_replace( "\u000", 	'', $filename );
		$filename = str_replace( "\z", 		'', $filename );
		$filename = str_replace( "%00", 	'', $filename );
		$filename = str_replace( "^@", 		'', $filename );
		$filename = str_replace( chr(0), 	'', $filename );
		return $filename;	
	}

	
	/**
    *   Set the size limits to for the filesize to be checked against.
    *
	*   @param mixed $limits An array of user defined limits.
	*	@return bool
	*	@access private
    */
	private function setSizeLimits ( $limits )
	{
		if ( isset($limits['max_size']) && !empty($limits['max_size']) )
		{
			$sys_max = $this->getMaxSystemUploadSize();
				
			if ( $sys_max < $limits['max_size'] )
			{ 
				$this->errorHandler( array( 'Max upload size is greater than system allowed size', 201) );
				return false;
			}
			else $this->max_size = $this->getBytes( $limits['max_size'] );
		}
		else $this->max_size = $this->getBytes( $this->getMaxSystemUploadSize() );	
		
		if ( isset($options['min_size']) && !empty($options['min_size']) ) $this->min_size = $options['min_site'];
		else $this->min_size = 1;
		
		return true;
	}
	
	/**
    *   Set the debug method to be used. UPLOADER::SILENT(suppress all errors), UPLOADER::ERROR(echo friendly error messages), UPLOADER::EXCEPTION(throw exceptions on error).
	*
	*   @param int $error_level The debug method to be used.
	*/
	public function setErrorMode( $error_level )
	{
		$this->debug = $error_level;
	}
	
	
	/**
    *   Set the directory to which files should be uploaded.
	*
	*   @param string $directory The new directory.
	*	@return bool
    */
	public function setUploadDir( $directory )
	{ 
		if ( !file_exists( $directory ) )
		{
			$this->errorHandler( array('File directory not found', 202) );
			return false;
		}
		
		if ( !is_writable( $directory ) )
		{
			$this->errorHandler( array('File directory is not writable', 203) );
			return false;
		}
		
		$this->upload_dir = $directory;
		
		return true;
	}
		
	
	/**
    *   Get the maximum size which the system allows to be uploaded.
    *
	*	@return int
	*	@access private
    */private function getMaxSystemUploadSize( )
	{
		$upload 	= (int)ini_get( 'upload_max_filesize' );
		$post 		= (int)ini_get( 'post_max_size' );
		$memory 	= (int)ini_get( 'memory_limit' );	
		return min( $upload, $post, $memory );
	}
	
	
	/**
    *   Convert megabytes to bytes
    *
	*   @param int $megabytes The size in megabytes to be converted.
	*	@return int the converted size in bytes
	*	@access private
    */
	private function getBytes( $megabytes )
	{
		return ( (float)$megabytes ) * 1048576;	
	}
	
	/**
    *   Get errors
    *
	*	@return mixed an array containing the error message
    */
	function getError( )
	{
		return $this->errors;	
	}
	
	/**
    *   Convert bytes to megabytes
    *
	*   @param int $bytes The size in bytes to be converted.
	*	@return int the converted size in megabytes
	*	@access private
    */
	private function getMegabytes( $bytes )
	{
		return ( (float)$bytes ) / 1048576;	
	}
	
	
	/**
    *   Deal with errors depending on user's chosen debug method
    *
	*	Stores error message and error code in the $self->error array for later retrieval
	*	Depending on debug method, errors are displayes, suppressed or exceptions are thrown.	
	*
	*   @param mixed $error An array of the error message and error code.
	*	@access private
    */
	private function errorHandler( $error )
	{
		$msg 	= $error[0];
		$code 	= $error[1];
		
		switch ( $this->debug )
		{
			case ( self::SILENT ):
				$this->errors['error'] 	= $msg;
				$this->errors['code'] 	= $code;
				break;
			
			case ( self::ERROR ):
				$this->errors['error'] 	= $msg;
				$this->errors['code'] 	= $code;
				echo "Upload Error $code: $msg";
				break;
				
			case ( self::EXCEPTION ):
				$this->errors['error'] 	= $msg;
				$this->errors['code'] 	= $code;
				throw new Exception( "Upload Error $code: $msg" );
				break;
			
			default:
				$this->errors['error'] 	= $msg;
				$this->errors['code'] 	= $code;
				break;
		}	
	}
};
?>