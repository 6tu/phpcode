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
// this function is called from time to time and takes all actions a cronjob
//  it should:
// - delete expired files
// - remove old stuff from the database.
// ==========================================================================
require("ADMIN/db_functions.php");
$DB_link = connect2db();
echo cron ($DB_link);
echo get_mysql_timestamp ($timelimit_links); 

function cron ($DB_link)
{
	global $timelimit_links;
	global $timelimit_cache;
	
	
	// check db for old links
	// delete the old links directly
	$numrows = 0;
	$timestamp = get_mysql_timestamp ($timelimit_links); // mysql-timestamp - caching-time
	echo $timestamp;
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