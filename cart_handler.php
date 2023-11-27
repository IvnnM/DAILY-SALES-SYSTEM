<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action)) {
        $action = $data->action;

        switch ($action) {
            case "addSoldItems":
                $productId = $data->product_id;
                $quantity = $data->quantity;

                // Check if there is enough stock in the products_table
                $checkStockQuery = "SELECT stock_quantity FROM products_table WHERE product_id = $productId";
                $checkStockResult = $con->query($checkStockQuery);

                if ($checkStockResult->num_rows > 0) {
                    $row = $checkStockResult->fetch_assoc();
                    $stockQuantity = $row['stock_quantity'];

                    if ($stockQuantity >= $quantity) {
                        // There is enough stock, proceed with the addition
                        $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity - $quantity WHERE product_id = $productId";
                        $con->query($updateProductsQuery);

                        $insertCartQuery = "INSERT INTO cart_table (product_id, unit_price, quantity, timestamp) 
                                            VALUES ($productId, $data->unit_price, $quantity, NOW())";
                        $con->query($insertCartQuery);

                        echo "success";
                    } else {
                        // Insufficient stock
                        echo "Insufficient stock";
                    }
                } else {
                    // Product not found
                    echo "Product not found";
                }
                break;

            case "deleteSoldItems":
                $productId = $data->product_id;
            
                // Fetch quantity from cart_table
                $fetchQuantityQuery = "SELECT quantity FROM cart_table WHERE product_id = $productId";
                $fetchQuantityResult = $con->query($fetchQuantityQuery);
            
                if ($fetchQuantityResult->num_rows > 0) {
                    $row = $fetchQuantityResult->fetch_assoc();
                    $quantity = $row['quantity'];
            
                    // Update products_table
                    $updateProductsQuery = "UPDATE products_table SET stock_quantity = stock_quantity + $quantity WHERE product_id = $productId";
                    $con->query($updateProductsQuery);
            
                    // Delete row from cart_table
                    $deleteCartQuery = "DELETE FROM cart_table WHERE product_id = $productId";
                    $con->query($deleteCartQuery);
            
                    echo "success";
                } else {
                    // Item not found in cart_table
                    echo "Item not found in cart";
                }
                break;
              
        }
    } else {
        echo "Action not set";
    }
} else {
    echo "Invalid request method";
}

$con->close();
?>
