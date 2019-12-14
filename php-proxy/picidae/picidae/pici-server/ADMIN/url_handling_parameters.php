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
// variables and arrays
// --------------------------------------------------------------------------





$url_download = array (
								// prŠfix
								"^ftp://",
								"^sftp://",
								
								// suffix
								"\.zip$",
								"\.zip[?#]",
								"\.tar$",
								"\.tar[?#]",
								"\.sit$",
								"\.sit[?#]",
								"\.dmg$",
								"\.dmg[?#]",
								"\.gz$",
								"\.gz[?#]",
								"\.tgz$",
								"\.tgz[?#]",
								"\.exe$",
								"\.exe[?#]",

								"\.mov$",
								"\.mov[?#]",
								"\.mpeg$",
								"\.mpeg[?#]",
								"\.mpg$",
								"\.mpg[?#]",
								"\.wmv$",
								"\.wmv[?#]",
								"\.mp3$",
								"\.mp3[?#]",
								"\.ram$",
								"\.ram[?#]",
								"\.rm$",
								"\.rm[?#]",

								"\.pdf$",
								"\.pdf[?#]",
								"\.rtf$",
								"\.rtf[?#]",
								"\.doc$",
								"\.doc[?#]",
								"\.xls$",
								"\.xls[?#]",
								"\.ppt$",
								"\.ppt[?#]",
								"\.vcf$",
								"\.vcf[?#]"
								);
								
$url_email = array (
								"^mailto:"
								);
								
$url_allowed = array (
								"^http://",
								"^https://"
								);
								
$url_forbidden = array (
								// praefix
								"^file://",
								"^#",
								"^javascript",
								"^onclick",
								
								// in the content
								"127.0.0.1",
								"localhost",
								"pici.picidae.net",
								"picidae.local",
								"194.50.176.206"
								);

// ------------------------------------------------------------

$url_protect4python = array (
								" "=>"%20",
								"\\0" => "",
								";"=>"\;",
								"&" => "\&",
								"\%" => "%",
								"%25" => "%",
								"%3A" => ":",
								"%2F" => "/",
								"'" => "\'"
								);

$url_unprotect = array (
								"%25" => "%",
								"%3A" => ":",
								"%2F" => "/",
								"%3F" => "?",
								"%3D" => "=",
								"%26" => "&"
								);


/*
$url_char_protect = array (
								"&" => "\&" //"&" => "%26"
								);
*/
// ------------------------------------------------------------

$url_form_same = array (
								"^$",
								"^\?",
								"^#"
								);


?>
