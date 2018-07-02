<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 13/06/2018
 * Time: 15:39
 */

require_once ('Model/ItemsDataSet.php');
require_once ('Model/Database.php');

$view = new stdClass();
$view->pageTitle = 'Homepage';

require_once ('Model/ItemsDataSet.php');


$itemDataObject = new ItemsDataSet();
$items = $itemDataObject->fetchAllData();
$name = $itemDataObject->getAllItemNames();





require_once('View/index.phtml');
