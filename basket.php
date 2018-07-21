<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 14/07/2018
 * Time: 13:19
 */



require_once ('Model/Database.php');
require_once ('Model/ItemsDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Basket';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$selectedItems = array();

$itemDataObject = new ItemsDataSet();

if ($_POST['key'] == 'basket') {
    $itemId = $_POST['rowID'];
    $item = $itemDataObject->getItemsByID($itemId)[0];
    $qtyOfClickedItem = $_POST['qtyInput'];
    if (intval($qtyOfClickedItem) <= intval($item->getItemQuantity())) {
        $_SESSION['basketItems'][$item->getItemID()] = array('itemB' => $item, 'qty' => $qtyOfClickedItem);
//        $_SESSION['Item' . $item->getItemID()] = $qtyOfClickedItem;
        $items = array();
        $items = $_SESSION['basketItems'];
        $jsonArray = array(
//            'qty' => $_SESSION['Item' . $item->getItemID()],
            'itemClicked' => $item,
            'itemsInBasket' => count($_SESSION['basketItems']),
            'basketItems' => $items,
        );
        exit(json_encode($jsonArray));
    } else {
        $jsonArray = array(
//            'qty' => $_SESSION['Item' . $item->getItemID()],
            'itemClicked' => $item,
            'itemsInBasket' => 0,
            'basketItems' => null,
        );
        exit(json_encode($jsonArray));
    }
}

if ($_POST['key'] == 'deleteRow') {
    $rowID = $_POST['rowID'];
    unset($_SESSION['basketItems'][$rowID]);
    $jsonArray = array(
        'qty' => count($_SESSION['basketItems'])
    );
    exit(json_encode($jsonArray));
}

