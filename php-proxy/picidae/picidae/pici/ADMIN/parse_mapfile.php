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
$xml_map_parsed;
$xml_map_linknr;
$xml_map_element;
$xml_map_shape;
$xml_map_coords;
$xml_map_alt;
$xml_map_href;
$xml_map_url;


// --------------------------------------------------------------------------
// helper functions
// --------------------------------------------------------------------------
function xml_start_map($parser, $name, $attrs) 
{
	global $xml_map_parsed;
	global $xml_map_element;
	global $xml_map_shape;
	global $xml_map_coords;
	global $xml_map_alt;
	global $xml_map_href;
	global $xml_map_url;
	
	$xml_map_element = $name;
	
	if ($name == "area")
	{
		// emty the elements
		$xml_map_shape = "";
		$xml_map_coords = "";
		$xml_map_alt = "";
		$xml_map_href = "";
		
		// refill them
		if ($attrs["shape"]) $xml_map_shape = $attrs['shape'];
		if ($attrs["coords"]) $xml_map_coords = $attrs['coords'];
		if ($attrs["alt"]) $xml_map_alt = $attrs['alt'];
				
	}
	elseif ($name == "map")
	{
		$xml_map_url = '';
		$xml_map_parsed .= "<map ";
		if ($attrs["name"]) $xml_map_parsed .= "name=\"" .$attrs['name'] ."\" ";
		$xml_map_parsed .= ">";
	}
}


function xml_end_map($parser, $name) 
{
	global $xml_map_parsed;
	global $xml_map_element;
	global $xml_map_shape;
	global $xml_map_coords;
	global $xml_map_alt;
	global $xml_map_href;
	global $xml_map_linknr;
	global $xml_map_url;

	if ($name == "area" && $xml_map_parsed && $xml_map_href != "")
	{
		$xml_map_parsed .= "<area ";
		$xml_map_parsed .= "shape=\"" .$xml_map_shape ."\" ";
		
		$xml_map_href = relative2absoluteURL ($xml_map_url, $xml_map_href);
		
		$ref = url2ref($xml_map_href, $xml_map_linknr, "ref2url");
		if ($ref) $xml_map_parsed .= "href=\"browse.php?r=$ref\" ";
		$xml_map_linknr++;
		
		$xml_map_parsed .= "coords=\"" .$xml_map_coords ."\" ";
		$xml_map_parsed .= "alt=\"" .$xml_map_alt ."\" ";
		$xml_map_parsed .= "/>";
	}
	elseif ($name == "area") ;
	elseif ($name == "page") ;
	else $xml_map_parsed .= "</$name>";
	
	$xml_map_element = "";
}


function xml_text_map($parser, $data) 
{
	global $xml_map_parsed;
	global $xml_map_element;
	global $xml_map_href;
	global $xml_map_url;
	
	if ($xml_map_element == "area")
	{
		$xml_map_href = $data;
	}
	elseif ($xml_map_element == "page") 
	{
		$xml_map_url = $data;
	}
	else $xml_map_parsed .= $data;
}


// --------------------------------------------------------------------------
// function
// --------------------------------------------------------------------------

function parse_mapfile($filename, $DB_link, $DB_table)		
{
	global $xml_map_parsed;
	global $xml_map_linknr;
	global $debug;
	global $path2CACHE;
	global $server;
	
	// get file
	$file = "$path2CACHE/$filename.xml";
	$xml_map_linknr = 0;
	
	// parse mapfile
	if (file_exists($file))
	{
		$xml_parser = xml_parser_create('UTF-8');
		//$xml_parser = xml_parser_create();

		xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_element_handler($xml_parser, "xml_start_map", "xml_end_map");
		xml_set_character_data_handler($xml_parser, "xml_text_map");
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

		while ($data = utf8_encode(fread($fp, 4096))) 
		{
			//if (!xml_parse($xml_parser, $data)) 
			if (!xml_parse($xml_parser, $data, feof($fp))) 
			{
				if ($debug)
				{
					$message = "mapfile parse error \n";
					$message .= "error code: " .xml_get_error_code ($xml_parser) ."\n";
					$message .= "error string: " .xml_error_string (xml_get_error_code ($xml_parser)) ."\n";
					$message .= "line nr: " .xml_get_current_line_number ($xml_parser) ."\n";
					$message .= "column code: " .xml_get_current_column_number ($xml_parser) ."\n";
					return $message;
				}
				else return " ";
			}
		}



		xml_parser_free($xml_parser);

		return $xml_map_parsed;		
	}
	else 
		return " ";
		//return "file $file does not exist";
}



?>
