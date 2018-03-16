<?php
/**
 * Created by PhpStorm.
 * User: Palash
 * Date: 2/26/2018
 * Time: 4:03 PM
 */

class Product
{
    private $productID, $productName, $description, $price, $quantity, $imageName, $salePrice;

    /**
     * @return String product name
     */
    public function getProductName(): string
    {
        return trim($this->productName);
    }

    /**
     * @return String description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int price
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int quantity
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string file path
     */
    public function getImageName(): string
    {
        return $this->imageName;
    }

    /**
     * @return int sale price
     */
    public function getSalePrice(): int
    {
        return $this->salePrice;
    }

    /**
     * @return string html card for product information along with submit button
     */
    public function makeHTMLCode()
    {
        return
            "<div class='card'>
                <div class='row'>
                    <div class='col-md-5 no-padding'>
                        <img class='card-img-top' src='{$this->getImageName()}' alt='Card image'>
                    </div>
                    <div class='col-md-7 card-body'>
                    <h4 class='card-title'>{$this->getProductName()}</h4>
                    <p class='card-text'>{$this->getDescription()}</p>
                    <p class='card-text'>"
            . $this->getPriceToShow()
            . "</p>
                    <p class='card-text'>Quantity left: 
                    " . $this->getQuantity() . "
                    </p>
                    <form method='post'>
                        <button type='submit' class='btn btn-success' name='addToCart' value='{$this->productID}' " . ($this->getQuantity() == 0 ? 'disabled' : '') . ">Add to cart</button>
                    </form>
                </div>
                </div>
            </div>";
    }

    /**
     * @return string "Price: " + sale price or original price
     */
    private function getPriceToShow()
    {
        return ($this->salePrice == 0) ? "Price: \$$this->price" : "Price: <s>\$$this->price</s> \$$this->salePrice";
    }
}