<?php

/*
* File: index.php
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
<title></title>

<?php

    echo site_header_info();

?>

</head>
<body>
<?php
	echo site_menu();
?>



<div class = "maincontent">
Upload and View Images

An account is required to upload images.  All images that are uploaded are public.
</div>



</body>
</html>
