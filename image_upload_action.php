<?php
include("connection_info.php");

function upload_image()
{
	

	if (is_valid_request() == false)
	{
		return "Failure to upload.  Not a valid request.  Make sure you are logged in";
	}
	
    $uploaded=0;
    
	session_start();
    
    $dbh = get_connection();
    $dbh->beginTransaction();
    try
    {
        foreach ($_FILES['userfile']['name'] as $i => $name) { 
            
            if ($_FILES['userfile']['error'][$i] > 0) { 
                continue;  
            } 

             if ($_FILES['userfile']['size'][$i] > 22000000) { 
                $message[] = "$name exceeded file limit."; 
                continue;   
             } 
             
             
            if (($_FILES["userfile"]["type"][$i] == "image/gif") 
                || ($_FILES["userfile"]["type"][$i] == "image/jpeg") 
                || ($_FILES["userfile"]["type"][$i] == "image/pjpeg") 
                || ($_FILES["userfile"]["type"][$i] == "image/png")
            )
            {
                $uploaddir = 'img/';
            

               
                
                
                $sql = "SELECT CounterType, NextNum FROM Counters where CounterType='Images';";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $nextNum = $result[0]["NextNum"];
                
                $sql = "UPDATE Counters SET NextNum = NextNum + 1 where CounterType='Images';";
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
                // Id INTEGER PRIMARY KEY DESC, UserId Integer, FileName
                $sql = "INSERT INTO UsersMedia (Id, UserId, FileName, PrivateMedia, UploadTime) " . 
                    "VALUES (:id, :userid, :filename, 0, datetime());";
                $stmt = $dbh->prepare($sql);
                
                 $uploadfile = $uploaddir . $nextNum . "-" . basename($_FILES['userfile']['name'][$i]);
                 
                $userid = (int)$_SESSION['UserDbId'];
                $stmt->bindParam(':id', $nextNum, PDO::PARAM_INT); 
                $stmt->bindParam(':userid', $userid, PDO::PARAM_INT); 
                $stmt->bindParam(':filename', $uploadfile, PDO::PARAM_STR);
                $stmt->execute();
                
                if (file_exists($uploadfile))
                {
                    return $_FILES["userfile"]["name"][$i] . " already exists. ";
                }
                else
                {
                    move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], $uploadfile);
                    $_SESSION['LastUpload'] = (string)$uploadfile;
                }
                
                
            }
             
             $uploaded++; 
         
         
        } 
        $dbh->commit();
    }
    catch (Exception $e) 
    {
        $dbh->rollBack();
        return "Failed: " . $e->getMessage();
    }


}

upload_image();

header( 'Location: image_upload.php' ) ;
	
?>