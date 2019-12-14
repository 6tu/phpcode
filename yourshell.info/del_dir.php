del_dir() is written to be used outside of the Class. Here is the coding:Code: 
/* del_dir() 
 * 
 *   Empty supplied dir + all sub-directories + (by default) remove all 
 * 
 *   Based on php.net/manual/en/function.rmdir.php, 
 *+  eli dot hen at gmail dot com, 25-Jan-2007 03:54 (bugfixed) 
 * 
 * @param string   $dir        dir to remove, no end slash 
 * @param boolean  $only_empty just empty $dir without deleting 
 * @return boolean TRUE/FALSE  $dir was emptied/deleted 
 * @access public 
 * 
 * 2008-03-24 added:  DIRECTORY_SEPARATOR to attempt Windows compatibility 
 *+           change: no longer assumes that `$dir' is also $CWD (required for opendir()). 
 *+           bugfix: is_dir() on symbolic links will report target, not link. 
 *+           bugfix: added correct `!== FALSE' check on readdir(). 
 * Note 1: Use of `$only_empty' requires PHP5 due to use of scandir(). 
 * Note 2: PHP4 strips any trailing slash with realpath(), PHP5 leaves it in place. 
 * Note 3: Permission or filesystem errors during opendir() or chdir() will generate E_WARNING errors. 
 *+        These can be suppressed with `@' prepended to function. 
 * Note 4: is_dir() always returns false if the handle from opendir() is NOT from $CWD. 
 *+        see: php.net/manual/en/function.is-dir.php 
 *+             alan dot rezende at light dot com dot br 29-Sep-2006 01:42 
 * Note 5: File/Dir permissions: essentially, PHP can only remove files/dirs that it created. 
 * Note 6: Windows problems: from experience, it is v common for another process to lock a file/dir, 
 *+        which will prevent deletion. 
 */ 
      function del_dir( 
         $dir, 
         $only_empty   = FALSE 
      ) { 
         $CWD      = getcwd(); 
         if( chdir( $dir ) == FALSE ) return FALSE; 

         $dscan   = array( realpath( $dir )); 
         $darr      = array(); 
         while( !empty( $dscan )) { 
            $dcur      = array_pop( $dscan ); 
            $darr[]   = $dcur; 
            if( $d = opendir( $dcur )) { 
               while(( $f = readdir( $d )) !== FALSE ) { 
                  if(( $f == '.' ) or ( $f == '..' ))         continue; 
                  $f   = $dcur . DIRECTORY_SEPARATOR . $f; 

                  if( is_dir( $f ) and ( !is_link( $f )))   $dscan[]   = $f; 
                  else                                       unlink( $f ); 
               } 
               closedir( $d ); 
            } 
         } 

         $i_until   = ( $only_empty ) ? 1 : 0; 
         for( $i = count( $darr ) - 1; $i >= $i_until; $i-- ) { 
            // echo "\nDeleting '".$darr[$i]."' ... "; 
            rmdir( $darr[ $i ]); 
         } 

         $result   = ( $only_empty ) ? ( count( scandir ) <= 2 ) : ( !is_dir( $dir )); 
         chdir( $CWD ); 

         return $result; 
      }   // p7z::del_dir() 
