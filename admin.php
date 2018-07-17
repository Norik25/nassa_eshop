<?php

require_once ('Model/ItemsDataSet.php');
require_once ('Model/Database.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['name'] !== 'admin' ) {
    header('location: index.php');

}

$view = new stdClass();
$view->pageTitle = 'Admin';

$id = 14;
$target = "";
$itemDataObject = new ItemsDataSet();
$items = $itemDataObject->fetchAllData();
$itemsById = $itemDataObject->getItemsByID($id);

//if (isset($_FILES['attachments'])) {
//    $target = basename($_FILES['attachments']['name'][0]);
//}


//$it = $itemsById[0]->getItemName();
//
//echo $it;

//echo var_dump($items);
//$itemData = $itemDataObject->getDataByLimit(0, 10);

// TEST <<-------------

//
//$dbInstance = Database::getInstance();
//
//$dbHandle = $dbInstance->getdbConnection();
//
//
//
//
//$limit = 5;
//$start = 0;
//
//$sqlQuery = "SELECT * FROM NASSA_items LIMIT :start, :limit";
//
//$statement = $dbHandle->prepare($sqlQuery); //prepare statement
//
//
//$statement->bindParam(':start', $start, PDO::PARAM_INT);
//$statement->bindParam(':limit', $limit, PDO::PARAM_INT);
//$statement->execute();
//
//
//
//
//if ($statement->rowCount() > 0) {
//    while ($row = $statement->fetch()) {
//        echo $row['item_name'];
//    }
//} else {
//    echo 's g w';
//}

//$dataSet = [];
//
//while ($row = $statement->fetch()) {
//    $dataSet[] = new ItemsData($row);
//}
//echo var_dump($dataSet);









//TEST ------------------------->>>







//if (isset($_FILES['attachments'])) {
//    $msg = "";
//    $targetFile = "uploads/" . basename($_FILES['attachments']['name'][0]);
//    if (file_exists($targetFile))
//        $msg = array("status" => 0, "msg" => "File already exists!");
//    else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
//        $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile);
//
//    exit(json_encode($msg));
//}




//if ($_POST['key'] == 'getExistingData') {
//    $displayDataQuery = ("SELECT * FROM NASSA_Items LIMIT :start, :limit ");
//    $stmt = $dbConnection->prepare($displayDataQuery);
//    $stmt->bindParam(':start', $start, PDO::PARAM_STR);
//    $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
//    if ($stmt->execute()) {
//        exit('Success' );
//    } else {
//        exit('Error' . ' '. var_dump($stmt->errorInfo()));
//    }
//}



require_once('View/admin.phtml');