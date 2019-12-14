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
// helpers
// --------------------------------------------------------------------------

function eregiArray ($Array, $string)
{
	// loop through the array
	for ($i=0; $i<count($Array); $i++)
	{
		if (eregi($Array[$i],$string)) return true;
	}
	return false;
}


// --------------------------------------------------------------------------
// functions
// --------------------------------------------------------------------------
/*
function url_optimize ($url)
{
	require ("url_handling_parameters.php");
	
	$url = trim($url);
	
	//protect problematic ascii-chars
	$url = strtr($url, $url_char_protect);
	
	return $url;
}
*/

// --------------------------------------------------------------------------
function url_protect4python ($url)
{
	require ("url_handling_parameters.php");
	
	$url = trim ($url);
	$url = addslashes($url);
	$url = strtr($url, $url_protect4python);
		
	return $url;
}


// --------------------------------------------------------------------------
// check the url for legitimity
function url_analyze ($url)
{
	require ("url_handling_parameters.php");

	// return-values:
	// 0 = url is not allowed, unable to handle
	// 1 = url is ok -> make picture
	// 2 = url is email
	// 3 = url is downloadable file

	
	// allowed?
	if (eregiArray($url_forbidden,$url)) return 0;
	// email
	if (eregiArray($url_email,$url)) return 2; 
	// downloadable files
	elseif (eregiArray($url_download,$url)) return 3;
	// allowed url
	elseif (eregiArray($url_allowed,$url)) return 1;
	
	else return 0;
}

// --------------------------------------------------------------------------
// check wether url is an allowed link at all
function url_allowed ($url)
{
	require ("url_handling_parameters.php");
	
	if (eregiArray($url_forbidden,$url)) return false;
	else return true;
}

// --------------------------------------------------------------------------
// 
function action2url ($url, $action)
{
	require ("url_handling_parameters.php");
	

	// is the action absolute?
	if (eregiArray($url_allowed,$action))
	{
		;
	}
	// does the action start at root
	elseif (eregi("^/",$action))
	{
		// add the site-domain-path
		$action  = eregi_replace ('([a-zA-Z]+://[^/?#]+).*','\\1',$url) .$action;
	}
	// no url at all
	elseif (eregiArray($url_form_same,$action))
	{
		$action = $url;
	}
	// otherwise relative url
	else
	{
		// add the path to the current web-directory
		$action  = eregi_replace ('([^?#]+/).*','\\1',$url) .$action;

	}
	
	
	// remove trailing search-strings & anchors
	$action = eregi_replace ('([^?#]*).*','\\1',$action);
	
	
	// check wether its now a valid url or not
	if (!eregiArray($url_forbidden,$action) && eregiArray($url_allowed,$action))
	{
		return $action;
	}
	else return false;
	
}

// --------------------------------------------------------------------------
// 
function relative2absoluteURL ($pageurl, $url)
{
	require ("url_handling_parameters.php");
	

	// is the url already absolute?
	if (eregi("^.{2,6}://", $url)) ;
	elseif (eregi("^javascript:", $url)) ;
	// does the url start at root
	elseif (eregi("^/",$url))
	{
		// add the site-domain-path
		$url  = eregi_replace ('([a-zA-Z]+://[^/?#]+).*','\\1',$pageurl) .$url;
	}
	// no url at all
	elseif ($url == '' || eregiArray(array('^$','^ '),$url))
	{
		$url = $pageurl;
	}
	// a querystring
	elseif (eregiArray(array('^\?','^#'),$url))
	{
		$url = eregi_replace ('([^?#]+).*','\\1',$pageurl) .$url;
	}
	// otherwise relative url
	else
	{
		// add the path to the current web-directory
		$url  = eregi_replace ('([^?#]+/).*','\\1',$pageurl) .$url;

	}
	
	return $url;
}


?>
