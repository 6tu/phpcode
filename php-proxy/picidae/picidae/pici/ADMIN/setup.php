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

// -----------------------------------------------------
// initialize templates
// -----------------------------------------------------
//	require("db.php");
//	require("common_url.php");
	require ("../external/class.rFastTemplate.php");
	
	$tpl = new rFastTemplate("../TPL");
	$tpl -> define(array(
					"MAIN" => "setup.tpl"
					));
	
// --------------------------------------------------------------------------
// check whether the config file exists
// --------------------------------------------------------------------------
	
if (!file_exists ('config.php'))
{
	$message = '<div class="problem">
				<strong>You seem not to have installed the ADMIN/config.php file!</strong><br/>
				Change the filename ADMIN/sample.config.php to ADMIN/config.php,
				open the file in a text-editor and fill in the correct paths and variables for your installation.<br/>
				Afterwards you may call this file again.
				</div>';
}
else	
{
//	require("config.php");
	
	if (!check_configuration ());
	elseif (!check_db ());
	else
	{
		$message .= '<div class="success">
					 <strong>The setup process was successfully completed! </strong><br/>
					 your pici-server should be up and running.
					 </div>';
	}
						
}



	// -----------------------------------------------------
	// fill in the content
	// -----------------------------------------------------
	$tpl -> assign(array(	
						"message" => $message
						));


	
	$tpl->parse("MAIN", "MAIN");
	$tpl->FastPrint();

// -----------------------------------------------------
// setup helper functions
// -----------------------------------------------------
function check_configuration ()
{
	require("config.php");
	
	global $message;
	$return = true;
	
	// check whether paths are correctly set
	if (!file_exists ($path2site)) 
	{
			$message .= '<div class="problem">
						<strong>Incorrect path to pici-server</strong><br/>
						The path <small>' .$path2site
						.'</small> seems not to be correct.<br/>Edit your configuration-file and insert the correct path to your
						pici-server for the variable $path2site.
						</div>';
			$return = false;
	}
	// check whether the Server Variable was set
	elseif (!isset ($server)) 
	{
			$message .= '<div class="problem">
						<strong>$server variable is not set</strong><br/>
						Uncomment the line 53 or 54 in your configuration-file.<br/>
						Set the variable to "darwin" for Mac OSX and to "linux" for a Linux system.
						</div>';
			$return = false;
	}
	else 
	{
		if ($server=='darwin' && !file_exists ("$path2mkpicture/mkpicture.sh")) 
		{
			$message .= '<div class="problem">
						<strong>Incorrect path to the shellscript mkpicture.sh</strong><br/>
						The path <small>' .$path2mkpicture
						.'/mkpicture.sh</small> seems not to be correct.<br/>Edit your configuration-file and insert the correct path to your
						mkpicture.sh shellscript for the variable $path2mkscript.
						</div>';
			$return = false;
		}
		elseif ($server=='linux' && !file_exists ("$path2mkpicture/mkpicture_xvfb.sh"))
		{
			$message .= '<div class="problem">
						<strong>Incorrect path to the shellscript mkpicture_xvfb.sh</strong><br/>
						The path <small>' .$path2mkpicture
						.'/mkpicture_xvfb.sh</small> seems not to be correct.<br/>Edit your configuration-file and insert the correct path to your
						mkpicture_xvfb.sh shellscript for the variable $path2mkscript.
						</div>';
			$return = false;
		}
	
		// check whether CACHE exists and is writable for www
		if (!file_exists ($path2CACHE)) 
		{
			$message .= '<div class="problem">
						<strong>Incorrect path to CACHE</strong><br/>
						The path <small>' .$path2CACHE
						.'</small> seems not to be correct.<br/>Edit your configuration-file and insert the correct path to your
						CACHE folder for the variable $path2CACHE.
						</div>';
			$return = false;
		}
		elseif (!is_writable ($path2CACHE)) 
		{
			$message .= '<div class="problem">
						<strong>CACHE folder not writable</strong><br/>
						Your CACHE folder <small>' .$path2CACHE
						.'</small> seems not to be writable for the www user.<br/>
						Change file permissions<br/>
						via shell:
						<pre>chmod -R 777 '
						.$path2CACHE
						.'</pre>
						or via GUI:<br/> 
						right mouse click > Get Info: Change the "Ownership & Permissions" details 
						to "Read &amp; Write" for everybody or make your www-user the owner of this folder.
						</div>';
			$return = false;
		}
	}

	if ($server == 'darwin' && !file_exists ("$path2WebKit.exe")) 
	{
			$message .= '<div class="problem">
						<strong>Apple WebKit not found</strong><br/>
						The path <small>' .$path2WebKit
						.'</small> seems not to be correct.<br/>Download and install <a href="http://nightly.webkit.org/" target="_blank">Webkit</a>.
						If you already did, put it into your Applications folder, where it will be found, 
						or set up your custom path for $path2WebKit in your configuration file.
						</div>';
			$return = false;
	}

	if ($server == 'linux' && !file_exists ($path2web2pici)) 
	{
			$message .= '<div class="problem">
						<strong>web2pici not found</strong><br/>
						The path <small>' .$path2web2pici
						.'</small> seems not to be correct.<br/>
						Download and install web2pici as described in the installation instructions.<br/>
						<a href="http://dev.picidae.net/dev/wiki/PiciServerLinux">http://dev.picidae.net/dev/wiki/PiciServerLinux</a><br/>
						If you already did, put it into the ADMIN folder of your pici-server, where it will be found, 
						or set up your custom path for <strong>$path2web2pici</strong> in your configuration file.
						</div>';
			$return = false;
	}


	return $return;
}


