<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 15/06/2018
 * Time: 14:16
 */

require_once ('Model/Database.php');
require_once ('Model/ItemsDataSet.php');


    if (isset($_POST['key'])) {




        $db = Database::getInstance();
        $dbConnection = $db->getdbConnection();


    if ($_POST['key'] == 'getRowData') {
        $rowID = $_POST['rowID'];
//        $dataRowQuery = "SELECT * FROM NASSA_items WHERE item_id=':rowID'";
//        $statement = $dbConnection->prepare($dataRowQuery); //prepare statement
//        $statement->bindParam(':rowID', $rowID, PDO::PARAM_STR);
//
//        $rowData = $statement->fetch();

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
        );
        exit(json_encode($jsonArray));

    }

    if ($_POST['key'] == 'existingData') {

        $start = $_POST['start'];
        $limit = $_POST['limit'];

//        $start = 0;
//        $limit = 5;

        $sqlQuery = "SELECT * FROM NASSA_items ";
        $statement = $dbConnection->prepare($sqlQuery); //prepare statement
//        $statement->bindParam(':start', $start, PDO::PARAM_INT);
//        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();



//        echo var_dump($statement);


        if ($statement->rowCount() > 0) {

            $response = '';
            $data = array();
            while ($row = $statement->fetch()) {
                $data = $row;
                $response .= '
                <tr>
                    <td><img src=""></td>
                    <td id="item_' . $row['item_id'] . '">'. $row['item_name'].'</td>
                    <td>'. $row['item_type'].'</td>
                    <td>'. $row['item_brand'] .'</td>
                    <td>'. $row['item_color'] .'</td>
                    <td>'. $row['item_size'] .'</td>
                    <td>'. $row['item_price'] .'</td>
                    <td>'. $row['item_quantity'] .'</td>
                    <td>
                    <button  type="button" onclick="edit(' . $row['item_id'] . ')" class="btn btn-primary">Upravit</button>
                    <button  type="button" onclick="delete(' . $row['item_id'] . ')" class="btn btn-danger">Vymazat</button>
                    </td>
                </tr>
                ';
            }
//            echo var_dump($row);
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
        $productImg='';



    //querying all items for duplicity check
    $duplicityCheckQuery = "SELECT * FROM NASSA_items WHERE item_name = '$productName' AND item_color = '$productColor' AND item_size = '$productSize'";
    //fetching all items for duplicity check
    $itemDataObject = new ItemsDataSet();
    $itemDataSet = $itemDataObject->getDataByQuery($duplicityCheckQuery);





        //update item in the database
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
                exit('success');
            } else {
                exit('Error: ' . var_dump($stmt->errorInfo()) );
            }

        }
        //adding item to the database
        if ($_POST['key'] == 'addNew') {
            if (!empty($itemDataSet)) {
                exit('Produkt s timto nazvem a farbou uz existuje');
            } else {


                if (isset($_FILES['attachments'])) {
                    $imgExtention = rand(false);
                    $msg = "";
                    $productImg = $imgExtention . basename($_FILES['attachments']['name'][0]);
                    $targetFile = "Uploads/" . $productImg;

                    if (file_exists($targetFile))
                        $msg = array("status" => 0, "msg" => "File already exists!");
                    else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
                        $msg = array("status" => 1, "msg" => "File Has Been Uploaded", "path" => $targetFile);

                    exit(json_encode($msg));
                }


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





