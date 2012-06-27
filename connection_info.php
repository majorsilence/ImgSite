<?php
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
	session_start();

	if($_SESSION['UserDbId'] != "" and $_SESSION['UserEmail'] != "" and $_SESSION['LoggedInUser'] == "IamLoggedIn")
	{
		if (is_numeric($_SESSION['UserDbId']))
		{
			return true;
		}
	}

	return false;
}

?>