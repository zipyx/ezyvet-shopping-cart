<?php declare(strict_types=1);

interface IProductRepository {
    
    public function getItems() : array;
    public function addItem($productName) : bool;
    public function removeItem($productName) : bool;
    public function getOverallExpenditure() : string;
    public function checkCart();
    public function hideCart();

}
?>