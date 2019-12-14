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

// --------------------------------------------------------------------------
// variables
// --------------------------------------------------------------------------
$xml_form_parsed;
$xml_form_nr;
$xml_form_ref;
$xml_form_url;
$xml_form_fields;
$xml_form_hiddenfields;
$xml_form_tag;

$xml_form_action;

$xml_form_in_type;
$xml_form_in_name;
$xml_form_in_value;
$xml_form_in_size;
$xml_form_in_checked;

	


// --------------------------------------------------------------------------
// helper functions
// --------------------------------------------------------------------------
function xml_start_form($parser, $name, $attrs) 
{
	global $xml_form_parsed;
	global $xml_form_nr;
	global $xml_form_ref;
	global $xml_form_url;
	global $xml_form_fields;
	global $xml_form_hiddenfields;
	global $xml_form_tag;
	
	global $xml_form_action;
	global $xml_form_in_type;
	global $xml_form_in_name;
	global $xml_form_in_value;
	global $xml_form_in_size;
	global $xml_form_in_checked;
	global $xml_form_in_style;
	
	
	$xml_form_tag = $name;
	
	if ($name == "page")
	{
		;
	}
	elseif ($name == "form")
	{
		$xml_form_action = "";
	}
	elseif ($name == "input")
	{
		if (isset($attrs["type"])) $xml_form_in_type = $attrs['type'];
		else $xml_form_in_type = "";
		if (isset($attrs["name"])) $xml_form_in_name = $attrs['name'];
		else $xml_form_in_name = "";
		if (isset($attrs["checked"])) $xml_form_in_checked = $attrs["checked"];
		else $xml_form_in_checked = "";
		if (isset($attrs["style"])) $xml_form_in_style = $attrs['style'];
		//else $xml_form_in_style = "";
		if (isset($attrs["size"])) $xml_form_in_size = $attrs['size'];
		
		$xml_form_in_value = "";
		
/*
		if ($attrs["type"] == "hidden")
		{
			if (isset($attrs["name"]) && isset($attrs["value"])) $xml_form_hiddenfields .= "&" .$attrs["name"] ."=" .$attrs["value"];
		}
		// specialhandling
		elseif (isset($attrs["name"]) && $attrs["name"] == "btnI")
		{
			;
		}
		// default handling
		else
		{
			$xml_form_parsed .= "<input ";
			if (isset($attrs["type"])) 
			{	
				$xml_form_parsed .= "type=\"" .$attrs['type'] ."\" ";
				//$xml_form_parsed .= "type=\"" ."button" ."\" ";
				if ($attrs["type"] == "image") $xml_form_parsed .= "src=\"G/space.gif\" ";
			}
			if (isset($attrs["name"])) $xml_form_parsed .= "name=\"" .$attrs['name'] ."\" ";
			if (isset($attrs["value"])) $xml_form_parsed .= "value=\"" .$attrs['value'] ."\" ";
			if (isset($attrs["checked"])) $xml_form_parsed .= "checked=\"" .$attrs['checked'] ."\" ";
			if (isset($attrs["style"])) $xml_form_parsed .= "style=\"" .$attrs['style'] ."\" ";
			$xml_form_parsed .= "/>";
			
			if (isset($attrs["name"])) $xml_form_fields .= $attrs["name"] ."#";
		}
*/
	}
	elseif ($name == "textarea")
	{
		$xml_form_parsed .= "<textarea ";
		if (isset($attrs["name"])) $xml_form_parsed .= "name=\"" .$attrs['name'] ."\" ";
		if (isset($attrs["rows"])) $xml_form_parsed .= "rows=\"" .$attrs['rows'] ."\" ";
		if (isset($attrs["cols"])) $xml_form_parsed .= "cols=\"" .$attrs['cols'] ."\" ";
		if (isset($attrs["style"])) $xml_form_parsed .= "style=\"" .$attrs['style'] ."\" ";
		$xml_form_parsed .= ">";
	}
	elseif ($name == "select")
	{
		$xml_form_parsed .= "<select ";
		if (isset($attrs["name"])) $xml_form_parsed .= "name=\"" .$attrs['name'] ."\" ";
		if (isset($attrs["style"])) $xml_form_parsed .= "style=\"" .$attrs['style'] ."\" ";
		$xml_form_parsed .= ">";
	}
	elseif ($name == "option")
	{
		$xml_form_parsed .= "<option ";
		if (isset($attrs["value"])) $xml_form_parsed .= "value=\"" .$attrs['value'] ."\" ";
		$xml_form_parsed .= ">";
	}
	elseif ($name == "button")
	{
		$xml_form_parsed .= "<button ";
		if (isset($attrs["name"])) $xml_form_parsed .= "name=\"" .$attrs['name'] ."\" ";
		if (isset($attrs["type"])) $xml_form_parsed .= "type=\"" .$attrs['type'] ."\" ";
		if (isset($attrs["value"])) $xml_form_parsed .= "value=\"" .$attrs['value'] ."\" ";
		if (isset($attrs["style"])) $xml_form_parsed .= "style=\"" .$attrs['style'] ."\" ";
		$xml_form_parsed .= "/>";
	}

}


