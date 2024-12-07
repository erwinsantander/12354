<?php
$cartData = json_decode($_POST['cartData'], true);
$total = $_POST['total'];
$payment = $_POST['payment'];
$change = $_POST['change'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supermarket Receipt</title>
    <style>
        @media print {
            @page {
                size: 58mm 200mm;
                margin: 0 !important;
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }

        body {
            font-family: monospace, 'Courier New', Courier;
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
            font-size: 10px;
        }

        .receipt {
            width: 220px;  /* Increased width slightly */
            text-align: center;
            padding: 5px 10px;  /* Added horizontal padding */
        }

        .receipt-header {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }

        .receipt-header h3 {
            margin: 0;
            text-transform: uppercase;
            font-size: 12px;
        }

        .receipt-details {
            width: 100%;
            font-size: 10px;
        }

        .receipt-details td {
            padding: 1px 0;
        }

        .receipt-details td:first-child {
            text-align: left;
            width: 60%;
            word-wrap: break-word;
        }

        .receipt-details td:nth-child(2) {
            text-align: center;
            width: 20%;
        }

        .receipt-details td:last-child {
            text-align: right;
            width: 20%;
        }

        .totals {
            border-top: 1px dashed #000;
            margin-top: 5px;
            padding-top: 5px;
            text-align: right;
        }

        .footer {
            margin-top: 5px;
            text-align: center;
            font-size: 8px;
        }

        .date-time {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <h3>ANC MINIMART</h3>
            <div>Kabanbang Bantayan Cebu</div>
            <div>Number: 09456484176</div>
            <div class="date-time">
                <span><?php echo date('m/d/Y'); ?></span>
                <span><?php echo date('H:i'); ?></span>
            </div>
        </div>

        <table class="receipt-details">
            <?php foreach ($cartData as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>₱<?php echo number_format($item['total'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="totals">
            <div>Total: ₱<?php echo number_format($total, 2); ?></div>
            <div>Cash: ₱<?php echo number_format($payment, 2); ?></div>
            <div>Change: ₱<?php echo number_format($change, 2); ?></div>
        </div>

        <div class="footer">
            <div>CASH TRANSACTION</div>
            <div>AUTH CODE: 123456</div>
            <div>THANK YOU FOR YOUR PURCHASE!</div>
            <div>PLEASE COME AGAIN</div>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html> 