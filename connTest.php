<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 28/08/2018
 * Time: 07:49
 */


$view = new stdClass();
$view->pageTitle = 'Test';

require_once ('Test/Database1.php');
require_once ('Test/TestItems.php');



$itemsObject = new TestItems();
$items = $itemsObject->fetchAllData()[0];

$test = "test";

require_once('View/conTest.phtml');