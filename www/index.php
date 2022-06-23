<?php

session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

echo '<span> Welcome to Team Omnomnom\'s Tasks application.</span><br> <br>';

/*
 * echo print_r($_SESSION);
 * 
 */

$objHtml::writeFooter();

?>