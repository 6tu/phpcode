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
	require("ADMIN/config.php");
	require("ADMIN/db_functions.php");
	require("ADMIN/common.php");

	require ("external/class.rFastTemplate.php");
	require ("ADMIN/url2ref.php");
	
	$tpl = new rFastTemplate("TPL");

	$tpl -> define(array(
					"MAIN" => "index.tpl",
					"JAVASCRIPT" => "js.tpl",
					"HOME" => "home.tpl"
					));
	

	// --------------------------------------------------------------------------
	// connect 2 DB
	// --------------------------------------------------------------------------
	$DB_link = connect2db();
	
	
	// --------------------------------------------------------------------------
	// delete old connections
	// --------------------------------------------------------------------------
	require ("ADMIN/cron.php");
	cron ($DB_link);
	
	// --------------------------------------------------------------------------
	// shall javascript-encoding be used?
	if (!isset($no_js)) $no_js = false;

	// -----------------------------------------------------
	// do what there is to do
	// -----------------------------------------------------
	$key = get_key (8);
	$ref = form2db ("", 1, $key, "form");
	
	
	// -----------------------------------------------------
	// read javascript
	// -----------------------------------------------------
	if (!$no_js)
	{
		$tpl -> assign(array(
						"key" => $key
						));
		
		$tpl->parse("javascript", "JAVASCRIPT");
	}
	else
	{
		$tpl -> assign(array(
						"javascript" => ""
						));
	}

	// -----------------------------------------------------
	// fill in the content
	// -----------------------------------------------------
	$tpl -> assign(array(	
						"ref" => $ref,
						"random_title" => ""
						));
						
	
	if (isset($_REQUEST['id'])) $tpl->parse("HOME", "HOME");
	else $tpl->parse("MAIN", "MAIN");
	$tpl->FastPrint();


?>
