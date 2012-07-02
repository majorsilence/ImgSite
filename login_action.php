<?php
/*
* File: login_action.php
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

	function login()
	{
		if(empty($_POST['username']))
		{
			echo "UserName is empty!";
			return false;
		}
		if(empty($_POST['password']))
		{
			echo "Password is empty!";
			return false;
		}
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$password = hash("sha512", $password);
		
		$dbh = get_connection();
		
		$sql = "SELECT Id, Email FROM Users where Email=:email and Password=:password;";
		$stmt = $dbh->prepare($sql);
		$stmt->bindParam(':email', $username, PDO::PARAM_STR); 
		$stmt->bindParam(':password', $password, PDO::PARAM_STR);
		$stmt->execute();
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		session_start();
		if (count($result) > 0)
		{
			$user_db_id = $result[0]["Id"];
			$user_db_email = $result[0]["Email"];
			
			
			if($username == $user_db_email)
			{
				$_SESSION['LoggedInUser'] = "IamLoggedIn";
				$_SESSION['UserDbId'] = $user_db_id;
				$_SESSION['UserEmail'] = $user_db_email;
				
				// The echo success is required for the login.php to know everything worked
				echo "success";
				return true;
			}
		}
		$_SESSION['LoggedInUser'] = "notset";
		$_SESSION['UserDbId'] = "";
		$_SESSION['UserEmail'] = "";
		return false;
	
	}


	login();



	?>