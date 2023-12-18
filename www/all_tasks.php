<?php
session_start();

use html\htmlwrapper;
use util\Dropdown;

$objHtml = new htmlwrapper;
$objHtml->writeHeader($_SERVER["PHP_SELF"]);

unset($_POST);

if (! isset($_SESSION['userid'])) {

    die('You must be logged in to view this page.');
}

$objDropdown = new Dropdown();

$objDropdown->addOptions(array(
    "Active",
    "Inactive",
    "All"
));

$objDropdown->setDisplayName("Task Status");
$objDropdown->setId("selection");
$objDropdown->setObjName("status");
$objDropdown->setOnChange("getTasks(this.value)");
$objDropdown->setDefaultOption(2);

echo $objDropdown->getHTML();

require_once 'sql/list_tasks.php';

$objHtml->writeFooter();

?>
