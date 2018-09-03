<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 28/08/2018
 * Time: 07:57
 */

require_once ('Test/Database1.php');

class TestItems
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
            $dataSet[] = $row;
        }
        return $dataSet;
    }
}
//SkCeny