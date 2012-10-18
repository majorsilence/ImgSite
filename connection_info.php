<?php
/*
* File: connection_info.php
* Author: Peter Gill
* Copyright: 2012 Peter Gill
* Link: https://github.com/majorsilence/ImgSite
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

error_reporting(E_ALL);

function get_connection()
{

	// List of PDO drivers:  http://www.php.net/manual/en/pdo.drivers.php
	
	// mysql dsn setup
	/*
	$username = 'password';
	$password = 'username';
	$database = 'databaseName';
	$host = 'host=ipAddressOrWebAddressOfServer';

	$dsn = 'mysql:dbname=' . $database . ';host='. $host;
	
	$dbh = new PDO($dsn, $username, $password, array(PDO::ATTR_PERSISTENT => true)); // connection pooling
	return $dbh;
	*/
	
	// SQLite
	//$dbh = new PDO("sqlite:/Full/Path/To/example-sqlite.sdb");
	$dbh = new PDO("sqlite:example-sqlite.sdb");
	$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	return $dbh;
}

function is_valid_request()
{
    if(session_id() == '') {
        session_start();    // session isn't started
    }

	if(isset($_SESSION['UserDbId']) and isset($_SESSION['UserEmail']) and isset($_SESSION['LoggedInUser']))
	{
		if (is_numeric($_SESSION['UserDbId']))
		{
			return true;
		}
	}

	return false;
}

function site_menu()
{
	return '<div id="site_menu" style="float: left;">' . 
            '<a href="index.php">Home</a> <br />' .
            '<a href="image_upload.php">Upload Images</a> <br /> ' .
            '<a href="browse_images.php">Browse Images</a> <br /> ' .
            '<div id="login-div" style="width:75px; float: left;">' .
            '</div>' .
        '</div>' .
        '<script  type=\'text/javascript\'>' .
        '  $("#login-div").load("login.php");' .
        '</script>';
}

function site_header_info()
{
    return '<link rel="stylesheet" href="styles/style.css" type="text/css"/>';
}



?>