// -----------------------------------------------------
function db_mk_tables (){

$form = "CREATE TABLE `form` (
  `idx` bigint(20) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `cipher_key` varchar(255) NOT NULL default '',
  `ref` varchar(255) NOT NULL default '',
  `formfields` varchar(255) NOT NULL default '',
  `hiddenfields` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  `created` double default NULL,
  PRIMARY KEY  (`idx`),
  KEY `created` (`created`),
  KEY `ref` (`ref`)
) ENGINE=MyISAM COMMENT='references  to the form-encryption' AUTO_INCREMENT=0;";

//----------------------------------------------------
$ref2url = "CREATE TABLE `ref2url` (
  `idx` bigint(20) NOT NULL auto_increment,
  `ref` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `created` double default NULL,
  `requested` int(11) default '0',
  `ip` varchar(250) NOT NULL default '',
  `idx_url` bigint(20) default NULL,
  PRIMARY KEY  (`idx`),
  KEY `ref` (`ref`),
  KEY `created` (`created`)
) ENGINE=MyISAM COMMENT='connects the link-ids with the urls' AUTO_INCREMENT=0 ;";

//----------------------------------------------------
$url2pic = "CREATE TABLE `url2pic` (
  `idx` bigint(20) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `pic` varchar(255) NOT NULL default '',
  `created` double default NULL,
  `loading` int(11) NOT NULL default '1',
  `displaynr` int(11) default NULL,
  PRIMARY KEY  (`idx`),
  KEY `url` (`url`),
  KEY `created` (`created`)
) ENGINE=MyISAM COMMENT='holds the references from the urls to the saved picture-name' AUTO_INCREMENT=0 ;";

require("config.php");

$link=mysql_pconnect($DB_host, $DB_user, $DB_pw);
if (mysql_query("CREATE DATABASE $DB",$link))
  {
  echo "Database created";
  }
else
  {
  $result = "Error creating database: " . mysql_error();
  }

mysql_select_db($DB, $link);

if (!db_table_exists ("form", $link)){
$result = mysql_query ($form, $link);
if (!$result) {
echo mysql_error();
}
}
if (!db_table_exists ("ref2url", $link)){
$result = mysql_query ($ref2url, $link);
if (!$result) {
die('Invalid query: ' . mysql_error());
}
}
if (!db_table_exists ("url2pic", $link)){
$result = mysql_query ($url2pic, $link);
if (!$result) {
die('Invalid query: ' . mysql_error());
}
}
return true;
}

//------------------------------------------------------
function check_db ()
{
	require("config.php");
	
	global $message;
	$return = true;

	// check whether database is connectable
	if (!($link=mysql_pconnect($DB_host, $DB_user, $DB_pw)))
	{
		$message .= '<div class="problem">
					 <strong>The setup program was unable to connect your database!</strong><br/>
					 check your configuration file ADMIN/config.php<br/>
					 and check whether mysql is running.
					 ';
					 
		$message .= sprintf("errormessage: <pre>error connecting to host %s, by user %s</pre><br/></div>", $DB_host, $DB_user);
		
		$return = false;
	}
	elseif (($link=mysql_pconnect($DB_host, $DB_user, $DB_pw)))
	{
		// link to the database
		if (!mysql_select_db($DB, $link))
		{
			db_mk_tables ($link);
			$return = true;
		}
		else 
		{
			db_mk_tables ($link);
			$return = true;
		}
	}

	return $return;
}


function db_table_exists ($table_name, $dblink)
{
	require("config.php");
	$return = false;
	
	//mysql_pconnect($DB_host, $DB_user, $DB_pw)
	$result = mysql_list_tables($DB);
	$num_rows = mysql_num_rows($result);
	for ($i = 0; $i < $num_rows; $i++) {
		if ($table_name == mysql_tablename($result, $i))
		{
			$return = true;
		}
	}

	mysql_free_result($result);

	return $return;
}



?>
