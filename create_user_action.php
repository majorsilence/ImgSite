<?php
/*
* File: create_user_action.php
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

include("connection_info.php");

function create_user()
{
	if(empty($_POST['email']))
	{
		echo "Email is empty!";
		return false;
	}
	if(empty($_POST['password']))
	{
		echo "Password is empty!";
		return false;
	}
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);
	$password = hash("sha512", $password);
	
	$dbh = get_connection();
	try
	{
		$dbh->beginTransaction();

		
		$sql = "UPDATE Counters SET NextNum = NextNum + 1 where CounterType='Users';";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		
		$sql = "SELECT CounterType, NextNum FROM Counters where CounterType='Users';";
		$stmt = $dbh->prepare($sql);
		$stmt->execute();
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$nextNum = (int)$result[0]["NextNum"];
		
		$sql = "INSERT INTO USERS (Id, Email, Password) VALUES (:id, :email, :password);";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':id', $nextNum, PDO::PARAM_INT); 
		$stmt->bindParam(':email', $email, PDO::PARAM_STR); 
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		
		$dbh->commit();
	}
	catch (Exception $e) 
	{
	  $dbh->rollBack();
	  echo "Failed: " . $e->getMessage();
	}
	
}

create_user();
header( 'Location: index.php' ) ;
	
?>