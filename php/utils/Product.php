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

    /**
     * @return mixed
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @return mixed
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPrice(): int
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

    public function makeHTMLCode()
    {
        return
            "<div class='card'>
                <img class='card-img-top' src='{$this->getImageName()}' alt='Card image'>
                <div class='card-body'>
                    <h4 class='card-title'>{$this->getProductName()}</h4>
                    <p class='card-text'>{$this->getDescription()}</p>
                    <p class='card-text'>"
            . $this->getPriceToShow($this->getSalePrice(), $this->getPrice())
            . "</p>
                </div>
            </div>";
    }

    private function getPriceToShow($salePrice, $origPrice)
    {
        return ($salePrice != 0) ? "Price: <s>\$$origPrice</s> \$$salePrice" : "Price: \$$origPrice";
    }
}