<?php
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
