<?php

session_start();
$_SESSION['LoggedInUser'] = "notset";
header( 'Location: index.php' ) ;
?>