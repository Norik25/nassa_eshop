<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 11/07/2018
 * Time: 11:07
 */

session_start();

unset($_SESSION['loggedIN']);
unset($_SESSION['email']);
unset($_SESSION['name']);
session_destroy();
header('Location: index.php');
exit();