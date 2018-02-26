<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:03 PM
 */

class Product
{
    private $productName, $description, $price, $quantity, $imageName, $salePrice;

    function __construct($productName, $description, $price, $quantity=0, $imageName="", $salePrice=0)
    {
        $this->productName = $productName;
        $this->description = $description;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->imageName = $imageName;
        $this->salePrice = $salePrice;
    }

    /**
     * @return mixed
     */
    public function getProductName() : string
    {
        return $this->productName;
    }

    /**
     * @return mixed
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrice() : int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * @return int
     */
    public function getSalePrice(): int
    {
        return $this->salePrice;
    }
}