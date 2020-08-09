<?php declare(strict_types=1);

include_once 'Models/ProductModel.class.php';
include_once 'Interface/IProductRepository.php';

class ProductRepository implements IProductRepository {

    private $items = array();

    public function __construct(array $items = array())
    {
        $this->items = $items;
    }

    public function getItems() : array {
        return $this->items ? $this->items : array();
    }

    public function addItem($productName, $productPrice) : bool {

        $quantity = 1;
        $confirm = false;
        
        try {
            
            if (@$this->items[$productName]) {
                $quantity += $this->items[$productName]["quantity"];
            }
            
            $productTotalCost = round($productPrice * $quantity, 2);
            $addToCart = array(
                'name' => $productName, 
                'unit_price' => $productPrice, 
                'quantity' => $quantity, 
                'overall_unit_cost' => $productTotalCost,
            );

            // mock validation against product table
            if (static::validateProductExists($addToCart)) {
                $this->items[$productName] = $addToCart;
                $confirm = true;
            }
            else {
                error_log("Error Log: addItem : validateProduct Failed.", 3, "error_logs.txt");
            }

        } catch (Exception $exception) {
            error_log("Error Log: addItem function --> ".$exception->getMessage(), 3, "error_logs.txt");
        }
        return $confirm;
    }

    public function removeItem($productName) : bool {
        $confirm = false;
        try {
            unset($this->items[$productName]);
            $confirm = true;
        } catch (Exception $exception) {
            error_log("Error Log: removeItem function --> ".$exception->getMessage(), 3, "error_logs.txt");
            return $confirm;
        }
        return $confirm;
    }

    // Get Overall cost of items added to cart
    public function getOverallExpenditure() : string {
        $totalCostValue = 0;
        foreach($this->items as $product) {
            $totalCostValue += $product["overall_unit_cost"];
        }
        return number_format(static::checkValue($totalCostValue), 2);
    }

    // Check for items in cart
    public function checkCart() {
        return empty($this->items);
    }

    // Method for debugging --> Hide Cart display
    public function hideCart() {
        $this->items = Array();
    }

    // mock validation check against existing product array table
    private static function validateProductExists(array $productToCheck) : bool {
        foreach($GLOBALS["products"] as $item) {
            if (in_array($productToCheck["name"], $item, TRUE)) {
                return true;
            }
        }
        return false;
    }

    // check cost value to ensure value is calculated accurately
    private static function checkValue($value) : float {
        $formula = pow(10, 2);
        if ($value > 0) {
            return Floor($value * $formula) / $formula;
        } else {
            return ceil($value * $formula) / $formula;
        }
    }
}