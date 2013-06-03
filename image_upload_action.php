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

if(session_id() == '') 
{
    session_start();
}


function upload_image()
{


    if (is_valid_request() == false)
    {
        return "Failure to upload.  Not a valid request.  Make sure you are logged in";
    }

    $webrequest =  (int)$_REQUEST['webupload'];
    
    
    $uploaded=0;
    
    if(session_id() == '') 
    {
        session_start();
    }
    
    $dbh = get_connection();
    $dbh->beginTransaction();
    try
    {
        if ($webrequest == 0)
        {
            // computer upload
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
                    savefile($dbh, $_FILES["userfile"]["name"][$i], $_FILES["userfile"]["tmp_name"][$i]);
                    
                }
                 
                 $uploaded++; 
             
             
            } 
        }
        else{
            // web upload
            $userfiles = $_REQUEST["userfile"];
            $files = explode("\n", $userfiles[0]);
            foreach($files as $value)
            {
                $temp_file = tempnam(sys_get_temp_dir(), 'imgsite');
                
                $ch = curl_init ();
                curl_setopt($ch, CURLOPT_URL, $value);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect: 100-continue"));
                curl_setopt($ch, CURLOPT_HEADER, 0);   
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6");
                
                // if you are not running with SSL or if you don't have valid SSL
                $verify_peer = false;
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_peer);

                // Disable HOST (the site you are sending request to) SSL Verification,
                // if Host can have certificate which is invalid / expired / not signed by authorized CA.
                $verify_host = false;
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verify_host);
                
                $rawdata=curl_exec($ch);
                curl_close ($ch);

                
                $fp = fopen($temp_file,'w');
                fwrite($fp, $rawdata);
                fclose($fp);
                
                // Auto Convert Downloaded Files to Png
              
                $image = new SimpleImage();
                $image->load($temp_file);
                $image->save($temp_file, IMAGETYPE_PNG);
                
                $name = str_replace(".tmp", "", $temp_file) . ".png";
                
                
                //echo $temp_file;
                savefile($dbh, $name, $temp_file);
                
            }
            
            
        }
        $dbh->commit();
    }
    catch (Exception $e) 
    {
        $dbh->rollBack();
        return "Failed: " . $e->getMessage();
    }


}

function savefile($dbh, $name, $the_file)
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
    
    $uploadfile_orig = $uploaddir . $nextNum . "-" . basename($name);
    $uploadfile_preview = $uploaddir . $nextNum . "-preview-" . basename($name);
    $uploadfile_view = $uploaddir . $nextNum . "-view-" . basename($name);
    
    $userid = (int)$_SESSION['UserDbId'];
    $stmt->bindParam(':id', $nextNum, PDO::PARAM_INT); 
    $stmt->bindParam(':userid', $userid, PDO::PARAM_INT); 
    $stmt->bindParam(':filenameorig', $uploadfile_orig, PDO::PARAM_STR);
    $stmt->bindParam(':filenameview', $uploadfile_view, PDO::PARAM_STR);
    $stmt->bindParam(':filenamepreview', $uploadfile_preview, PDO::PARAM_STR);
    $stmt->execute();
    
    if (file_exists($uploadfile_orig))
    {
        return $name . " already exists. ";
    }
    else
    {
        
    
        if (move_uploaded_file($the_file, $uploadfile_orig) == false)
        {
            // not a uploaded file.  Probably web download.  Try moving manually.
             copy($the_file, $uploadfile_orig);
        }
        
        
        $image = new SimpleImage();
        $image->load($uploadfile_orig);
        $image->resizeToHeight(500);
        $image->resizeToWidth(500);
        $image->save($uploadfile_view);
        
        $image->resizeToHeight(70);
        $image->resizeToWidth(70);
        $image->save($uploadfile_preview);
        
        $_SESSION['LastUpload'] = (string)$uploadfile_view;
        
    }
}


upload_image();

header( 'Location: image_upload.php' ) ;

?>