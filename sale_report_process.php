<?php
$page_title = 'Sales Report';
$results = '';
require_once('includes/load.php');

// Check user permission level
page_require_level(3);

if(isset($_POST['submit'])){
    $req_dates = array('start-date', 'end-date');
    validate_fields($req_dates);

    if(empty($errors)):
        $start_date = remove_junk($db->escape($_POST['start-date']));
        $end_date = remove_junk($db->escape($_POST['end-date']));
        $results = find_sale_by_dates($start_date, $end_date);
    else:
        $session->msg("d", $errors);
        redirect('sales_report.php', false);
    endif;

} else {
    $session->msg("d", "Select dates");
    redirect('sales_report.php', false);
}

function total_qty($results) {
    $total_qty = 0;
    foreach ($results as $result) {
        $total_qty += (int)$result['total_sales']; // Assume this field reflects the deducted quantity
    }
    return $total_qty;
}

function total_selling_price($results) {
    $total_selling_price = 0;
    foreach ($results as $result) {
        $total_selling_price += (float)$result['total_saleing_price'];
    }
    return $total_selling_price;
}

function total_buying_price($results) {
    $total_buying_price = 0;
    foreach ($results as $result) {
        $total_buying_price += (float)$result['buy_price'] * (int)$result['total_sales'];
    }
    return $total_buying_price;
}

function total_profit($results) {
    return total_selling_price($results) - total_buying_price($results);
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sales Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .page-break {
            page-break-before: always;
        }
        .sale-head {
            text-align: center;
            margin-bottom: 30px;
        }
        .sale-head h1 {
            margin: 0 0 10px;
            padding: 10px 0;
            border-bottom: 2px solid #000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        tfoot {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php if($results): ?>
        <div class="page-break">
            <div class="sale-head">
                <!-- Logo Section -->
                <img src="libs/images/icon.png" alt="ANC Mini Mart Logo" style="width:100px; height:auto; display:block; margin: 0 auto;">
                <h1>ANC Mini Mart - Sales Report</h1>
                <p>Kabangbang, Bantayan, Cebu</p>
                <p>Contact no: Smart: 09086062594 Sun: 09228947029 Globe: (0945)7657140</p>
                <p>Email: ancminimartbantayan@yahoo.com</p>
                <strong><?php echo isset($start_date) ? $start_date : ''; ?> to <?php echo isset($end_date) ? $end_date : ''; ?></strong>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product Title</th>
                        <th>Buying Price</th>
                        <th>Selling Price</th>
                        <th>Total Qty</th>
                        <th>Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($results as $result): ?>
                        <tr>
                            <td><?php echo remove_junk($result['date']); ?></td>
                            <td><?php echo remove_junk(ucfirst($result['name'])); ?></td>
                            <td class="text-right"><?php echo number_format((float)$result['buy_price'], 2); ?></td>
                            <td class="text-right"><?php echo number_format((float)$result['sale_price'], 2); ?></td>
                            <td class="text-right"><?php echo (int)$result['total_sales']; ?></td>
                            <td class="text-right"><?php echo number_format((float)$result['total_saleing_price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <td>Total Sales</td>
                        <td class="text-right">₱ <?php echo number_format(total_selling_price($results), 2); ?></td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>Profit</td>
                        <td class="text-right">₱ <?php echo number_format(total_profit($results), 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php else: ?>
        <?php
        $session->msg("d", "Sorry, no sales have been found.");
        redirect('sales_report.php', false);
        ?>
    <?php endif; ?>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>