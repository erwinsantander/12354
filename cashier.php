<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern POS Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        .total {
            font-size: 1.5em;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        #errorMessage {
            color: red;
            margin-top: 10px;
        }

        .payment-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #sukliDisplay {
            font-size: 1.2em;
            font-weight: bold;
        }

        .print-button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .print-button:hover {
            background-color: #0056b3;
        }

        @media print {
            body {
                font-size: 10px;
                width: 80mm;
                margin: 0;
                padding: 5mm;
                box-sizing: border-box;
            }

            table {
                width: 100%;
                border: none;
                table-layout: fixed;
            }

            th,
            td {
                padding: 3px;
                font-size: 10px;
                text-align: left;
                word-wrap: break-word;
                overflow: hidden;
            }

            .total,
            .payment-section,
            #barcodeInput,
            #customerPayment,
            .print-button {
                font-size: 10px;
                text-align: center;
            }

            #barcodeInput,
            #customerPayment {
                display: none;
            }

            .print-button {
                display: none;
            }
        }
        
    </style>

<?php
        require_once('includes/load.php');
        // Checkin What level user has permission to view this page
        page_require_level(2);

        // Check for messages from previous redirects
        if (isset($_SESSION['message'])) {
            $msg = json_decode($_SESSION['message'], true);
            unset($_SESSION['message']);
        }
        ?>
</head>

