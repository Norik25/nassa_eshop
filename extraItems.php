<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 18/06/2018
 * Time: 06:06
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['name'])) {
    header('location: index.php');

}

$view = new stdClass();
$view->pageTitle = 'Extra';


require_once('View/extraItems.phtml');