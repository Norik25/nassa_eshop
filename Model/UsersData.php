<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 06/07/2018
 * Time: 14:22
 */

class UsersData
{
    public function __construct($dbRow)
    {
        $this->userID = $dbRow['user_id'];
        $this->companyName = $dbRow['user_companyName'];
        $this->userEmail = $dbRow['user_email'];
        $this->userPassword = $dbRow['user_password'];
        $this->userPhone = $dbRow['user_phone'];
        $this->userICO = $dbRow['user_ico'];
        $this->userDIC = $dbRow['user_dic'];
        $this->userAddress = $dbRow['user_address'];
        $this->userCity = $dbRow['user_city'];
        $this->userPostCode = $dbRow['user_postCode'];
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return mixed
     */
    public function getUserAddress()
    {
        return $this->userAddress;
    }

    /**
     * @return mixed
     */
    public function getUserCity()
    {
        return $this->userCity;
    }

    /**
     * @return mixed
     */
    public function getUserDIC()
    {
        return $this->userDIC;
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @return mixed
     */
    public function getUserICO()
    {
        return $this->userICO;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @return mixed
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }

    /**
     * @return mixed
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * @return mixed
     */
    public function getUserPostCode()
    {
        return $this->userPostCode;
    }
}