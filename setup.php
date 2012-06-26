<?php

include("connection_info.php");

error_reporting(E_ALL);

unlink("example-sqlite.sdb");

$dbh = get_connection();

// Create Users table ***************************************************************
$sql = 'CREATE TABLE Users ' .
	'(Id INTEGER, Email TEXT);'
	;

$stmt = $dbh->prepare($sql);
$stmt->execute();

$sql = "INSERT INTO Users (Id, Email) VALUES (1, 'testing@majorsilence.com');";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$sql = "INSERT INTO Users (Id, Email) VALUES (2, 'testing2@majorsilence.com');";
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

$sql = "CREATE TABLE Counters (CounterType TEXT, NextNum INTEGER);";
$stmt = $dbh->prepare($sql);
$stmt->execute();

$sql = "INSERT INTO Counters (CounterType, NextNum) VALUES ('Images', 0);";
$stmt = $dbh->prepare($sql);
$stmt->execute();


?>