<?php

session_start();
$_SESSION['LoggedInUser'] = "notset";
$_SESSION['UserDbId'] = "";
$_SESSION['UserEmail'] = "";
header( 'Location: index.php' ) ;
?>