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

echo "<!DOCTYPE html>\n";
?>
<html>
<head>
<title>Image Upload</title>

    <script src="javascript/jquery.min.js" type='text/javascript'></script>
    <script src="javascript/kendo.web.min.js" type='text/javascript'></script>
    <link href="styles/kendo.common.css" rel="stylesheet" />
    <link href="styles/kendo.default.css" rel="stylesheet" />

<?php

    echo site_header_info();

?>
    
</head>
<body>
<?php
	echo site_menu();
?>

    <div class="maincontent">
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
            session_start();
            if ($_SESSION['LastUpload'] != "")
            {
                echo "<br />Last File Uploaded: " . $_SESSION['LastUpload'] . "<br />";
                echo '<img src="' . $_SESSION['LastUpload'] . '" />';
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
