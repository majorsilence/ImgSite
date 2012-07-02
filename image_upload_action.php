<?php
/*
* File: image_upload_action.php
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
include("simpleimage.php");

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
                // Id INTEGER PRIMARY KEY DESC, UserId Integer, FileNameOrig, FileNameView, FileNamePreview
                $sql = "INSERT INTO UsersMedia (Id, UserId, FileNameOrig, FileNameView, FileNamePreview, PrivateMedia, UploadTime) " . 
                    "VALUES (:id, :userid, :filenameorig, :filenameview, :filenamepreview, 0, datetime());";
                $stmt = $dbh->prepare($sql);
                
                $uploadfile_orig = $uploaddir . $nextNum . "-" . basename($_FILES['userfile']['name'][$i]);
                $uploadfile_preview = $uploaddir . $nextNum . "-preview-" . basename($_FILES['userfile']['name'][$i]);
                $uploadfile_view = $uploaddir . $nextNum . "-view-" . basename($_FILES['userfile']['name'][$i]);
                
                $userid = (int)$_SESSION['UserDbId'];
                $stmt->bindParam(':id', $nextNum, PDO::PARAM_INT); 
                $stmt->bindParam(':userid', $userid, PDO::PARAM_INT); 
                $stmt->bindParam(':filenameorig', $uploadfile_orig, PDO::PARAM_STR);
                $stmt->bindParam(':filenameview', $uploadfile_view, PDO::PARAM_STR);
                $stmt->bindParam(':filenamepreview', $uploadfile_preview, PDO::PARAM_STR);
                $stmt->execute();
                
                if (file_exists($uploadfile_orig))
                {
  
                    
                    return $_FILES["userfile"]["name"][$i] . " already exists. ";
                }
                else
                {
                    
                
                    move_uploaded_file($_FILES["userfile"]["tmp_name"][$i], $uploadfile_orig);
                  
                    
                    $image = new SimpleImage();
                    $image->load($uploadfile_orig);
                    $image->resizeToHeight(500);
                    $image->save($uploadfile_view);
                    
                    $image->resizeToHeight(70);
                    $image->save($uploadfile_preview);
                    
                    $_SESSION['LastUpload'] = (string)$uploadfile_view;
                    
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