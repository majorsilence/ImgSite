<?php

/*
* File: login.php
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

session_start();

if (is_valid_request()==true)
{
	echo '<a href="logout.php">Logout</a><br />';
}
else
{

?>

<script type="text/javascript">
$(document).ready(function() {

	$("#login").click(function() {

		var action = $("#loginform").attr('action');
		var form_data = {
			username: $("#username").val(),
			password: $("#password").val(),
			is_ajax: 1
		};
		
		$.ajax({
			type: "POST",
			url: action,
			data: form_data,
			success: function(response)
			{
				if(response == 'success')
				{
					$("#loginform").slideUp('slow', function() {
						$("#message").html("<p class='success'>You have logged in successfully!</p><a href=\"logout.php\">Logout</a><br />");
					});
				}
				else
				{
					$("#message").html("<p class='error'>Invalid username and/or password.</p>");
				}
			}
		});
		
		return false;
	});

	});
</script>

  <form id="loginform" name="loginform" action="login_action.php" method="post">
    <p>
      <label for="username">Username: </label>
      <input type="text" name="username" id="username" />
    </p>
    <p>
      <label for="password">Password: </label>
      <input type="password" name="password" id="password" />
    </p>
    <p>
      <input type="submit" id="login" name="login" />
    </p>
    
      <br />
      <a href="create_user.php">Create New User</a>
      <br />
  </form>
  
  <div id="message"></div>

  <?php
  }
  ?>