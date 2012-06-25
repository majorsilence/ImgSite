<?php

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

		session_start();
		if($username == 'demo' && $password == 'demo')
		{
			$_SESSION['LoggedInUser'] = "IamLoggedIn";
			echo "success";
			
			return true;
		}
		$_SESSION['LoggedInUser'] = "notset";
		return false;
	
	}


	login();



	?>