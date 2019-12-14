<?php

/* --------------------------------------------------------------------------

pici-server of the artproject picidae http://www.picidae.net
Copyright (c) 2007  picidae.net by christoph wachter and mathias jud

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

 -------------------------------------------------------------------------- */


// ==========================================================================


require_once ("url_handling.php");

// --------------------------------------------------------------------------
// helpers
// --------------------------------------------------------------------------


function str2img($str,$file)
{
	$fontpath = 'external/fonts/Vera.ttf';
	$fontsize = 10;
	$width = 700;
	$height = 300;
	
	//$img = imagecreate($width, $height);
	$img = imagecreatefrompng($file);

	$black = imagecolorallocate($img,0,0,0);
	$grey = imagecolorallocate($img,128,128,128);
	$white = imagecolorallocate($img,255,255,255);

	//imagefilledrectangle($img, 0, 0, $width, $height, $white);
	imagettftext($img, $fontsize, 0, 15, 190, $black, $fontpath, $str);

	$result = imagepng($img,$file);
	imagedestroy($img);
	
	return $result;
}



function compress_img($idx, $compression)
{
	// compression goes from 0-9
	// 0 = no compression
	// 9 = highest compression
	
	$imgid = "CACHE/$idx";
	
	if (file_exists ($file))
	{
		$img = imagecreatefrompng($file);
		$result = imagepng ($img, $imgid .".$compression.png", $compression);
		imagedestroy($img); 
		return $result;
	}
	else
	{
		return false;
	}
}


function check_gd ()
{
	if (extension_loaded('gd')) return true;
	else return false;
}


function make_xml_files ($imgid)
{
	// creating and emptiing the xml-file
	// mapfile
	$filename = $imgid .".xml";
	$handle = fopen($filename, "w+");
	fclose($handle);	
	chmod($filename, 0666);
	
	// formfile
	$filename = $imgid .".form.xml";
	$handle = fopen($filename, "w+");
	fclose($handle);	
	chmod($filename, 0666);
}


function checkDisplayNr ($DB_link, $DB_table)
{
	require ("config.php");

	// check for a free display number display
	$qryStmt = "SELECT `idx`,`displaynr`,`created`  from $DB_table where loading = 1;";
	if ($exists_query=mysql_query(sprintf($qryStmt), $DB_link))
	{
		$numrows = mysql_num_rows($exists_query);
	}
	else return $display_nr;

	// check for how many are open	
	if ($numrows >= $display_sessions)
	{
		for ($i=0; $i<$numrows; $i++)
		{
			$timestamp = get_mysql_timestamp (100);
			if (mysql_result($exists_query, $i, "created") < $timestamp )
			{
				$idx = mysql_result($exists_query, $i, "idx");
				$addStmt = "UPDATE `$DB_table` set loading = '-1' where idx = '$idx';"; 
				mysql_query(sprintf($addStmt), $DB_link);
			}
		}

		if ($exists_query=mysql_query(sprintf($qryStmt), $DB_link))
		{
			$numrows = mysql_num_rows($exists_query);
		}
	}
		
	// search for an empty session
	for ($i=0; $i<$display_sessions; $i++)
	{
		$available = true;
		$tmpdisplay = $i +$display_nr;
		for ($j=0; $j<$numrows; $j++)
		{
			if (mysql_result($exists_query, $j, "displaynr") == $tmpdisplay)
			{
				$available = false;
			}
		}
		
		// return free display number
		if ($available) return $tmpdisplay;
	}
	return false;
}


// --------------------------------------------------------------------------
// functions
// --------------------------------------------------------------------------

function web2png ($idx, $url, $DB_link, $DB_table)
{
	require ("config.php");
		
	$imgid = "$path2CACHE/$idx";
	$filename = "$path2CACHE/$idx.xml";
	
	
	// creating and emptiing the xml-file
	make_xml_files ($imgid);
	

	if ($server=='darwin')
	{
		// write loading into the database
		$addStmt = "UPDATE `$DB_table` set loading = '1' where pic = '$idx';"; 
		mysql_query(sprintf($addStmt), $DB_link);
		
		if ($debug) echo ("/usr/bin/sudo -u $pici_user $path2mkpicture/mkpicture.sh \"$url\" \"$idx\" \"$path2site\" \"$path2python\" \"$path2WebKit\" \"$path2CACHE\"  <br/>\n"); 
		$shell = shell_exec ("/usr/bin/sudo -u $pici_user $path2mkpicture/mkpicture.sh $url \"$idx\" \"$path2site\" \"$path2python\" \"$path2WebKit\" \"$path2CACHE\" ");

		if ($debug) echo ("shell = $shell <br/>\n");
	}
	else
	{
		for ($i=0;$i<60;)
		{
			$displaynr = checkDisplayNr ($DB_link, $DB_table);

			if ($displaynr)
			{
				require_once ("linuxpici.class.php");
				$my_linuxpici = new linuxpici ($DB_link, $DB_table);
				$my_linuxpici->create_picture ($idx, $url, $displaynr, NULL);
//				$my_linuxpici->create_picture ($idx, $url, $displaynr, $idx);
				break;			
			}
			sleep (1);
			$i++;
		}
	}

	return true;
}

