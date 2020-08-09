<?php

error_reporting(E_STRICT);
session_start();

require_once("Repository/ProductRepository.class.php");
require_once("Controllers/ProductController.class.php");
require_once("Models/ProductModel.class.php");


if (isset($_SESSION["cart"])) {
    $cartItems = $_SESSION["cart"];
} else {
    $cartItems = array();
}
$result = false;


// #############################################
// $userCart = new ProductRepository($cartItems);
// ##############################################

// Comment lines below and uncomment line above to
// remove 'mock' repository pattern.
// #############################################
$store = new ProductRepository($cartItems);
$userCart = new ProductController($store);
// #############################################


// URL to include 'id' & 'action' & 'product name' (i.e - index.php?id=123&actionlink=example&product=clothes)
// 'id' uses simple hash of product name.
if (isset($_GET["id"]) && (isset($_GET["actionlink"])) && (isset($_GET["product"]))) {

    $productName = $_GET["product"];
    switch ($_GET['actionlink']) {
        case 'addItem':
            $productPrice = 0;
            foreach ($products as $product) {
                if (in_array($productName, $product, TRUE)) {
                    $productPrice = $product["price"];
                    break;
                }
            }
            if ($productPrice) {
                $result = $userCart->addItem($productName, $productPrice);
            }
            break;
        case  'removeItem':
            $result = $userCart->removeItem($productName);
            break;
        default:
            break;
    }
    if ($result) {
        $_SESSION['cart'] = $userCart->getItems();
    }

    // comment line to view URL links
    header("location:" . $_SERVER['PHP_SELF']);

    // Used for debugging --> Hide Cart functionality
} else if (($_GET["actionlink"])) {
    switch ($_GET["actionlink"]) {
        case 'hideCart':
            $userCart->hideCart();
        default:
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <title>ezyVet Assignment</title>
</head>

<body style="min-height: auto;">
    <div class="jumbotron text-center bg-dark text-white">
        <h1>Ezyvet</h1>
        <p>PHP Shopping Cart</p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-5 col-12">
                <h3>Product List</h3>
                <hr>
                <table class="table table-striped table-responsive-sm display">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th class="text-center">Price (NZD)</th>
                            <th class="text-center">Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <form method="post" action="index.php?id=<?php echo hash('ripemd160', $product['name']) ?>&actionlink=addItem&product=<?php echo $product['name'] ?>">
                                <tr>
                                    <td><?php echo $product['name'] ?></td>
                                    <td class="text-center">$<?php echo number_format($product['price'], 2) ?></td>
                                    <td class="text-center"><input type="submit" value="Add" class="btn btn-primary" /></td>
                                </tr>
                            </form>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div id="reload_body" class="col">
                <h3>Cart Items</h3>
                <hr>
                <?php if ($userCart->checkCart()) : ?>
                    <div class="alert alert-danger text-center">
                        <strong>Empty Cart!</strong> You have not added any items
                    </div>
                <?php else : ?>
                    <table class="table table-striped table-bordered table-responsive-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Total (NZD)</th>
                                <th class="text-center">Link</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($userCart->getItems() as $item) : ?>
                                <form method="post" action="index.php?id=<?php echo hash('ripemd160', $product['name']) ?>&actionlink=removeItem&product=<?php echo $item['name'] ?>">
                                    <tr>
                                        <td class="text-center"><?php echo isset($item["name"]) ? $item["name"] : '' ?></td>
                                        <td class="text-center">$<?php echo isset($item["unit_price"]) ? number_format($item["unit_price"], 2) : '' ?></td>
                                        <td class="text-center"><?php echo isset($item["quantity"]) ? $item["quantity"] : '' ?></td>
                                        <td class="text-center">$<?php echo isset($item['overall_unit_cost']) ? number_format($item["overall_unit_cost"], 2) : '' ?></td>
                                        <td class="text-center"><input type="submit" value="Remove" class="btn btn-danger" /></td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <div class="col text-center">
            <a href="index.php?actionlink=hideCart" class="btn btn-warning" type="button">Hide Cart</a>
        </div>
        <div class="col text-right">
            <hr>
            <h3>Summary</h3>
            <hr>
            Overall Cost : $ <strong><?php echo $userCart->getOverallExpenditure() ?></strong> NZD
            <br>
        </div>
    </div>
</body>

</html>
<footer class="bg-dark text-white mt-4" style="min-height:auto;">
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col text-center">
                <h5>GitHub <a href="https://github.com/zipyx/ezyvet-shopping-cart" target="_NEW">Repository Here</h5>
            </div>
        </div>
    </div>
</footer>