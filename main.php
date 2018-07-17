<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 13/06/2018
 * Time: 15:39
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once ('Model/ItemsDataSet.php');
require_once ('Model/Database.php');



if (!isset($_SESSION['name'])) {
    header('location: index.php');

}

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

//if (isset($_SESSION['name'])) {
//    $headerSession = '
//            <li class="nav-item" >
//            <div class="dropdown show">
//                <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
//                        ' . $_SESSION['name'] . '
//                </a>
//
//                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
//                    <a class="dropdown-item" href="#">Sprava uctu</a>
//                    <a class="dropdown-item" href="#">Vase Objednavky</a>
//                    <a class="dropdown-item" href="logout.php">Log out</a>
//                </div>
//            </div>
//        </li>
//';
//} else {
//    $headerSession = '';
//}












require_once('View/main.phtml');
