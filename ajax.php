<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 15/06/2018
 * Time: 14:16
 */

require_once ('Model/Database.php');
require_once ('Model/ItemsDataSet.php');


$db = Database::getInstance();
$dbConnection = $db->getdbConnection();
try {

    if (isset($_POST['key'])) {

        //getting information from the input
        $productName = $_POST['name'];
        $productType = $_POST['type'];
        $productBrand = $_POST['brand'];
        $productColor = $_POST['color'];
        $productSize = $_POST['size'];
        $productPrice = $_POST['price'];
        $productQuantity = $_POST['quantity'];
        $start = $_POST['start'];
        $limit = $_POST['limit'];


        $itemDataObject = new ItemsDataSet();
    if ($_POST['key'] == 'getExistingData') {
        $displayDataQuery = ("SELECT * FROM NASSA_Items LIMIT :start, :limit ");
        $stmt = $dbConnection->prepare($displayDataQuery);
        $stmt->bindParam(':start', $start, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_STR);
        if ($stmt->execute()) {
            exit('Success' );
        } else {
            exit('Error' . ' '. var_dump($stmt->errorInfo()));
        }
    }

    //querying all items for duplicity check
    $duplicityCheckQuery = "SELECT * FROM NASSA_items WHERE item_name = '$productName' AND item_color = '$productColor'";
    //fetching all items for duplicity check
    $itemDataObject = new ItemsDataSet();
    $itemDataSet = $itemDataObject->getDataByQuery($duplicityCheckQuery);

        //adding item to the database
        if ($_POST['key'] == 'addNew') {
            if (!empty($itemDataSet)) {
                exit('Produkt s timto nazvem a farbou uz existuje');
            } else {
                echo $productPrice;
                $addItemQuery = ("INSERT INTO NASSA_items (item_name, item_type, item_color, 
              item_price, item_brand, item_size, item_quantity) VALUES (:productName, :productType, 
               :productColor, :productPrice, :productBrand,:productSize, :productQuantity)");
                $stmt = $dbConnection->prepare($addItemQuery);
                $stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
                $stmt->bindParam(':productType', $productType, PDO::PARAM_STR);
                $stmt->bindParam(':productBrand', $productBrand, PDO::PARAM_STR);
                $stmt->bindParam(':productColor', $productColor, PDO::PARAM_STR);
                $stmt->bindParam(':productSize', $productSize, PDO::PARAM_STR);
                $stmt->bindParam(':productPrice', $productPrice, PDO::PARAM_STR);
                $stmt->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    exit('Produkt bol uspesne ulozeny v databaze.' );
                } else {
                    exit('Error: Produkt nebol ulozeny.' . ' '. var_dump($stmt->errorInfo()));
                }


            }
        }
    }

}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$db->__destruct();

