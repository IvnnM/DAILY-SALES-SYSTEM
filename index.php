<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari Store Inventory</title>
    <!--Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <!--Sweetalert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
    <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="container" >
    <h2>Sari-Sari Store Inventory</h2>
    <form id="productForm">
        <select id="productProductId" name="productProductId">
            <?php
            include 'connection.php'; // Include your database connection file

            $result = $con->query("SELECT product_id FROM products_table");

            echo "<option value=\"\">Select a Product</option>";

            while ($row = $result->fetch_assoc()) {
                echo "<option value=\"{$row["product_id"]}\">{$row["product_id"]}</option>";
            }

            // Close the database connection
            $con->close();
            ?>
        </select>

        <label for="productProductName">Product Name:</label>
        <input type="text" id="productProductName" name="productName">
    
        <label for="productUnitPrice">Unit Price:</label>
        <input type="number" id="productUnitPrice" name="unitPrice">
    
        <label for="productStockQuantity">Stock Quantity:</label>
        <input type="number" id="productStockQuantity" name="stockQuantity">
    
        <button type="button" id="addProductBtn">Add Product</button>
        <button type="button" id="updateProductBtn">Update Product</button>
        <button type="button" id="deleteProductBtn">Delete Product</button>
    </form>

    <div class="overflow-y-scroll" id="productList" style="height: 200px;">
        <!-- Product list will be displayed here -->
        <?php
        include 'connection.php';

        $sql = "SELECT * FROM products_table";
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) {
            echo "<table class='table table-dark table-hover' id='productTable'>";
            echo '
                <thead>
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Stock Quantity</th>
                    </tr>
                </thead>';
            while ($row = $result->fetch_assoc()) {
                echo '<tbody class="table-group-divider">';
                echo "<tr>";
                echo "<td>" . $row["product_id"] . "</td>";
                echo "<td>" . $row["product_name"] . "</td>";
                echo "<td>" . $row["unit_price"] . "</td>";
                echo "<td>" . $row["stock_quantity"] . "</td>";
                echo '</tbody>';
            }
            echo "</table>";
        } else {
            echo "No records found";
        }
        
        $con->close();
        ?>
    </div>
</div>

<div class="container" id="cart-container">
    <h2>Cart</h2>
    <form id="cartForm">
        <select id="cartProductId" name="cartProductId">
            <?php
            include 'connection.php';

            $result = $con->query("SELECT product_id FROM products_table");

            echo "<option value=\"\">Select a Product</option>";

            while ($row = $result->fetch_assoc()) {
                echo "<option value=\"{$row["product_id"]}\">{$row["product_id"]}</option>";
            }

            $con->close();
            ?>
        </select>
        
        <label for="cartProductName">Product Name:</label>
        <input type="text" id="cartProductName" name="cartProductName" readonly>
    
        <label for="cartUnitPrice">Unit Price:</label>
        <input type="number" id="cartUnitPrice" name="cartUnitPrice" readonly>
    
        <label for="cartSoldQuantity">Sold Item Quantity:</label>
        <input type="number" id="cartSoldQuantity" name="cartSoldQuantity">
    
        <button id="addSoldProductBtn">Add Sold Items</button>
        <button id="deleteSoldProductBtn">Delete Sold Items</button>

    </form>

    
<div>
    <label for="selectedDate">Select Date:</label>
    <input type="date" id="selectedDate" name="selectedDate">
    <button onclick="loadCart()">Load Cart</button>
</div>
<div class="overflow-y-scroll" id="cart" style="height: 200px;">
    <!-- Cart list will be displayed here -->
    <?php
    include 'connection.php';

    // Initialize $todayDate with the current date as a default
    $todayDate = date("Y-m-d");

    // Check if a date is provided by the user
    if (isset($_GET['selectedDate'])) {
        // Use the provided date if available and convert it to the correct format
        $selectedDate = $_GET['selectedDate'];
        $todayDate = date("Y-m-d", strtotime($selectedDate));
    }

    // Your existing SQL query
    $cartSql = "SELECT * FROM cart_table WHERE DATE(timestamp) = '$todayDate'";
    $cartResult = $con->query($cartSql);

    if ($cartResult->num_rows > 0) {
        echo "<table class='table table-dark table-hover' id='cartTable'>";
        echo '
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Product ID</th>
                    <th scope="col">Unit Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Timestamp</th>
                </tr>
            </thead>';
        while ($cartRow = $cartResult->fetch_assoc()) {
            echo '<tbody class="table-group-divider">';
            echo "<tr>";
            echo "<td>" . $cartRow["id"] . "</td>";
            echo "<td>" . $cartRow["product_id"] . "</td>";
            echo "<td>" . $cartRow["unit_price"] . "</td>";
            echo "<td>" . $cartRow["quantity"] . "</td>";
            echo "<td>" . $cartRow["timestamp"] . "</td>";
            echo '</tbody>';
        }
        echo "</table>";
    } else {
        echo "Cart is empty";
    }

    $con->close();
    ?>
</div>



    
    <button id="submitCartBtn">Submit Cart</button>
</div>


<script>
    function loadCart() {
        // Get the selected date from the input field
        const selectedDate = document.getElementById('selectedDate').value;

        // Reload the page with the selected date as a parameter
        window.location.href = 'index.php?selectedDate=' + selectedDate;
    }
</script>

<script>
    $(document).ready(function() {
        // Event handler for regular product selection
        $('#productProductId').change(function() {
            var selectedOption = $(this).val();
            handleProductSelection(selectedOption, 'product');
        });

        // Event handler for cart product selection
        $('#cartProductId').change(function() {
            var selectedOption = $(this).val();
            handleProductSelection(selectedOption, 'cart');
        });

        function handleProductSelection(selectedOption, prefix) {
            var table = $('#productTable');

            // Show all rows
            table.find('tbody tr').show();

            if (selectedOption !== "") {
                // Hide rows where the product ID does not match the selected option
                table.find('tbody tr').filter(function() {
                    return $(this).find('td:first-child').text() !== selectedOption;
                }).hide();

                // Find the selected row
                var selectedRow = table.find('tbody tr').filter(function() {
                    return $(this).find('td:first-child').text() === selectedOption;
                });

                // Populate input boxes with corresponding values from the selected row
                var productName = selectedRow.find('td:eq(1)').text();
                var unitPrice = selectedRow.find('td:eq(2)').text();
                var stockQuantity = selectedRow.find('td:eq(3)').text();

                // Set values in the input boxes with the specified prefix
                $('#' + prefix + 'ProductName').val(productName);
                $('#' + prefix + 'UnitPrice').val(unitPrice);
                $('#' + prefix + 'StockQuantity').val(stockQuantity);
            } else {
                // Clear input boxes if the selected option is empty
                $('#' + prefix + 'ProductName, #' + prefix + 'UnitPrice, #' + prefix + 'StockQuantity').val('');
            }
        }
    });
</script>






<!-- Include your script.js file -->
<script src="script.js"></script>
</body>
</html>
