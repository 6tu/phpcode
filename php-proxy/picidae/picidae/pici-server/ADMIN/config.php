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
// Start Configuration
// ==========================================================================

// --------------------------------------------------------------------------
// Paths to Libraries and Files
// --------------------------------------------------------------------------
$path2site = "/opt/lampp/htdocs/pici-server";  // path to your pici-server


// --------------------------------------------------------------------------
// Database
// --------------------------------------------------------------------------
$DB = "picidae";        // name of your database
$DB_user = "root";          // name of your database user
$DB_pw = "";            // your database password
$DB_host = "localhost"; // most probably you don't have to change this


// --------------------------------------------------------------------------
// your user that creates the pictures
// --------------------------------------------------------------------------
$pici_user = "root";


// --------------------------------------------------------------------------
// which system are you using?
// --------------------------------------------------------------------------
//$server = "darwin";      // uncomment this for mac OSX
$server = "linux";       // uncomment this for linux server


// ==========================================================================
// only needed for mac OSX
// --------------------------------------------------------------------------
$path2python = "python";                       // path to your python location
                                               // most probably you dont have 
                                               // to change this value


// ==========================================================================
// only needed for linux-version
// --------------------------------------------------------------------------
$display_sessions = 1;  // write here the number of Xvfb display sessions 
			// that you are running in parallel.
			// this is the number of pictures that can be produced
			// in parallel. 
			// (the other users have to wait until a display gets free)

$display_nr = 10;	// the number of your first display (eg. 10)
			// number

// set up for every display session an own Xvfb display by typing the following
// into your terminal. (':10' means display nr. 10)
// 
//  Xvfb :10 -auth $path2site/ADMIN/xvfb.conf -screen 0 1200x5050x24 &
//

// ==========================================================================
// End Configuration !!!
// 
// your done! 
// the following does not need to be changed.
// ==========================================================================

// --------------------------------------------------------------------------
// show debugging messages
// --------------------------------------------------------------------------
$debug = false;

// --------------------------------------------------------------------------
// Paths to locations & scripts
// --------------------------------------------------------------------------
$path2CACHE = "$path2site/CACHE";
$path2mkpicture = "$path2site/ADMIN";


// --------------------------------------------------------------------------
// Database table-names
// --------------------------------------------------------------------------
$DB_ref2url = "ref2url";
$DB_url2pic = "url2pic";


// --------------------------------------------------------------------------
// Time-Limits
// --------------------------------------------------------------------------
// expiration of the reference
$timelimit_links = 3000; // The link-references will expire after that time 
                         // timeformat: YYYYMMddhhmmss
                         // default: 30 minutes

// cache of pictures
$timelimit_cache = 20000;// The pages will be rerendered after that time. 
                         // timeformat: YYYYMMddhhmmss
                         // default: 2 hours


// --------------------------------------------------------------------------
// common global variables
// --------------------------------------------------------------------------
// nothing to do here...
$DB_link; //link to database
$DB_query; //link to database-query


// ==========================================================================
// only needed for mac OSX
// --------------------------------------------------------------------------
$path2WebKit = "/Applications/WebKit";


// ==========================================================================
// only needed for linux-version
// --------------------------------------------------------------------------
$homefolder = "/$pici_user";
$path2web2pici = "$path2site/ADMIN/web2pici";

?>
