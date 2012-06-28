<?php

session_start();
$_SESSION['LoggedInUser'] = "notset";
$_SESSION['UserDbId'] = "";
$_SESSION['UserEmail'] = "";
$_SESSION['LastUpload'] = "";
header( 'Location: index.php' ) ;
?>