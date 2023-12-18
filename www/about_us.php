<?php
session_start();

use html\htmlwrapper;
$objHtml = new html\htmlwrapper();
$objHtml->writeHeader($_SERVER["PHP_SELF"]);

echo 'We are Team Omnomnom!';

$objHtml->writeFooter();
