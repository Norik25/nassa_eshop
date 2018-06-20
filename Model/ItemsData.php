<?php
/**
 * Created by PhpStorm.
 * User: oem
 * Date: 15/06/2018
 * Time: 16:27
 */

class ItemsData
{
    public function __construct($dbRow)
    {
        $this->itemID = $dbRow['item_id'];
        $this->itemName = $dbRow['item_name'];
        $this->itemType = $dbRow['item_type'];
        $this->itemColor = $dbRow['item_color'];
        $this->itemPrice = $dbRow['item_price'];
        $this->itemBrand = $dbRow['item_brand'];
        $this->itemSize = $dbRow['item_size'];
        $this->itemQuantity = $dbRow['item_quantity'];
    }

    /**
     * @return mixed
     */
    public function getItemBrand()
    {
        return $this->itemBrand;
    }

    /**
     * @return mixed
     */
    public function getItemColor()
    {
        return $this->itemColor;
    }

    /**
     * @return mixed
     */
    public function getItemID()
    {
        return $this->itemID;
    }

    /**
     * @return mixed
     */
    public function getItemName()
    {
        return $this->itemName;
    }

    /**
     * @return mixed
     */
    public function getItemPrice()
    {
        return $this->itemPrice;
    }

    /**
     * @return mixed
     */
    public function getItemQuantity()
    {
        return $this->itemQuantity;
    }

    /**
     * @return mixed
     */
    public function getItemSize()
    {
        return $this->itemSize;
    }

    /**
     * @return mixed
     */
    public function getItemType()
    {
        return $this->itemType;
    }

}