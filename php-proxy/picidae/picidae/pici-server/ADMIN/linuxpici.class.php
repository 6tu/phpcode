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

// class linuxpici.class.php manages the creation of the picture.

class linuxpici
{
  var $pici_process;
  var $web2pici_id;
  var $handle;
  var $DB_table;
  var $DB_link;
  var $idx;

  function linuxpici($DB_link, $DB_table)
  {
   return $this->__construct($DB_link, $DB_table); // forward php4 to __construct
  }

  function __construct($DB_link, $DB_table)
  {
    register_shutdown_function(array(&$this, "cleanup"));

	global $path2CACHE;
	global $debug;
	
	$filename = "$path2CACHE/debug_log.txt";
	if ($debug) $this->handle = fopen($filename, "a+");
	
	$this->DB_table = $DB_table;
	$this->DB_link = $DB_link;
	
    return true;
  }
  
  function create_picture ($idx, $url, $displaynr, $imgnr)
  {
	global $path2site;
	global $path2web2pici;
	global $path2mkpicture;
	global $path2CACHE;
	global $pici_user;
	global $homefolder;
	global $debug;

	$this->idx = $idx;

	if ($debug) fwrite  ( $this->handle  , "-------------------------------------------------- \n");


	$addStmt = "UPDATE `$this->DB_table` set `loading` = '1', `displaynr` = '$displaynr' where `pic` = '$idx';";
	if ($debug) fwrite  ( $this->handle  , "$addStmt \n");
	if (!mysql_query(sprintf($addStmt), $this->DB_link)) if ($debug) fwrite  ( $this->handle  , "success \n");


	$descriptorspec = array(
	   0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
	   1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
	   2 => array("pipe", "w")
	);

	$cwd = NULL; // current working directory
	$env = array(   'HOME' => $homefolder,
			'path2web2pici' => $path2web2pici,
			'DISPLAY' => "localhost:$displaynr.0",
			'url' => $url,
			'imgnr' => $imgnr
			);

$xvfb = 'xvfb-run --server-args="-screen 0, 1200x5050x24" ';
$cmd = $xvfb.$path2web2pici.' --url='.$url.' --out='."$path2CACHE/$idx.png > $path2CACHE/error_.txt 2>&1 &";
//echo $cmd;




	$this->pici_process = proc_open(
                        $cmd,
			$descriptorspec, 
			$pipes, 
			$cwd, 
			$env
			);



@chmod ("$path2CACHE/$idx.png",755);


	if (is_resource($this->pici_process)) 
	{
	    sleep (1);
	    
	    $this->web2pici_id = $this->_find_bg_web2pici ($idx);

	    if ($this->web2pici_id > 0)
	    {
		$this->_create_loop ();
	    }
	    else if ($debug) fwrite ( $this->handle  , "no web2pici_id found, no loop created \n");
	}
	else if ($debug) fwrite  ( $this->handle  , "is not a process \n");


  }
  
  function _create_loop ()
  {
	for ($i=0; $i<40; $i++)
	{
		// is program still running
		if ($this->_web2pici_id_exists())
		{
			;
		}
		else
		{
			break;
		}

		sleep (1);
	}
  }

  function _web2pici_id_exists ()
  {
	// does web2pici still exist?
	$ps_out = shell_exec ("ps -p " .$this->web2pici_id);
	$ps_array = explode("\n", $ps_out);
	if (isset($ps_array[1]) AND $ps_array[1]) return true;
	else return false;
  }


  function _find_bg_web2pici ($session)
  {
	global $debug;

	// find web2pici process in the background
	$ps_out = shell_exec ("ps -C web2pici -o pid,cmd -w -w");
	$ps_array = explode("\n", $ps_out);

	if ($debug) fwrite  ( $this->handle , "ps_out: $ps_out \n");

	if ($ps_array[1]>0) 
	{
		foreach ($ps_array as $my_ps)
		{
			$pattern = "^[ ]*([0-9]+) .*/($session)";

			if (eregi ($pattern, $my_ps, $regs))
			{
				if ($debug) fwrite  ( $this->handle , "found '$pattern' in '$my_ps' \n");
				if ($debug) fwrite  ( $this->handle , "my session: " .$regs[2] ."; ");
				if ($debug) fwrite  ( $this->handle , "web2pici_id: " .$regs[1] ." \n");

				$this->web2pici_id = $regs[1];
				return $this->web2pici_id;
			}
			else if ($debug) fwrite  ( $this->handle , "could not find '$pattern' in '$my_ps' \n");
		}
	}
	
	return false;
  }

  function cleanup ()
  {
	global $debug;
	
	$addStmt = "UPDATE `$this->DB_table` set loading = '2' where pic = '" .$this->idx ."';";
	mysql_query(sprintf($addStmt), $this->DB_link);

	
	// does web2pici still exist?
	if ($this->_web2pici_id_exists ()) 
	{
		if ($debug) fwrite ( $this->handle  , "cleanup: kill web2pici process \n");

		// kill web2pici process
		$message = shell_exec ("/usr/bin/sudo kill " .$this->web2pici_id);
		if ($debug) fwrite ( $this->handle  , "cleanup: message: /usr/bin/sudo kill " .$this->web2pici_id ." \n");
		if ($debug) fwrite ( $this->handle  , "cleanup: message: $message \n");
	}
	else if ($debug) fwrite ( $this->handle  , "cleanup: no web2pici process found \n");


	return true;
  }


  function __destruct()
  {
    // finish what we where doing;
    return true;
  }

}

?>

