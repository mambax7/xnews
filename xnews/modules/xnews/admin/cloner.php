<?php
// $Id: cloner.php 8207 2011-11-07 04:18:27Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
/**
 * Module Cloner file
 *
 * Enable webmasters to clone the news module.
 *
 * NOTE : Please give credits if you copy this code !
 *
 * @package News
 * @author DNPROSSI
 * @copyright (c) DNPROSSI
 */

function nw_cloneFileFolder($path, $patterns)
{
	$patKeys = array_keys($patterns);
	$patValues = array_values($patterns);

	// work around for PHP < 5.0.x
	if(!function_exists('file_put_contents')) {
		function file_put_contents($filename, $data, $file_append = false) {
			$fp = fopen($filename, (!$file_append ? 'w+' : 'a+'));
			if ( !$fp ) {
				trigger_error('file_put_contents cannot write in file.', E_USER_ERROR);
				return;
			}
			fputs($fp, $data);
			fclose($fp);
		}
	}
    
    $newpath = str_replace($patKeys[0], $patValues[0], $path);
	
	if ( is_dir($path) )
	{
		// create new dir
		if ( !is_dir($newpath) ){ mkdir($newpath); };
		
		// check all files in dir, and process them
		if ( $handle = opendir($path) )
		{
			while ( $file = readdir($handle) )
			{
				if ( $file != '.' && $file != '..' )
				{
					nw_cloneFileFolder("$path/$file", $patterns);
				}
			}
			closedir($handle);
		}
	}
	else
	{
		//trigger_error('in else', E_USER_ERROR);
		if ( preg_match('/(.jpg|.gif|.png|.zip)$/i', $path) )
		{
			copy($path, $newpath);
			@chmod($newpath, 0755);
		}
		else
		{
			$path_info = pathinfo($path);
			$path_ext = $path_info['extension'];
			//trigger_error($path . " -------- " . $path_ext, E_USER_WARNING);
			
				//trigger_error($path , E_USER_WARNING);
				$content = file_get_contents($path);			
				if ( $path_ext != 'txt' ) 
				{
					for ( $i = 0; $i < sizeof($patterns); ++$i )
					{
						$content = str_replace($patKeys[$i], $patValues[$i], $content);   
					}
				}
				file_put_contents($newpath, $content);
				@chmod($newpath, 0755);
				//trigger_error($path. ' ---- ' .$newpath , E_USER_WARNING);
				
		}  
	}
}

//DNPROSSI
function nw_clonefilename($path, $old_subprefix, $new_subprefix)	
{
	for ($i = 0; $i <= 1; $i++) 
	{
		if ( $handle = opendir($path[$i]) )
		{
			while ( $file = readdir($handle) )
			{
				if ( $file != '.' && $file != '..' )
				{
					$newfilename = str_replace($old_subprefix, $new_subprefix, $file);
					@rename( $path[$i].$file, $path[$i].$newfilename );
				}				
			}
			closedir($handle);
		}
	}	
}

//DNPROSSI
function nw_deleteclonefile($path, $new_subprefix)	
{
	for ($i = 0; $i <= 1; $i++) 
	{
		if ( $handle = opendir($path[$i]) )
		{
			while ( $file = readdir($handle) )
			{
				if ( $file != '.' && $file != '..' )
				{   
					$pos = strpos($file, $new_subprefix);
					if ( $pos !== false )
					{
					   //trigger_error($file. ' ---- Deleted' , E_USER_WARNING);
					   @unlink( $path[$i].$file );

					}
				}				
			}
			closedir($handle);
		}
	}	
}

//DNPROSSI
function nw_clonecopyfile($srcpath, $destpath, $filename)	
{
	if ( $handle = opendir($srcpath) )
	{
		if ( $filename == '' ) 
		{
			while ( $file = readdir($handle) )
			{
				if ( $file != '.' && $file != '..' )
				{   
					@copy($srcpath.$file, $destpath.$file );
				}				
			}
		} else {
			if ( file_exists($srcpath.$filename) ) 
			{
				@copy($srcpath.$filename, $destpath.$filename);
			}	
	    }		
		closedir($handle);
	}	
}

// ------------ recursive PHP functions -------------
// recursive_remove_directory( directory to delete, empty )
// expects path to directory and optional TRUE / FALSE to empty
// of course PHP has to have the rights to delete the directory
// you specify and all files and folders inside the directory
// ------------------------------------------------------------

// to use this function to totally remove a directory, write:
// nw_removewholeclone('path/to/directory/to/delete');

// to use this function to empty a directory, write:
// nw_removewholeclone('path/to/full_directory',TRUE);

function nw_removewholeclone($directory, $empty=FALSE)
{
	// if the path has a slash at the end we remove it here
	if ( substr($directory, -1) == '/' ) {
		$directory = substr($directory, 0, -1);
	}
	
	// if the path is not valid or is not a directory ...
	if ( !file_exists($directory) || !is_dir($directory) ) {
		// ... we return false and exit the function
		return FALSE;
	// ... if the path is not readable
	} elseif ( !is_readable($directory) ) {
		// ... we return false and exit the function
		return FALSE;
		// ... else if the path is readable
	} else {
		// we open the directory
		$handle = opendir($directory);
		// and scan through the items inside
		while ( FALSE !== ($item = readdir($handle)) )
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if ( $item != '.' && $item != '..' )
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;
	
				// if the new path is a directory
				if ( is_dir($path) ) 
				{
					// we call this function with the new path
					nw_removewholeclone($path);
	
					// if the new path is a file
				} else {
					// we remove the file
					@unlink($path);
				}
			}
		}
		// close the directory
		closedir($handle);
	
		// if the option to empty is not set to true
		if ( $empty == FALSE )
		{
			// try to delete the now empty directory
			if ( !rmdir($directory) )
			{
				// return false if not possible
				return FALSE;
			}
		}
		// return success
		return TRUE;
	}
}
?>
