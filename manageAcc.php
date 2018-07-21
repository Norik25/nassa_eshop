<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 21/07/2018
 * Time: 06:34
 */


require_once ('Model/ItemsDataSet.php');
require_once ('Model/Database.php');





$view = new stdClass();
$view->pageTitle = 'Manage User';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}












require_once('View/manageAcc.phtml');
