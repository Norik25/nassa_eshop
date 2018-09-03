<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 04/08/2018
 * Time: 07:55
 */


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$view = new stdClass();
$view->pageTitle = 'Test';






require_once('View/test.phtml');