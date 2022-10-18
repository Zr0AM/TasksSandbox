<?php
session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

echo '<div> Welcome to Team Omnomnom\'s Tasks application.</div><br> <br>';

$objHtml::writeFooter();
