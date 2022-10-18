<?php

/**

 require_once 'util/Database.php';

 $db = new Database();

 $db->runQuery("SELECT * FROM tbl_tasks;");

 */
namespace test;

use util;
use html\htmlwrapper;
echo htmlwrapper::writeHeader($_SERVER["PHP_SELF"]);

require_once '../util/Dropdown.php';

$objDropdown = new util\Dropdown();

$objDropdown->addOptions(Array(
    "By",
    "To"
));

$objDropdown->setDisplayName("Test");
$objDropdown->setId("Test");
$objDropdown->setObjName("Test");
$objDropdown->setOnChange("Test");

echo $objDropdown->getHTML();

echo htmlwrapper::writeFooter();

// use www\html;
// require_once '../html/html.php';

?>