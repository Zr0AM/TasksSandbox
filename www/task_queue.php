<?php
session_start();

$objHtml = new html\htmlwrapper();
$objHtml->writeHeader($_SERVER["PHP_SELF"]);

if (! isset($_SESSION['userid'])) {

    die('You must be logged in to view this page.');
}

require_once 'sql/scripts.php';
require_once 'util/Dropdown.php';

$objDropdown = new util\Dropdown();

$objDropdown->addOptions(Array(
    "Outstanding",
    "Complete",
    "Upcoming",
    "All"
));

$objDropdown->setDisplayName("Queue Status");
$objDropdown->setId("queueSelection");
$objDropdown->setObjName("queueStatus");
$objDropdown->setOnChange("getQueueStatus(this.value)");
$objDropdown->setDefaultOption(0);

echo $objDropdown->getHTML();

require_once 'sql/list_queue.php';

$objHtml->writeFooter();
?>
