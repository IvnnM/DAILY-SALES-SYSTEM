// Function to handle the product action (add/update/delete)
function handleProductAction(action) {
    const productId = document.getElementById('productProductId').value;
    const productName = document.getElementById('productProductName').value;
    const unitPrice = document.getElementById('productUnitPrice').value;
    const stockQuantity = document.getElementById('productStockQuantity').value;

    // Check if any of the required fields is empty or below 0
    if (!productId || !productName || !unitPrice || !stockQuantity || unitPrice < 0 || stockQuantity < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields with valid values!',
        });
        return;
    }
    
    const data = {
        action: action,
        product_id: productId,
        product_name: productName,
        unit_price: unitPrice,
        stock_quantity: stockQuantity
    };

    Swal.fire({
        title: `Are you sure you want to ${action.toUpperCase()}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${action.toUpperCase()}`,
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            executeProductAction(action, data);
        }
    });
}
// Function to execute the product action (add/update/delete)
function executeProductAction(action, data) {
    const url = 'product_handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: `${action.toUpperCase()} successfully!`,
                showConfirmButton: false,
                timer: 1500
            });

            // Clear input boxes
            document.getElementById('productProductId').value = '';
            document.getElementById('productProductName').value = '';
            document.getElementById('productUnitPrice').value = '';
            document.getElementById('productStockQuantity').value = '';
        } else if (result === 'exists') {
            // Show SweetAlert for existing product
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Product with the same name already exists!',
            });
        } else {
            // Handle other cases if needed
            console.log(`${action.toUpperCase()} failed`);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}

// Event listeners for buttons
document.getElementById("addProductBtn").addEventListener("click", function() {
    handleProductAction('addProduct');
});

document.getElementById("updateProductBtn").addEventListener("click", function() {
    handleProductAction('updateProduct');
});

document.getElementById("deleteProductBtn").addEventListener("click", function() {
    handleProductAction('deleteProduct');
});

//

// Function to handle the cart action (add/delete)
function handleCartAction(action) {
    const cartProductId = document.getElementById('cartProductId').value;
    const cartProductName = document.getElementById('cartProductName').value;
    const cartUnitPrice = document.getElementById('cartUnitPrice').value;
    const cartSoldQuantity = document.getElementById('cartSoldQuantity').value;

    // Check if any of the required fields is empty or below 0
    if (!cartProductId || !cartProductName || !cartUnitPrice || cartUnitPrice < 0 || cartSoldQuantity < 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please fill in all required fields with valid values!',
        });
        return;
    }

    const data = {
        action: action,
        product_id: cartProductId,
        product_name: cartProductName,
        unit_price: cartUnitPrice,
        quantity: cartSoldQuantity
    };

    Swal.fire({
        title: `Are you sure you want to ${action.toUpperCase()}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${action.toUpperCase()}`,
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            executeCartAction(action, data);
        }
    });
}

// Function to execute the cart action (add/delete)
function executeCartAction(action, data) {
    const url = 'cart_handler.php';

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.text())
    .then(result => {
        console.log(result); // Log the result from the server
        if (result === 'success') {
            // Show SweetAlert success message
            Swal.fire({
                icon: 'success',
                title: `${action.toUpperCase()} successfully!`,
                showConfirmButton: false,
                timer: 1500
            });

            // Clear input boxes
            document.getElementById('cartProductId').value = '';
            document.getElementById('cartProductName').value = '';
            document.getElementById('cartUnitPrice').value = '';
            document.getElementById('cartSoldQuantity').value = '';
        } else {
            // Show SweetAlert for item not found or other errors
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result || 'Unknown error occurred',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Handle errors if any
    });
}

// Event listener for add sold items button
document.getElementById("addSoldProductBtn").addEventListener("click", function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    handleCartAction('addSoldItems');
});

// Event listener for delete sold items button
document.getElementById("deleteSoldProductBtn").addEventListener("click", function(event) {
    // Prevent the default form submission behavior
    event.preventDefault();

    handleCartAction('deleteSoldItems');
});

//

// Function to handle the submit cart action
function handleSubmitCart() {
    // Get the selected date from the input field
    const selectedDate = document.getElementById('selectedDate').value;

    // Check if a date is selected
    if (!selectedDate) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please select a date!',
        });
        return;
    }

    // Confirm with the user before submitting the cart
    Swal.fire({
        title: 'Submit Cart?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then((result) => {
        if (result.isConfirmed) {
            // Make a request to your PHP script to handle the cart submission
            fetch('submit_cart.php?selectedDate=' + selectedDate)
                .then(response => response.text())
                .then(result => {
                    // Log the result from the server
                    console.log(result);

                    // Check for 'error' in the response
                    if (result === 'success') {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Cart submitted successfully!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Optionally, you can clear the cart or take other actions
                    } else if (result === 'Cart is empty.') {
                        // Show cart is empty message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Cart is empty.',
                        });
                    } else if (result === 'Error executing the cart query.') {
                        // Show error executing cart query message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error executing the cart query.',
                        });
                    } else {
                        // Show other error messages
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result || 'Unknown error occurred',
                        });
                        // Optionally, you can clear the cart or take other actions
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Handle errors if any
                });
        }
    });
}

// Add an event listener to the "Submit Cart" button
document.getElementById("submitCartBtn").addEventListener("click", function (event) {
    // Prevent the default button click behavior
    event.preventDefault();

    // Call the function to handle the submit cart action
    handleSubmitCart();
});

