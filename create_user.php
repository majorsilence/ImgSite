<?php

/*
* File: create_user.php
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
<title>Create New User</title>
</head>
<body>
<?php
	echo site_menu();
?>
<div>
  <form id="createuserform" name="createuserform" action="create_user_action.php" method="post">
    <p>
      <label for="username">Email Address: </label>
      <input type="text" name="email" id="email" />
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" />
    </p>
    <p>
      <input type="submit" id="create" name="create" />
    </p>
  </form>
</div>


</body>
</html>
