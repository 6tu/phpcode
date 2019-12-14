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


// --------------------------------------------------------------------------
// variables
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// helper functions
// --------------------------------------------------------------------------
function make_ref($url, $nr)		
{
	$temp = $nr .microtime() .$url;
	$ref = md5($temp);
	return $ref;
}


// --------------------------------------------------------------------------
// function
// --------------------------------------------------------------------------

function url2ref($url, $nr, $DB_table)		
{
	global $DB_link;
	
	// create a reference for the 
	$ref = make_ref($url, $nr);
	
	// make a new entry into the Database
	$addStmt = "Insert into $DB_table(ref, url, ip, created) values('%s','%s','%s','%f');"; 
	
	if (!mysql_query(sprintf($addStmt, $ref, $url, $_SERVER['REMOTE_ADDR'], ((float) get_mysql_timestamp (0))), $DB_link)) 
	{
		return false;
	}
	else
	{
		return $ref;
	}
}


// --------------------------------------------------------------------------
function form2db($url, $nr, $key, $DB_table)
{
	global $DB_link;
	
	// create a reference for the 
	$ref = make_ref($url, $nr);
	
	// make a new entry into the Database
	$addStmt = "Insert into $DB_table(url, cipher_key, ref, ip, created) values('%s','%s','%s','%s','%f');"; 
	
	if (!mysql_query(sprintf($addStmt, $url, $key, $ref, $_SERVER['REMOTE_ADDR'], ((float) get_mysql_timestamp (0))), $DB_link)) 
	{
		return false;
	}
	else
	{
		return $ref;
	}
}


function form2db_update($ref, $formfields, $hiddenfields, $DB_table)		
{
	global $DB_link;
	
	// make a new entry into the Database
	$addStmt1 = "UPDATE $DB_table SET formfields = '%s' WHERE ref = '%s';"; 
	$addStmt2 = "UPDATE $DB_table SET hiddenfields = '%s' WHERE ref = '%s';"; 
	
	if (!mysql_query(sprintf($addStmt1,$formfields,$ref), $DB_link)) 
	{
		return false;
	}
	else
	{
		if (!mysql_query(sprintf($addStmt2,$hiddenfields,$ref), $DB_link)) 
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}


?>