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
$itemObject = new ItemsDataSet();

if (isset($_FILES['attachments'])) {



    $msg = "";
    $target = basename($_FILES['attachments']['name']);

    $targetFile = "uploads/" . basename($_FILES['attachments']['name']);

    if (file_exists($targetFile)) {
        $msg = array("status" => 0, "msg" => "Vas image uz existuje!");
    } elseif (move_uploaded_file($_FILES['attachments']['tmp_name'], $targetFile)) {
//        $items = $itemDataObject->getLastItemID();
//        $itemID = intval($lastID) + 1;
//        $addImageQuery = "INSERT INTO NASSA_images (image_name, image_item) VALUES ('$target', '$itemID')";
//        $stmt = $dbConnection->prepare($addImageQuery);
//        $stmt->execute();

        $msg = array("status" => 1, "msg" => "Vas image bol uspesne ulozeny", "path" => $targetFile);
    }

    exit(json_encode($msg));

}



if (isset($_POST['key'])) {

    /**
     * Gets ItemData for a particular row from the database and returns it in JSON
     */
    if ($_POST['key'] == 'getRowData') {
        $rowID = $_POST['rowID'];
        $itemDataObjectRow = new ItemsDataSet();
        $itemDataSetRow = $itemDataObjectRow->getItemsByID($rowID);

        $jsonArray = array(
            'itemName' => $itemDataSetRow[0]->getItemName(),
            'itemType' => $itemDataSetRow[0]->getItemType(),
            'itemColor' => $itemDataSetRow[0]->getItemColor(),
            'itemPrice' => $itemDataSetRow[0]->getItemPrice(),
            'itemBrand' => $itemDataSetRow[0]->getItemBrand(),
            'itemSize' => $itemDataSetRow[0]->getItemSize(),
            'itemQuantity' => $itemDataSetRow[0]->getItemQuantity(),
            'itemImage' => $itemDataSetRow[0]->getItemImage(),

        );
        exit(json_encode($jsonArray));
    }
    /**
     * Deletes the row of the item
     */
    if ($_POST['key'] == 'deleteRow') {
        $iDRow = $_POST['rowID'];
        $deleteQuery = "DELETE FROM NASSA_items WHERE item_id = :rowID";
        $stmt = $dbConnection->prepare($deleteQuery);
        $stmt->bindParam(':rowID', $iDRow, PDO::PARAM_STR);
        if ($stmt->execute()) {
            exit("Vas produkt byl odstranen!");
        }

    }
    /**
     * Gets all data from the database and returns them line by line
     * forming the table.
     */
    if ($_POST['key'] == 'existingData') {

        $start = $_POST['start'];
        $limit = $_POST['limit'];

        $sqlQuery = "SELECT * FROM NASSA_items ";
        $statement = $dbConnection->prepare($sqlQuery); //prepare statement
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $response = '';
            $data = array();
            while ($row = $statement->fetch()) {
                $data = $row;
                $response .= '
                <tr>
                    <td><center><img src="uploads/' . $row['item_img'] .  '" style="width: 70px; height: 50px"></center></td>
                    <td id="item_' . $row['item_id'] . '">'. $row['item_name'].'</td>
                    <td>'. $row['item_type'].'</td>
                    <td>'. $row['item_brand'] .'</td>
                    <td>'. $row['item_color'] .'</td>
                    <td>'. $row['item_size'] .'</td>
                    <td>'. $row['item_price'] .'</td>
                    <td>'. $row['item_quantity'] .'</td>
                    <td>
                    <button  type="button" onclick="editItem(' . $row['item_id'] . ')" class="btn btn-primary">Upravit</button>
                    <button  type="button" onclick="deleteItem(' . $row['item_id'] . ')" class="btn btn-danger">Vymazat</button>
                    </td>
                </tr>
                ';
            }
            exit($response);
        }
        else {
            exit('reachedMax');
        }
    }


    //getting information from the input
    $productName = $_POST['name'];
    $productType = $_POST['type'];
    $productBrand = $_POST['brand'];
    $productColor = $_POST['color'];
    $productSize = $_POST['size'];
    $productPrice = $_POST['price'];
    $productQuantity = $_POST['quantity'];
    $productImg= basename($_POST['pic']);



    //querying all items for duplicity check
    $duplicityCheckQuery = "SELECT * FROM NASSA_items WHERE item_name = '$productName' AND item_color = '$productColor' AND item_size = '$productSize'";
    //fetching all items for duplicity check
    $itemDataObject = new ItemsDataSet();
    $itemDataSet = $itemDataObject->getDataByQuery($duplicityCheckQuery);


    /**
     * Updates data in the database for a selected row.
     */
    if ($_POST['key'] == 'updateRow') {
        $iDRow = $_POST['rowID'];
        $updateQuery = "UPDATE NASSA_items SET item_name=:productName, item_type=:productType, item_color=:productColor,
                      item_price=:productPrice, item_brand=:productBrand, item_size=:productSize, item_quantity=:productQuantity, item_img=:productImg
                       WHERE item_id=:rowID";
        $stmt = $dbConnection->prepare($updateQuery);
        $stmt->bindParam(':rowID', $iDRow, PDO::PARAM_STR);
        $stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
        $stmt->bindParam(':productType', $productType, PDO::PARAM_STR);
        $stmt->bindParam(':productBrand', $productBrand, PDO::PARAM_STR);
        $stmt->bindParam(':productColor', $productColor, PDO::PARAM_STR);
        $stmt->bindParam(':productSize', $productSize, PDO::PARAM_STR);
        $stmt->bindParam(':productPrice', $productPrice, PDO::PARAM_STR);
        $stmt->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
        $stmt->bindParam(':productImg', $productImg, PDO::PARAM_STR);


        if ($stmt->execute()) {
            exit('Produkt bol uspesne upraveny.');
        } else {
            exit('Error: ' . var_dump($stmt->errorInfo()) );
        }
    }

    $itemID = "";
    //adding item to the database
    if ($_POST['key'] == 'addNew') {
        if (!empty($itemDataSet)) {
            exit('Produkt s timto nazvem a farbou uz existuje');
        } else {
            $addItemQuery = ("INSERT INTO NASSA_items (item_name, item_type, item_color, 
              item_price, item_brand, item_size, item_quantity, item_img) VALUES (:productName, :productType, 
               :productColor, :productPrice, :productBrand,:productSize, :productQuantity, :productImg)");
            $stmt = $dbConnection->prepare($addItemQuery);
            $stmt->bindParam(':productName', $productName, PDO::PARAM_STR);
            $stmt->bindParam(':productType', $productType, PDO::PARAM_STR);
            $stmt->bindParam(':productBrand', $productBrand, PDO::PARAM_STR);
            $stmt->bindParam(':productColor', $productColor, PDO::PARAM_STR);
            $stmt->bindParam(':productSize', $productSize, PDO::PARAM_STR);
            $stmt->bindParam(':productPrice', $productPrice, PDO::PARAM_STR);
            $stmt->bindParam(':productQuantity', $productQuantity, PDO::PARAM_INT);
            $stmt->bindParam(':productImg', $productImg, PDO::PARAM_STR);

            if ($stmt->execute()) {
                exit('Produkt bol uspesne ulozeny v databaze.' );
            } else {
                exit('Error: Produkt nebol ulozeny.' . ' '. var_dump($stmt->errorInfo()));
            }
        }
    }
    $db->__destruct();
}







