<?php
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
			$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
			
			if (file_exists($uploadfile))
			{
				echo $_FILES["userfile"]["name"] . " already exists. ";
			}
			else
			{

				move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile);
				echo "Stored in: " . $uploadfile;
			}
		}
	}
	else
	{
	echo "Type: " . $_FILES["userfile"]["type"] . "<br />";
		echo "Invalid file";
	}
?>