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
// helper functions
// --------------------------------------------------------------------------



// --------------------------------------------------------------------------
// include files & functions
// --------------------------------------------------------------------------
require("ADMIN/config.php");
require("ADMIN/db_functions.php");
require("ADMIN/common.php");


require("ADMIN/ref2url.php");
require("ADMIN/get_picture.php");
require("ADMIN/parse_mapfile.php");
require("ADMIN/parse_form.php");

require("ADMIN/display_site.php");

require_once ("ADMIN/url_handling_parameters.php");
require_once ("external/des.php");

// --------------------------------------------------------------------------
// connect 2 DB
// --------------------------------------------------------------------------
$DB_link = connect2db();


// --------------------------------------------------------------------------
// shall javascript-encoding be used?
if (!isset($no_js)) $no_js = false;


// --------------------------------------------------------------------------
if (isset($_REQUEST["f"]))
{
	// make url 
	$url_array = formref2url ($_REQUEST["f"], $DB_link, "form");
	
	// check whether it is the first 
	if ((strlen($url_array["url"])<4)) 
	{
		if (isset($_REQUEST["c"]) && $_REQUEST["c"] == 1) 
		{
			$key = $url_array["key"];
			unset ($url_array);
			
			$tmp = pici_decrypt ($key, $_REQUEST["u"]);
			
			$url_array["url"] = strtr($tmp, $url_unprotect);
		}
		else 
		{
			unset ($url_array);
			$url_array["url"] = $_REQUEST["u"];

			/*
			// encode special chars (if not already encoded)
			if (!strstr($_REQUEST["u"], '%'))
			{
				$url_array["url"] = urlencode($_REQUEST["u"]);
				echo ($url_array["url"] ."<br/>");
			}
			else
			{
				$url_array["url"] = $_REQUEST["u"];
			}
			*/
		}
		
	}
	
	// TODO how to work with post-form-variables?
}
elseif (isset($_REQUEST["r"]))
{
	$url_array = ref2url($_REQUEST["r"], $DB_link, "ref2url");
}
elseif (isset($_REQUEST["c"]) && $_REQUEST["c"] == 1) 
{
	// get key 
	$key = "8bytekey";
	//$key = "this is a 24 byte key !!";

	$url_array["url"] = pici_decrypt ($key, $_REQUEST["u"]);
}
elseif (isset($_REQUEST["u"])) 
{
	$url_array["url"] = $_REQUEST["u"];
}


// checking the database for an earlier connection
// writing the request into the database
$imgnr = get_picture ($url_array, $DB_link, "url2pic");
//echo ("<div style=\"position:absolute;z-index:200;\">" .$url_array["url"] ."</div>");

if (!$imgnr)
{
	echo ("<h1>An Error Occured</h1>");
	//echo ($result);
}
else
{
	$imgid = "CACHE/$imgnr";
	$filename = "CACHE/$imgnr.xml";
	
	$maps = parse_mapfile ($imgnr, $DB_link, "ref2url");
	$form = parse_form ($imgnr, $DB_link, "ref2url");
	
	display_site ($imgid, $maps, $form, $no_js, "8bytekey");
}

?>