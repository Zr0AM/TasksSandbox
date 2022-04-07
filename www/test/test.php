<?php

/**
 
 require_once 'util/Database.php';
 
 $db = new Database();
 
 $db->runQuery("SELECT * FROM tbl_tasks;");
 
 */
namespace www\test;

use www\util;
require_once '../util/Constant.php';
echo util\Constant::HTMLHead();

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

echo util\Constant::HTMLFooter();

// use www\html;
// require_once '../html/html.php';

?>