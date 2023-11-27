<?php
include 'connection.php'; // Include your database connection file

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->action)) {
        $action = $data->action;

        switch ($action) {
            case "addProduct":
                if (isset($data->product_name) && isset($data->unit_price) && isset($data->stock_quantity)) {
                    $productName = $data->product_name;
                    $unitPrice = $data->unit_price;
                    $stockQuantity = $data->stock_quantity;
            
                    // Check if the product with the same name already exists
                    $checkQuery = "SELECT * FROM products_table WHERE product_name = '$productName'";
                    $checkResult = $con->query($checkQuery);
            
                    if ($checkResult->num_rows > 0) {
                        // Product with the same name already exists
                        echo "exists";
                    } else {
                        // Insert data into the products_table
                        $sql = "INSERT INTO products_table (product_name, unit_price, stock_quantity) VALUES ('$productName', '$unitPrice', '$stockQuantity')";
            
                        if ($con->query($sql) === TRUE) {
                            echo "success";
                        } else {
                            echo "Error: " . $sql . "<br>" . $con->error;
                        }
                    }
                } else {
                    echo "Incomplete data for adding a product";
                }
                break;

            case "updateProduct":
                if (isset($data->product_id) && isset($data->product_name) && isset($data->unit_price) && isset($data->stock_quantity)) {
                    $productId = $data->product_id;
                    $productName = $data->product_name;
                    $unitPrice = $data->unit_price;
                    $stockQuantity = $data->stock_quantity;

                    // Update data in the products_table
                    $sql = "UPDATE products_table SET product_name='$productName', unit_price='$unitPrice', stock_quantity='$stockQuantity' WHERE product_id='$productId'";
            
                    if ($con->query($sql) === TRUE) {
                        echo "success";
                    } else {
                        echo "Error: " . $sql . "<br>" . $con->error;
                    }
                } else {
                    echo "Incomplete data for updating a product";
                }
                break;
            
            case "deleteProduct":
                if (isset($data->product_id)) {
                    $productId = $data->product_id;
            
                    // Delete data from the products_table
                    $sql = "DELETE FROM products_table WHERE product_id='$productId'";
            
                    if ($con->query($sql) === TRUE) {
                        echo "success";
                    } else {
                        echo "Error: " . $sql . "<br>" . $con->error;
                    }
                } else {
                    echo "Incomplete data for deleting a product";
                }
            break;
                

            default:
                echo "Invalid action";
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
