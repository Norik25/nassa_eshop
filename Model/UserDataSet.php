<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 06/07/2018
 * Time: 14:29
 */

require_once ('Model/Database.php');
require_once ('Model/UsersData.php');

class UserDataSet
{
    protected $_dbHandle, $dbInstance;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->_dbHandle = $this->dbInstance->getdbConnection();
    }

    /**
     * Fetches all Users from the database
     * @return array of Users
     */
    public function fetchAllData() {
        $sqlQuery = 'SELECT * FROM NASSA_users';

        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare statement
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new UsersData($row);
        }
        return $dataSet;
    }

    /**
     * Receives the query as an argument and gets data by that MySql query
     * @param $query of Type String
     * @return array of Items of type ItemsData
     */
    public function getUsersDataByQuery($query) {

        $statement = $this->_dbHandle->prepare($query); //prepare statement
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new UsersData($row);
        }
        return $dataSet;
    }

    public function getUserByID($userID) {

        $sqlQuery = "SELECT * FROM NASSA_users WHERE user_id=:rowID";
        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare statement
        $statement->bindParam(':rowID', $userID, PDO::PARAM_STR);
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new UsersData($row);
        }
        return $dataSet;
    }
}