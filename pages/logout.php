<?php 
session_start();
extract($_REQUEST);
session_unset();
session_destroy();
header("Location: ../");

?>