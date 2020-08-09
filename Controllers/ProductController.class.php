<?php declare(strict_types=1);

include_once 'Interface/IProductRepository.php';

class ProductController {

    private $_unitOfWork;

    public function __construct(IProductRepository $_unitOfWork)
    {
        $this->_unitOfWork = $_unitOfWork;
    }

    public function getItems() : array {
        return $this->_unitOfWork->getItems();
    }

    public function addItem($productName) : bool {
        return $this->_unitOfWork->addItem($productName);
    }

    public function removeItem($productName) : bool {
        return $this->_unitOfWork->removeItem($productName);
    }

    public function getOverallExpenditure() : string {
        return $this->_unitOfWork->getOverallExpenditure();
    }

    public function checkCart() {
        return $this->_unitOfWork->checkCart();
    }

    public function hideCart() {
        $this->_unitOfWork->hideCart();
    }
}