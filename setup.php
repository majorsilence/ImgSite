<?php

include("connection_info.php");

error_reporting(E_ALL);

unlink("example-sqlite.sdb");

$dbh = get_connection();
$stmt = $dbh->prepare('PRAGMA foreign_keys = ON;');
$stmt->execute();

// Create Users table ***************************************************************
$sql = 'CREATE TABLE Users ' .
	'(Id INTEGER PRIMARY KEY DESC, Email TEXT, Password TEXT, UNIQUE (Email));'
	;

$stmt = $dbh->prepare($sql);
$stmt->execute();

$password = hash("sha512", "password");
$password2 = hash("sha512", "password2");

$sql = "INSERT INTO Users (Id, Email, Password) VALUES (1, 'testing@majorsilence.com', '" .  $password . "');";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$sql = "INSERT INTO Users (Id, Email, Password) VALUES (2, 'testing2@majorsilence.com', '" .  $password2 . "');";
$stmt = $dbh->prepare($sql);
$stmt->execute();


$sqlSelect = "SELECT * FROM Users;";
$stmtSelect = $dbh->prepare($sqlSelect);
$stmtSelect->execute();
$result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

header('Content-type: application/json');
// Notice that it is Products with an S
echo json_encode($result);
	
// End Users table ******************************************************************


// Create a table to track all files inserted into table
// PrivateMedia is used to keep track if the image is private (0) or public (1)
$sql = 'CREATE TABLE UsersMedia ' .
	'(Id INTEGER PRIMARY KEY DESC, UserId INTEGER, FileName TEXT, PrivateMedia BOOLEAN, UploadTime DATE,
		FOREIGN KEY(UserId) REFERENCES Users(Id));'
	;

$stmt = $dbh->prepare($sql);
$stmt->execute();



$sql = "CREATE TABLE Counters (CounterType TEXT PRIMARY KEY DESC, NextNum INTEGER);";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$sql = "INSERT INTO Counters (CounterType, NextNum) VALUES ('Images', 0);";
$stmt = $dbh->prepare($sql);
$stmt->execute();


?>