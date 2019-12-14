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
//   连接数据库的任务
// ==========================================================================
function connect2db(){
	global $DB_host;
	global $DB_user;
	global $DB_pw;
	global $DB;
	
	//create link 2 database
	if (!($link=mysql_pconnect($DB_host, $DB_user, $DB_pw))) {
		DisplayErrMsg(sprintf("error connecting to host %s, by user %s", $DB_host, $DB_user));
		exit();
	}

	
	//select database
	if (!mysql_select_db($DB, $link)) {
		DisplayErrMsg(sprintf("Error in selecting %s database", $DB));
		DisplayErrMsg(sprintf("error:%d %s", mysql_errno($link), mysql_error($link)));
		$message = '<div class="problem">
					<strong>The setup program was unable to find your database!</strong><br/>
					check your configuration file ADMIN/config.php whether you have set the correct database name<br/>
					and check <a href="ADMIN/setup.php" target="_blank">ADMIN/setup.php </a>whether you created that database within mysql.<br/>
					 <br/>Then call this file again.</div>';
		echo $message;
		exit();
	}
	
	return $link;
}

// ==========================================================================
// this function is called from time to time and takes all actions a cronjob
//  it should:
// - delete expired files
// - remove old stuff from the database.
//   更新文件和数据库的 任务
// ==========================================================================


function cron ($DB_link)
{
	global $timelimit_links;
	global $timelimit_cache;
	
	
	// check db for old links
	// delete the old links directly
	$numrows = 0;
	$timestamp = get_mysql_timestamp ($timelimit_links); // mysql-timestamp - caching-time
	$qryStmt = "DELETE FROM `ref2url` WHERE created < $timestamp;";
	
	mysql_query(sprintf($qryStmt), $DB_link);
	
	// -------------------------------------------------------------
	// check db for old forms
	// delete the old forms directly
	$numrows = 0;
	$qryStmt = "DELETE FROM `form` WHERE created < $timestamp;";
	
	mysql_query(sprintf($qryStmt), $DB_link);

	
	// -------------------------------------------------------------
	// check db for old pictures
	$timestamp = get_mysql_timestamp ($timelimit_cache);
	$qryStmt = "SELECT * from `url2pic` WHERE created < $timestamp;";


	if ($exists_query=mysql_query(sprintf($qryStmt), $DB_link)) 
	{
		//wieviele resultate
		$numrows = mysql_num_rows($exists_query);
	}
	
	// loop through results and delete images as well as db-entries	
	for ( $i=0; $i < $numrows; $i++)
	{
		$pic = DemaskString(mysql_result($exists_query, $i, "pic"));
		$imgid = "CACHE/$pic";
		
		// remove xml files
		if (file_exists($imgid .".xml")) unlink ($imgid .".xml");
		if (file_exists($imgid .".form.xml")) unlink ($imgid .".form.xml");
		
		// remove picture files
		if (file_exists($imgid .".png")) unlink ($imgid .".png");
	}

	// delete the images
	$qryStmt = "DELETE FROM `url2pic` WHERE created < $timestamp;";
	mysql_query(sprintf($qryStmt), $DB_link);
	
	
	// TODO place 0 into db for all not loadable pages.
}


?>