function xml_end_form($parser, $name) 
{
	global $xml_form_ref;
	global $xml_form_nr;
	global $xml_form_fields;
	global $xml_form_hiddenfields;
	global $xml_form_parsed;
	global $xml_form_url;
	global $xml_form_tag;

	global $xml_form_action;
	
	global $xml_form_in_type;
	global $xml_form_in_name;
	global $xml_form_in_value;
	global $xml_form_in_size;
	global $xml_form_in_checked;
	global $xml_form_in_style;
	
	$xml_form_tag = "";
	
	if ($name == "forms") ;
	elseif ($name == "page")
	{
		;
	}
	elseif ($name == "action")
	{
		$url = "";
		$xml_form_fields = "";
		$xml_form_hiddenfields = "";
		
		$xml_form_parsed .= "<form ";
		if ($xml_form_action && $xml_form_action != "") 
		{
			$url = action2url ($xml_form_url, $xml_form_action);
		}
		
		$xml_form_parsed .= "action=\"browse.php\" ";
		$xml_form_parsed .= "method=\"get\" ";
		$xml_form_parsed .= ">";
		
		// get reference
		$key = "8bytekey";
		$xml_form_ref = form2db($url, $xml_form_nr, $key, "form");
		
		// reference and scrambling-test
		$xml_form_parsed .= "<input type=\"hidden\" name=\"f\" value=\"$xml_form_ref\" class=\"n\" />";
		$xml_form_parsed .= "<input type=\"hidden\" name=\"c\" value=\"0\" class=\"n\" />";
		
		$xml_form_nr++;
	}
	elseif ($name == "input")
	{
		if ($xml_form_in_type == "hidden")
		{
			if ($xml_form_in_name && $xml_form_in_value != "") $xml_form_hiddenfields .= "&" .$xml_form_in_name ."=" .$xml_form_in_value;
		}
		// specialhandling
		elseif (isset($xml_form_in_name) && $xml_form_in_name == "btnI")
		{
			;
		}
		// default handling
		else
		{
			$xml_form_parsed .= "<input ";
			if (isset($xml_form_in_type)) 
			{	
				if ($xml_form_in_type != '') $xml_form_parsed .= "type=\"" .$xml_form_in_type ."\" ";
				if ($xml_form_in_type == "image") $xml_form_parsed .= "src=\"G/space.gif\" ";
			}
			if (isset($xml_form_in_name) && $xml_form_in_name != "") $xml_form_parsed .= "name=\"$xml_form_in_name\" ";
			if (isset($xml_form_in_value) && $xml_form_in_value != "") $xml_form_parsed .= "value=\"" .urldecode($xml_form_in_value) ."\" ";
			if (isset($xml_form_in_checked) && $xml_form_in_checked != "") $xml_form_parsed .= "checked=\"" .$xml_form_in_checked ."\" ";
			if (isset($xml_form_in_style)) $xml_form_parsed .= "style=\"" .$xml_form_in_style ."\" ";
			$xml_form_parsed .= "/>";
			
			if (isset($xml_form_in_name) && $xml_form_in_style != "") $xml_form_fields .= $xml_form_in_name ."#";
		}
	}
	elseif ($name == "form") 
	{
		// write reference into db
		form2db_update ($xml_form_ref, $xml_form_fields, $xml_form_hiddenfields, "form");
		$xml_form_parsed .= "</$name>";
	}
	else $xml_form_parsed .= "</$name>";
}


function xml_text_form($parser, $data) 
{
	global $xml_form_tag;
	global $xml_form_url;
	global $xml_form_parsed;
	
	global $xml_form_action;
	global $xml_form_in_value;
	
	if ($xml_form_tag == "page") 
	{
		$xml_form_url = $data;
	}
	elseif ($xml_form_tag == "action")
	{
		if ($data && $data != "") 
		{
			$xml_form_action = $data;
		}
	}
	elseif ($xml_form_tag == "input")
	{
		$xml_form_in_value = $data;
	}
}


// --------------------------------------------------------------------------
// function
// --------------------------------------------------------------------------

function parse_form ($filename, $DB_link, $DB_table)		
{
	global $xml_form_parsed;
	global $xml_form_nr;
	global $debug;
	global $path2CACHE;
	
	// get file
	$file = "$path2CACHE/$filename.form.xml";
	$xml_form_nr = 0;
	
	// parse mapfile
	if (file_exists($file))
	{
		$xml_parser = xml_parser_create();
		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_element_handler($xml_parser, "xml_start_form", "xml_end_form");
		xml_set_character_data_handler($xml_parser, "xml_text_form");
		if (!($fp = fopen($file, "r"))) 
		{
			if ($debug) return "file $file is not to open";
			else return " ";
		}
/*
		$data = fread($fp, filesize($file));
		fclose($fp);
		xml_parse($xml_parser, $data);
*/

		while ($data = fread($fp, 4096)) 
		{
			//if (!xml_parse($xml_parser, $data)) 
			if (!xml_parse($xml_parser, $data, feof($fp))) 
			{
				if ($debug) return "form parse error";
				else return " ";
			}
		}



		xml_parser_free($xml_parser);

		return $xml_form_parsed;		
	}
	else 
	{
		if ($debug) return "file $file does not exist";
		else return " ";
	}
}



?>
