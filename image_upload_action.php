<?php
include("connection_info.php");

function upload_image()
{
	

	if (is_valid_request() == false)
	{
		return "Failure to upload.  Not a valid request.  Make sure you are logged in";
	}
	
	session_start();

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
				// Id INTEGER PRIMARY KEY DESC, UserId Integer, FileName
				$sql = "INSERT INTO UsersMedia (Id, UserId, FileName) VALUES (:id, :userid, :filename);";
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(':id', $nextNum, PDO::PARAM_INT); 
				$stmt->execute();
				
				$dbh->commit();
				$uploadfile = $uploaddir . $nextNum . "-" . basename($_FILES['userfile']['name']);
		
				if (file_exists($uploadfile))
				{
					return $_FILES["userfile"]["name"] . " already exists. ";
				}
				else
				{
					move_uploaded_file($_FILES["userfile"]["tmp_name"], $uploadfile);
					$_SESSION['LastUpload'] = (string)$uploadfile;
					return "file uploaded";
				}
				
				
			}
			catch (Exception $e) 
			{
			  $dbh->rollBack();
			  return "Failed: " . $e->getMessage();
			}
		}
	}
	else
	{
		return "Type: " . $_FILES["userfile"]["type"] . "<br /> Invalid file";
	}
}

$msg = upload_image();
header( 'Location: image_upload.php' ) ;
	
?>