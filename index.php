<?php
 echo "<!DOCTYPE html>\n";
?>
<html>
<head>
<title></title>
<script src = 'javascript/jquery.min.js' type='text/javascript'></script>


</head>
<body>

<div id="login-div" style="width:75px;">

</div>

<div>
Upload and View Images

An account is required to upload images.  All images that are uploaded are public.
</div>

<script>
  $("#login-div").load("login.php");
</script>


</body>
</html>
