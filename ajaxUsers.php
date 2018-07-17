<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 06/07/2018
 * Time: 12:38
 */
require_once ('Model/Database.php');
require_once ('Model/UserDataSet.php');

$db = Database::getInstance();
$dbConnection = $db->getdbConnection();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['key'])) {

    /**
     * Gets ItemData for a particular row from the database and returns it in JSON
     */
    if ($_POST['key'] == 'getRowUserData') {
        $rowID = $_POST['rowIDUser'];
        $userDataObjectRow = new UserDataSet();
        $userDataSetRow = $userDataObjectRow->getUserByID($rowID);

        $jsonArray = array(
            'userCompanyName' => $userDataSetRow[0]->getCompanyName(),
            'userEmail' => $userDataSetRow[0]->getUserEmail(),
            'userPhone' => $userDataSetRow[0]->getUserPhone(),
            'userAddress' => $userDataSetRow[0]->getUserAddress(),
            'userCity' => $userDataSetRow[0]->getUserCity(),
            'userPostCode' => $userDataSetRow[0]->getUserPostCode(),
            'userICO' => $userDataSetRow[0]->getUserICO(),
            'userDIC' => $userDataSetRow[0]->getUserDIC(),

        );
        exit(json_encode($jsonArray));
    }
    /**
     * Deletes the row of the item
     */
    if ($_POST['key'] == 'deleteRow') {
        $iDRow = $_POST['rowID'];
        $deleteQuery = "DELETE FROM NASSA_users WHERE user_id = :rowID";
        $stmt = $dbConnection->prepare($deleteQuery);
        $stmt->bindParam(':rowID', $iDRow, PDO::PARAM_STR);
        if ($stmt->execute()) {
            exit("Uzivatel byl odstranen!");
        }

    }
    /**
     * Gets all data from the database and returns them line by line
     * forming the table.
     */
    if ($_POST['key'] == 'existingUserData') {

        $start = $_POST['start'];
        $limit = $_POST['limit'];

        $sqlQuery = "SELECT * FROM NASSA_users ";
        $statement = $dbConnection->prepare($sqlQuery); //prepare statement
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $response = '';
            $data = array();
            while ($row = $statement->fetch()) {
                $data = $row;
                $response .= '
                <tr>
                    <td id="item_' . $row['user_id'] . '">'. $row['user_companyName'].'</td>
                    <td>'. $row['user_email'] .'</td>
                    <td>'. $row['user_phone'] .'</td>
                    <td>'. $row['user_address'] .'</td>
                    <td>'. $row['user_city'] .'</td>
                    <td>'. $row['user_postCode'] .'</td>
                    <td>'. $row['user_ico'] .'</td>
                    <td>'. $row['user_dic'] .'</td>
                    <td>
                    <button  type="button" onclick="editUser(' . $row['user_id'] . ')" class="btn btn-primary">Upravit</button>
                    <button  type="button" onclick="deleteUser(' . $row['user_id'] . ')" class="btn btn-danger">Vymazat</button>
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
    $companyName = $_POST['companyName'];
    $userEmail = $_POST['userEmail'];
    $userPhone = $_POST['userPhone'];
    $userAddress = $_POST['userAddress'];
    $userCity = $_POST['userCity'];
    $userPostCode = $_POST['userPostCode'];
    $userICO = $_POST['userICO'];
    $userDIC = $_POST['userDIC'];




    //querying all items for duplicity check
    $duplicityCheckQuery = "SELECT * FROM NASSA_users WHERE user_email = '$userEmail'";
    //fetching all items for duplicity check
    $userDataObject = new UserDataSet();
    $userDataSet = $userDataObject->getUsersDataByQuery($duplicityCheckQuery);


    /**
     * Updates data in the database for a selected row.
     */
    if ($_POST['key'] == 'updateUserRow') {
        $iDRowUser = $_POST['rowIDUser'];
        $updateQuery = "UPDATE NASSA_users SET user_companyName=:companyName, user_email=:userEmail, user_phone=:userPhone,
                      user_address=:userAddress, user_city=:userCity, user_postCode=:userPostCode, user_ico=:userICO, user_dic=:userDIC
                       WHERE user_id=:userRowID";
        $stmt = $dbConnection->prepare($updateQuery);
        $stmt->bindParam(':userRowID', $iDRowUser, PDO::PARAM_STR);
        $stmt->bindParam(':companyName', $companyName, PDO::PARAM_STR);
        $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
        $stmt->bindParam(':userPhone', $userPhone, PDO::PARAM_STR);
        $stmt->bindParam(':userAddress', $userAddress, PDO::PARAM_STR);
        $stmt->bindParam(':userCity', $userCity, PDO::PARAM_STR);
        $stmt->bindParam(':userPostCode', $userPostCode, PDO::PARAM_STR);
        $stmt->bindParam(':userICO', $userICO, PDO::PARAM_STR);
        $stmt->bindParam(':userDIC', $userDIC, PDO::PARAM_STR);


        if ($stmt->execute()) {
            exit('Pouzivatel bol uspesne upraveny.');
        } else {
            exit('Error: ' . var_dump($stmt->errorInfo()) );
        }
    }
    $token = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM0987654321";
    $token = str_shuffle($token);
    $token = substr($token, 0, 15);
//    $password = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM0987654321";
//    $password = str_shuffle($password);
//    $password = substr($password, 0, 15);
    $password = '123456';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $itemID = "";
    //adding item to the database
    if ($_POST['key'] == 'addNewUser') {
        if (!empty($userDataSet)) {
            exit('Pouzivatel s timto emailem uz existuje');
        } else {
            $addUserQuery = ("INSERT INTO NASSA_users (user_email, user_password, user_CompanyName, 
              user_phone, user_ico, user_dic, user_address, user_city, user_postCode, user_isEmailConfirmed, token) VALUES (:userEmail, '$hashedPassword', 
               :userCompanyName, :userPhone, :userICO,:userDIC, :userAddress, :userCity, :userPostCode, '0', '$token')");
            $stmt = $dbConnection->prepare($addUserQuery);
            $stmt->bindParam(':userCompanyName', $companyName, PDO::PARAM_STR);
            $stmt->bindParam(':userEmail', $userEmail, PDO::PARAM_STR);
            $stmt->bindParam(':userPhone', $userPhone, PDO::PARAM_STR);
            $stmt->bindParam(':userAddress', $userAddress, PDO::PARAM_STR);
            $stmt->bindParam(':userCity', $userCity, PDO::PARAM_STR);
            $stmt->bindParam(':userPostCode', $userPostCode, PDO::PARAM_STR);
            $stmt->bindParam(':userICO', $userICO, PDO::PARAM_STR);
            $stmt->bindParam(':userDIC', $userDIC, PDO::PARAM_STR);

            if ($stmt->execute()) {
//                include_once "PHPMailer/PHPMailer.php";
//                include_once "PHPMailer/Exception.php";
//                include_once "PHPMailer/SMTP.php";
//
//                $mail = new PHPMailer();
//                $mail->setFrom('n.nazarej@edu.salford.ac.uk');
//                $mail->addAddress($userEmail, $companyName);
//                $mail->Subject = "NASSA Registration verification Email";
//                $mail->isHTML(true);
//                $mail->Body = "
//                Please click on the ling below:<br><br>
//
//                <a href='localhost:8080/confirm.php?email=$userEmail&token=$token'>Click hre to verify.</a>
//                ";

//                if ($mail->send()) {
                    exit('Produkt bol uspesne ulozeny v databaze.' );
//                } else {
                    exit('Something wrong happened.');
//                }


            } else {
                exit('Error: Produkt nebol ulozeny.' . ' '. var_dump($stmt->errorInfo()));
            }
        }
    }
    $db->__destruct();

}