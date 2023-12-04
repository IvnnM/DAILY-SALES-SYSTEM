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
  <div class="row align-items-start">
    
    <div class="col" id="left-container">
      <div class="d-block p-2 px-3" style="height: 8vh;">
        <h2>SARI-SARI STORE INVENTORY</h2>
      </div>
      <div class="d-block p-3 pb-0 overflow-y-auto" id="product-container" style="height: 29vh;" >

        <form id="productForm">
          <div class="mb-3">
              <select id="productProductId" name="productProductId" class="form-select" aria-label="Product ID">
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
          </div>

          <div class="row">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="productProductName" name="productName" placeholder="Product Name">
                    <label for="productProductName">Product Name:</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="productUnitPrice" name="unitPrice" placeholder="Unit Price">
                    <label for="productUnitPrice">Unit Price:</label>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-floating mb-3">
                    <input type="number" class="form-control" id="productStockQuantity" name="stockQuantity" placeholder="Stock Quantity">
                    <label for="productStockQuantity">Stock Quantity:</label>
                </div>
            </div>
          </div>


          <button type="button" onclick="handleProductAction('addProduct')" class="btn">Add Product</button>
          <button type="button" onclick="handleProductAction('updateProduct')" class="btn update">Update Product</button>
          <button type="button" onclick="handleProductAction('deleteProduct')" class="btn delete">Delete Product</button>
        </form>


        
      </div>
      <div class="d-block p-2">
          <div class="overflow-y-auto p-2" id="productList" style="background-color: #cfe2ff; border-radius:8px; height: 56vh;">
            <!-- Product list will be displayed here -->
            <?php
            include 'connection.php';

            $sql = "SELECT * FROM products_table";
            $result = $con->query($sql);
            
            if ($result->num_rows > 0) {
                echo "<table class='table table-primary' id='productTable'>";
                echo '
                    <thead>
                        <tr>
                            <th scope="col">Product ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Stock</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tbody class="table-group-divider">';
                    echo "<tr>";
                    echo "<td>" . $row["product_id"] . "</td>";
                    echo "<td>" . $row["product_name"] . "</td>";
                    echo "<td>" . $row["unit_price"] . "</td>";
                    echo "<td>" . $row["stock_quantity"] . "</td>";

                    echo "<td><button id='addStockBtn' onclick='addStock(" . $row["product_id"] . ", \"" . $row["product_name"] . "\")'>Add Stock</button></td>";


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
    </div>
    <div class="col" id="right-container">
      <div class="d-block p-2 px-3" style="height: 8vh;">
        <h2>SOLD ITEMS</h2>
      </div>
      <div class="d-block p-3 pb-0 overflow-y-auto" id="cart-container" style="height: 42vh;">
      <form id="cartForm">
        <div class="mb-3">
            <select id="cartProductId" name="cartProductId" class="form-select" aria-label="Product ID">
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
        </div>

        <div class="mb-3 row">
          <div class="col-md-7">
              <div class="form-floating">
                  <input type="text" id="cartProductName" name="cartProductName" readonly class="form-control">
                  <label for="cartProductName">Product Name:</label>
              </div>
          </div>

          <div class="col-md-5">
              <div class="form-floating">
                  <input type="number" id="cartUnitPrice" name="cartUnitPrice" readonly class="form-control">
                  <label for="cartUnitPrice">Unit Price:</label>
              </div>
          </div>
        </div>

        <div class="form-floating mb-3">
          <input type="number" id="cartSoldQuantity" name="cartSoldQuantity" class="form-control">
          <label for="cartSoldQuantity">Sold Item Quantity:</label>
        </div>


        <button type="button" onclick="handleCartAction('addSoldItems')" class="btn mb-1">Add Sold Items</button>
        <button type="button" onclick="handleCartAction('deleteSoldItems')" class="btn delete mb-1">Delete Sold Items</button>
        <a href="dashboard.php" class="btn mb-1" role="button" id="reports">Go To Reports</a>

      </form>

      </div>
      <div class="d-block p-2">
        <div class="overflow-y-auto p-2" id="soldProductList" style="background-color:#cfe2ff; border:solid #75acd2; border-radius:8px; height: 43vh">
            <form method="get">
                <input type="date" id="selectedDate" name="selectedDate" value="<?php echo isset($_GET['selectedDate']) ? htmlspecialchars($_GET['selectedDate']) : date('Y-m-d'); ?>" onchange="this.form.submit()">
                <button id="submitCartBtn">Create Report</button>
            </form>
          
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
          $cartSql = "SELECT cart_table.id, cart_table.product_id, products_table.product_name, cart_table.unit_price, cart_table.quantity,     DATE(cart_table.timestamp) AS date FROM cart_table
          INNER JOIN products_table ON cart_table.product_id = products_table.product_id
          WHERE DATE(cart_table.timestamp) = '$todayDate'";
          $cartResult = $con->query($cartSql);

          if ($cartResult->num_rows > 0) {
              echo "<table class='table table table-primary overflow-y-auto' id='cartTable'>";
              echo '
                  <thead>
                      <tr>
                          <th scope="col">ID</th>
                          <th scope="col">Product</th>
                          <th scope="col">Unit Price</th>
                          <th scope="col">Quantity</th>
                          <th scope="col">Date</th>
                      </tr>
                  </thead>';
              while ($cartRow = $cartResult->fetch_assoc()) {
                  echo '<tbody class="table-group-divider">';
                  echo "<tr>";
                  echo "<td>" . $cartRow["id"] . "</td>";
                  echo "<td>" . $cartRow["product_name"] . "</td>";
                  echo "<td>" . $cartRow["unit_price"] . "</td>";
                  echo "<td>" . $cartRow["quantity"] . "</td>";
                  echo "<td>" . $cartRow["date"] . "</td>";
                  echo '</tbody>';
              }
              echo "</table>";
          } else {
              echo "<br>Table is empty";
          }

          $con->close();
          ?>
        </div>
      </div>
     

    </div>
  </div>
    
</div>



<script>
    function loadCart() {
        // Get the selected date from the input field
        const selectedDate = document.getElementById('selectedDate').value;

        // Reload the page with the selected date as a parameter
        window.location.href = 'admin_page.php?selectedDate=' + selectedDate;
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