// --------------------------------------------------------------------------
function message2picture ($idx, $url, $code)
{
	// code-values:
	// -1 = not reachable
	// 0 = url is not allowed, unable to handle
	// 2 = url is email
	// 3 = url is downloadable file
	
	global $path2CACHE;

	$imgid = "$path2CACHE/$idx";
	$file = "$path2CACHE/$idx.png";
	
	// create and empty xml-files
	make_xml_files ($imgid);
	
	// check wether gd is installed
	if (check_gd ())
	{
		if ($code == 2)
		{			
			// string without mailto:
			$message = substr($url, 7);
			copy("G/error_email.png",$file);
		}
		elseif ($code == 3)
		{
			$message = $url;
			copy("G/error_download.png",$file);
		}
		elseif ($code == -1)
		{
			$message = $url;
			copy("G/error_notresponding.png",$file);
		}
		else
		{
			$message = $url;
			$result = copy("G/error_unhandled.png",$file);
		}
	
		$result = str2img($message,$file);
	}
	else 
	{
		// simply copy the images into the directory
		if ($code == 2)
		{
			$result = copy("G/error_email.png",$file);
		}
		elseif ($code == 3)
		{
			$result = copy("G/error_download.png",$file);
		}
		elseif ($code == -1)
		{
			$result = copy("G/error_notresponding.png",$file);
		}
		else
		{
			$result = copy("G/error_unhandled.png",$file);
		}
	}
	
	return $result;
}

// --------------------------------------------------------------------------

function make_picture ($ref, $url, $DB_link, $DB_table)
{
	require ("config.php");
	
	// make reference
	if (!$ref) 
	{
		// create reference
		$ref = make_ref ($url, "");
		
		// make a new entry into the Database
		$addStmt = "Insert into $DB_table(url, pic, loading, created) values('%s','%s','%s','%f');"; 
	
		if (!mysql_query(sprintf($addStmt, Schuetzen($url), $ref, 3, ((float) get_mysql_timestamp (0))), $DB_link)) 
		{
			return false;
		}

		// check for idx
		$qryStmt = "SELECT `idx` from $DB_table where pic = '$ref';";
		
		if ($exists_query=mysql_query(sprintf($qryStmt), $DB_link)) 
		{
			//wieviele resultate
			$numrows = mysql_num_rows($exists_query);
		}
		
		if ($numrows > 0)
		{
			$idx = DemaskString(mysql_result($exists_query, 0, "idx"));
		}
		else return false;
	}
	
	// analyze url
	$analyzation = url_analyze ($url);

	// create picture
	if ($analyzation == 1) 
	{
		// protect white spaces
		$url = url_protect4python ($url);

		web2png ($ref, $url, $DB_link, $DB_table);
		
		// wait until the picture is generated
		if (!file_exists("$path2CACHE/$ref.png")) sleep (1);

		if (!file_exists("$path2CACHE/$ref.png")) 
		{
			if ($debug) echo ("Image $path2CACHE/$ref.png was not generated <br/>\n");
			$result = message2picture ($ref, $url, -1);
		}
		else 
		{
			if ($debug) echo ("Image $path2CACHE/$ref.png was generated <br/>\n");
			$result = true;
		}
	}
	else
	{
		if ($debug) echo ("analyzation of URL $url failed <br/>\n");
		$result = message2picture ($ref, $url, $analyzation);
	}
	
	// display an errormessage otherwise
	if (!$result)
	{
		// write error into the database
		$addStmt = "UPDATE `$DB_table` set loading = '0' where idx = '$idx';"; 
	
		if (!mysql_query(sprintf($addStmt), $DB_link)) 
		{
			return false;
		}		
		
		return false;
	}
	else
	{
		// write loaded into the database
		$addStmt = "UPDATE `$DB_table` set loading = '2' where idx = '$idx';"; 
	
		if (!mysql_query(sprintf($addStmt), $DB_link)) 
		{
			;
		}
		
		return $ref;
	}
	
}


// --------------------------------------------------------------------------

function get_picture ($url_array, $DB_link, $DB_table)
{
	// is picture recent enough?
	if (isset($url_array["url_idx"]))
	{
		$qryStmt = "SELECT * from $DB_table where idx = '" .$url_array['url_idx'] ."';"; 
	}
	else
	{
		$temp = Schuetzen($url_array['url']);
		$qryStmt = "SELECT * from $DB_table where url = '" .$temp ."';"; 
	}
	
	
	// check DB
	if ($exists_query = mysql_query($qryStmt, $DB_link)) 
	{
		$numrows = mysql_num_rows($exists_query);
	}
	

	// check whether image exists
	if ($numrows > 0)
	{
		//check whether image is already created:
		if (mysql_result($exists_query, 0, "created")==0) 
		{
			//@@@ check whether image exists
			$result = DemaskString(mysql_result($exists_query, 0, "pic"));
		}
		elseif (mysql_result($exists_query, 0, "created")==2) 
		{
			// last time there was an error
			//@@@ check whether image exists
			$result = DemaskString(mysql_result($exists_query, 0, "pic"));
		}
		else 
		{
			//@@@ enhancement
			// loop for max. 30 seconds
			// check each second whether image is created

			sleep(1);
			$result = DemaskString(mysql_result($exists_query, 0, "pic"));						
		}		
	}
	
	$numrows = 0;
	// check whether recent enough
	if ($numrows > 0)
	{
		$result = DemaskString(mysql_result($exists_query, 0, "pic"));
	}
	else 
	{

		$result = make_picture (false, $url_array["url"], $DB_link, $DB_table);
	}
	
	return $result;
}


?>
