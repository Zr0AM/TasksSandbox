<?php
session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml->writeHeader($_SERVER["PHP_SELF"]);

echo 'We are Team Omnomnom!';

$objHtml->writeFooter();