<body>
    <div class="container">
        <h1><i class="fas fa-barcode"></i> ANC Minimart</h1>
        <input type="text" id="barcodeInput" class="form-control" placeholder="Scan Barcode Here" autofocus>
        <div id="errorMessage"></div>
        <table class="table table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>Barcode</th>
                    <th>Product Name</th>
                    <th>Sale Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <!-- Product rows will be added here dynamically -->
            </tbody>
        </table>
        <div class="total" id="totalPrice">Total: ₱0.00</div>
        <div class="payment-section row mt-3">
            <div class="col-md-6 d-flex align-items-center">
                <label for="customerPayment" class="form-label me-2 mb-0">Customer Payment:</label>
                <input type="number" id="customerPayment" class="form-control" placeholder="Enter payment amount"
                    step="0.01" min="0">
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div id="sukliDisplay" class="ms-3 p-2 bg-light text-success rounded"
                    style="background-color: #d4edda;">
                    Change: ₱0.00
                </div>
            </div>
        </div>
        <form id="receiptForm" action="receipt.php" method="POST">
            <input type="hidden" name="cartData" id="cartData">
            <input type="hidden" name="total" id="total">
            <input type="hidden" name="payment" id="payment">
            <input type="hidden" name="change" id="change">
            <button type="button" class="print-button mt-3" onclick="redirectToReceipt()">
                <i class="fas fa-print"></i> Print Receipt
            </button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let cart = [];
        const barcodeInput = document.getElementById('barcodeInput');
        const productTableBody = document.getElementById('productTableBody');
        const totalPriceElement = document.getElementById('totalPrice');
        const errorMessageElement = document.getElementById('errorMessage');
        const customerPaymentInput = document.getElementById('customerPayment');
        const sukliDisplayElement = document.getElementById('sukliDisplay');

        const BASE_URL = 'get_product.php';

        barcodeInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                const barcode = barcodeInput.value.trim();
                barcodeInput.value = '';
                errorMessageElement.textContent = '';
                fetchProductByBarcode(barcode);
            }
        });

        customerPaymentInput.addEventListener('input', calculateSukli);

        function fetchProductByBarcode(barcode) {
            const url = `${BASE_URL}?barcode=${encodeURIComponent(barcode)}`;

            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(product => {
                    if (product && product.barcode) {
                        addToCart(product);
                    } else {
                        errorMessageElement.textContent = 'Product not found or invalid response';
                    }
                })
                .catch(error => {
                    errorMessageElement.textContent = `Error: ${error.message}. Please check your network connection and server configuration.`;
                });
        }

        function addToCart(product) {
            const salePrice = Number(product.sale_price);
            const existingProduct = cart.find(item => item.barcode === product.barcode);

            if (existingProduct) {
                existingProduct.quantity += 1;
                existingProduct.total = Number((existingProduct.quantity * salePrice).toFixed(2));
            } else {
                cart.push({
                    barcode: product.barcode,
                    name: product.name,
                    price: salePrice,
                    quantity: 1,
                    total: salePrice
                });
            }

            updateTable();
        }

        function updateTable() {
            productTableBody.innerHTML = '';
            let total = 0;

            cart.forEach((item, index) => {
                const itemTotal = Number(item.total.toFixed(2));

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.barcode}</td>
                    <td>${item.name}</td>
                    <td>₱${item.price.toFixed(2)}</td>
                    <td>
                        <input type="number" 
                               value="${item.quantity}" 
                               min="1" 
                               class="form-control quantity-input" 
                               data-index="${index}">
                    </td>
                    <td>₱${itemTotal.toFixed(2)}</td>
                `;
                productTableBody.appendChild(row);
                total += itemTotal;
            });

            totalPriceElement.textContent = `Total: ₱${total.toFixed(2)}`;
            calculateSukli();

            document.querySelectorAll('.quantity-input').forEach(input => {
                input.addEventListener('change', handleQuantityChange);
            });
        }

        function handleQuantityChange(event) {
            const input = event.target;
            const newQuantity = parseInt(input.value, 10);
            const index = parseInt(input.getAttribute('data-index'), 10);

            if (isNaN(newQuantity) || newQuantity < 1) {
                input.value = cart[index].quantity;
                return;
            }

            cart[index].quantity = newQuantity;
            cart[index].total = Number((cart[index].quantity * cart[index].price).toFixed(2));
            updateTable();
        }

        function calculateSukli() {
            const total = parseFloat(totalPriceElement.textContent.replace('Total: ₱', ''));
            const payment = parseFloat(customerPaymentInput.value) || 0;

            if (payment >= total) {
                const sukli = payment - total;
                sukliDisplayElement.textContent = `Change: ₱${sukli.toFixed(2)}`;
                sukliDisplayElement.style.color = 'green';
            } else {
                sukliDisplayElement.textContent = 'Insufficient Payment';
                sukliDisplayElement.style.color = 'red';
            }
        }

        
        function reduceProductQuantities(cart) {
            const url = 'reduce_product_quantities.php';
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cart)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    console.log('Product quantities updated successfully');
                } else {
                    console.error('Failed to update product quantities:', result.message);
                }
            })
            .catch(error => {
                console.error('Error reducing product quantities:', error);
            });
        }


        function redirectToReceipt() {
            const cartDataInput = document.getElementById('cartData');
            const totalInput = document.getElementById('total');
            const paymentInput = document.getElementById('payment');
            const changeInput = document.getElementById('change');

            const total = parseFloat(totalPriceElement.textContent.replace('Total: ₱', ''));
            const payment = parseFloat(customerPaymentInput.value) || 0;
            const change = payment >= total ? payment - total : 0;

            cartDataInput.value = JSON.stringify(cart);
            totalInput.value = total.toFixed(2);
            paymentInput.value = payment.toFixed(2);
            changeInput.value = change.toFixed(2);

            reduceProductQuantities(cart);
            document.getElementById('receiptForm').submit();
        }

    </script>
      <?php if (isset($msg)): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: '<?php echo $msg['type']; ?>',
                title: '<?php echo $msg['text']; ?>',
                position: 'top-end',
                toast: true,
                showConfirmButton: false,
                timer: 3000,
                customClass: {
                    popup: 'swal2-rectangle',
                    title: 'swal2-title-normal',
                    icon: 'swal2-icon-inline', // Add this line
                    content: 'swal2-content-inline' // Add this line
                }
            });
        </script>
        <style>
            /* Adjust size for success icon */
            .swal2-success {
                width: 8px;
                /* Adjust width */
                height: 8px;
                /* Adjust height */
                font-size: 10px;
                /* Adjust font size if needed */
            }

            /* Adjust size for error icon */
            .swal2-error {
                width: 8px;
                /* Adjust width */
                height: 8px;
                /* Adjust height */
                font-size: 15px;
                /* Adjust font size if needed */
            }

            .swal2-icon-inline {
                display: inline-block;
                vertical-align: middle;
                margin-right: 15px;
                /* Adjust spacing between icon and text */
            }

            .swal2-content-inline {
                display: inline-block;
                vertical-align: middle;
            }
        </style>
    <?php endif; ?>
</body>

</html>