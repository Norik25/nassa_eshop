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

//$view->itemID = "";
//$view->itemByID = "";
//
//if (isset($_POST['getRowIDIndex'])) {
//    $view->itemID = $_POST['getRowIDIndex'];
//    $view->itemByID = $itemDataObject->getItemsByID($view->itemID);
//}


//if ($_POST['key'] == 'getRowData') {
//    $rowID = $_POST['rowID'];
////        $dataRowQuery = "SELECT * FROM NASSA_items WHERE item_id=':rowID'";
////        $statement = $dbConnection->prepare($dataRowQuery); //prepare statement
////        $statement->bindParam(':rowID', $rowID, PDO::PARAM_STR);
////
////        $rowData = $statement->fetch();
//
//    $itemDataObjectRow = new ItemsDataSet();
//    $itemDataSetRow = $itemDataObjectRow->getItemsByID($rowID);
//
//    $jsonArray = array(
//        'itemName' => $itemDataSetRow[0]->getItemName(),
//        'itemType' => $itemDataSetRow[0]->getItemType(),
//        'itemColor' => $itemDataSetRow[0]->getItemColor(),
//        'itemPrice' => $itemDataSetRow[0]->getItemPrice(),
//        'itemBrand' => $itemDataSetRow[0]->getItemBrand(),
//        'itemSize' => $itemDataSetRow[0]->getItemSize(),
//        'itemQuantity' => $itemDataSetRow[0]->getItemQuantity(),
//        'itemImage' => $itemDataSetRow[0]->getItemImage(),
//
//    );
//    exit(json_encode($jsonArray));
//
//}










require_once('View/index.phtml');
