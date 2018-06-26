<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 15/06/2018
 * Time: 16:27
 */

require_once ('Model/Database.php');
require_once ('Model/ItemsData.php');

class ItemsDataSet
{
    protected $_dbHandle, $dbInstance;

    public function __construct()
    {
        $this->dbInstance = Database::getInstance();
        $this->_dbHandle = $this->dbInstance->getdbConnection();
    }

    /**
     * Fetches all Items from the database
     * @return array of Items
     */
    public function fetchAllData() {
        $sqlQuery = 'SELECT * FROM NASSA_items';

        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare statement
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new ItemsData($row);
        }
        return $dataSet;
    }

    /**
     * Receives the query as an argument and gets data by that MySql query
     * @param $query of Type String
     * @return array of Items of type ItemsData
     */
    public function getDataByQuery($query) {

        $statement = $this->_dbHandle->prepare($query); //prepare statement
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new ItemsData($row);
        }
        return $dataSet;
    }

    public function getDataByLimit($start, $limit) {
        $sqlQuery = "SELECT * FROM NASSA_items LIMIT :start, :limit";
        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare statement
        $statement->bindParam(':start', $start, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);

        $statement->execute();



        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new ItemsData($row);
        }
        return $dataSet;
    }

    public function getItemsByID($itemID) {

        $sqlQuery = "SELECT * FROM NASSA_items WHERE item_id=:rowID";
        $statement = $this->_dbHandle->prepare($sqlQuery); //prepare statement
        $statement->bindParam(':rowID', $itemID, PDO::PARAM_STR);
        $statement->execute();

        $dataSet = [];

        while ($row = $statement->fetch()) {
            $dataSet[] = new ItemsData($row);
        }
        return $dataSet;
    }



}