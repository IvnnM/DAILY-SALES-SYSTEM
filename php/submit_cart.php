<?php
include '../connection.php';

// Get the selected date from the URL parameter
$selectedDate = $_GET['selectedDate'];

// Check if a date is provided
if (!$selectedDate) {
    echo 'Please provide a valid date.';
    exit();
}

// Fetch cart items for the selected date
$cartSql = "SELECT * FROM cart_table WHERE DATE(timestamp) = '$selectedDate'";
$cartResult = $con->query($cartSql);

if ($cartResult) {
    // Check if the cart is not empty
    if ($cartResult->num_rows > 0) {
        // Initialize variables for total items and total price
        $totalItems = 0;
        $totalPrice = 0;

        // Iterate through cart items
        while ($cartRow = $cartResult->fetch_assoc()) {
            $totalItems += $cartRow['quantity'];
            $totalPrice += $cartRow['unit_price'] * $cartRow['quantity'];
        }

        // Insert into record_sale_table
        $insertSaleSql = "INSERT INTO record_sale_table (user_id, sale_date, total_items, total_price, timestamp) 
                          VALUES (1, '$selectedDate', $totalItems, $totalPrice, NOW())"; // Replace '1' with the actual user_id

        if ($con->query($insertSaleSql) === TRUE) {
            // Clear the cart for the selected date
            $clearCartSql = "DELETE FROM cart_table WHERE DATE(timestamp) = '$selectedDate'";
            $con->query($clearCartSql);

            echo 'success';
        } else {
            echo "Error: " . $sql . "<br>" . $con->error;
        }
    } else {
        echo 'Cart is empty.';
    }
} else {
    echo 'Error executing the cart query.';
}

// Close the database connection
$con->close();
?>
