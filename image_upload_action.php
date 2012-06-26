<?php
include("connection_info.php");


	if ((($_FILES["userfile"]["type"] == "image/gif") 
		|| ($_FILES["userfile"]["type"] == "image/jpeg") 
		|| ($_FILES["userfile"]["type"] == "image/pjpeg") 
		|| ($_FILES["userfile"]["type"] == "image/png")
		)
		&& ($_FILES["userfile"]["size"] < 20000000))
	{
	
		if ($_FILES["userfile"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["userfile"]["error"] . "<br />";
		}
		else
		{
			echo "Upload: " . $_FILES["userfile"]["name"] . "<br />";
			echo "Type: " . $_FILES["userfile"]["type"] . "<br />";
			echo "Size: " . ($_FILES["userfile"]["size"] / 1024) . " Kb<br />";
			echo "Temp file: " . $_FILES["userfile"]["tmp_name"] . "<br />";
			
			// $uploaddir generally should be the full path
			$uploaddir = 'img/';


			$dbh = get_connection();
			try
			{
				$dbh->beginTransaction();
				$sql = "SELECT CounterType, NextNum FROM Counters where CounterType='Images';";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();
				
				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$nextNum = $result[0]["NextNum"];
				
				$sql = "UPDATE Counters SET NextNum = NextNum + 1 where CounterType='Images';";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();
				
				
				$dbh->commit();
				$uploadfile = $uploaddir . $nextNum . "-" . basename($_FILES['userfile']['name']);
		
				if (file_exists($uploadfile))
				{
					echo $_FILES["userfile"]["name"] . " already exists. ";
				}
				else
				{
					move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile);
				}
				
				
			}
			catch (Exception $e) 
			{
			  $dbh->rollBack();
			  echo "Failed: " . $e->getMessage();
			}
		}
	}
	else
	{
	echo "Type: " . $_FILES["userfile"]["type"] . "<br />";
		echo "Invalid file";
	}
?>