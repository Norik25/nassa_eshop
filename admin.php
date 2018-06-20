<?php

require_once ('Model/ItemsDataSet.php');

$view = new stdClass();
$view->pageTitle = 'Admin';

$itemDataObject = new ItemsDataSet();
$items = $itemDataObject->fetchAllData();


if (isset($_FILES['attachments'])) {
    $msg = "";
    $targetFile = "uploads/" . basename($_FILES['attachments']['name'][0]);
    if (file_exists($targetFile))
        $msg = array("status" => 0, "msg" => "File already exists!");
    else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
        $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile);

    exit(json_encode($msg));
}




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