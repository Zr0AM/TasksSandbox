<?php

session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

if (! isset($_SESSION['userid']))
{
  
  die('You must be logged in to view this page.');
}

require_once "sql/scripts.php";

sql\add_log($_SESSION['userid'], 'Logout');

session_unset();

session_destroy();

session_start();

$_SESSION['errorMsg'] = "You have successfully logged out";

header('Location: login.php');

$objHtml::writeFooter();
?>