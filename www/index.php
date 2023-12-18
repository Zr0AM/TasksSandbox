<?php
session_start();


$objHtml = new \html\htmlwrapper();
$objHtml->writeHeader($_SERVER["PHP_SELF"]);

echo '<div> Welcome to Team Omnomnom\'s Tasks application.</div><br> <br>';

$objHtml->writeFooter();
