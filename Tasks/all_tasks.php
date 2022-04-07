<?php
namespace www;

session_start();

require_once 'html/htmlwrapper.php';
$objHtml = new html\htmlwrapper();
$objHtml::writeHeader($_SERVER["PHP_SELF"]);

unset($_POST);

if (! isset($_SESSION['userid']))
{
  
  die('You must be logged in to view this page.');
}

require_once 'sql/scripts.php';
require_once 'util/Dropdown.php';

$objDropdown = new util\Dropdown();

$objDropdown->addOptions(Array(
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

$objHtml::writeFooter();

?>