<?php

/*
* File: image_upload.php
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

if(session_id() == '') 
{
    session_start();
}

echo "<!DOCTYPE html>\n";
?>
<html>
<head>
<title>Image Upload</title>

<?php

    echo site_header_info();

?>
    
</head>
<body>
<?php
	echo site_menu();
?>

    <div class="maincontent">
    <?php
        if (is_valid_request())
        {
    ?>
        <table width="100%">
            <tr>
                <td width="25%" valign="top">
                    <form method="post" action="image_upload_action.php?webupload=0" style="width:100%" >
                        Upload from computer <br />
                        <div>
                            <input name="userfile[]" id="userfile" type="file" />
                            <p>
                                <input type="submit" value="Submit" class="k-button" />
                            </p>
                        </div>
                    </form>
                </td>
                
                <td valign="top">
                    <form method="post" action="image_upload_action.php?webupload=1" style="width:100%" >
                        Upload from the web <br />
                        Enter the URLs of images, one per line: <br />
                        <div>
                            <textarea name="userfile[]" id="userfile" rows="5" cols="60">http://www.majorsilence.com/image.png</textarea>
                            <p>
                                <input type="submit" value="Submit" class="k-button" />
                            </p>
                        </div>
                    </form>
                </td>
            </tr>
        </table>
        <?php
            }
            else
            {
                echo "Only logged in users can upload files.";
            }
            
            if (isset($_SESSION['LastUpload']))
            {
				if ($_SESSION['LastUpload'] != "") 
				{
					echo "<br />Last File Uploaded: " . $_SESSION['LastUpload'] . "<br />";
					echo '<img src="' . $_SESSION['LastUpload'] . '" />';
				}
            }
        ?>
        
        <script type='text/javascript'>
            $(document).ready(function() {
                $("#userfile").kendoUpload();
            });
        </script>
    </div>
</body>
</html>
