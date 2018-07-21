<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 09/07/2018
 * Time: 17:29
 */

require_once ('Model/Database.php');


$view = new stdClass();
$view->pageTitle = 'Login';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['loggedIN'])) {
    header('Location: main.php');
    exit();
}

$db = Database::getInstance();
$dbConnection = $db->getdbConnection();

if (isset($_POST['login'])) {
    $email = $_POST['emailPHP'];
    $password = $_POST['passwordPHP'];
//    $inputError = array();
//    $dataError = array();

//    if ($email == "")  {
//        array_push($inputError, 'Prosim vlozte email.');
//    } elseif ($password == "") {
//        array_push($inputError, 'Prosim vlozte password.');
//    }
    $loginQuery = "SELECT user_id, user_password, user_companyName FROM NASSA_users WHERE user_email=:email";
    $stmt = $dbConnection->prepare($loginQuery);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $data = "";
        if ($row = $stmt->fetch()) {
            $data = $row;
        } else {
            exit('Email neexistuje.');
        }
        if (password_verify($password, $data['user_password'])) {

            $_SESSION['loggedIN'] = '1';
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $data['user_companyName'];
            exit('success');
        } else {
            exit('Nespravny password.');
        }
    } else {
        exit('Zadany email neexistuje.');
    }






}


require_once('View/index.phtml');