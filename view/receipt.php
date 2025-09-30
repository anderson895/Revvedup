<?php
include '../controller/class.php';

if (!isset($_GET['transaction_id'])) {
    die("Invalid request. No transaction ID.");
}

$transactionId = intval($_GET['transaction_id']);

$db = new global_class(); // gumamit ng global_class
$data = $db->fetch_transaction_by_id($transactionId);

$business = $db->get_business_details();

if (!$data) {
    die("Transaction not found.");
}

$transaction = $data['transaction'];
$services    = $data['services'];
$items       = $data['items'];

// Compute totals
$totalServices = 0;
foreach ($services as $s) {
    $totalServices += floatval($s['price']);
}

$totalItems = 0;
foreach ($items as $i) {
    $totalItems += floatval($i['subtotal']);
}

// VAT breakdown
$vatAmount   = $transaction['transaction_vat'] ?? 0;
$vatableSale = ($transaction['transaction_total'] - $vatAmount);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt #<?= htmlspecialchars($transactionId) ?></title>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

  <style>
    body { font-family: monospace; font-size: 12px; margin: 0; padding: 0; }
    .print-container { width: 58mm; margin: 0 auto; padding: 10px; }
    .flex { display: flex; justify-content: space-between; }
    hr { border: none; border-top: 1px dashed #000; margin: 4px 0; }
    h1 { font-size: 14px; margin: 0; font-weight: bold; }
    h2 { font-size: 12px; margin: 4px 0; font-weight: bold; }

    /* Hide sa print */
    @media print {
        .printButtonDiv {
            display: none !important;
        }
    }
  </style>
</head>
<body onload="window.print()">
  <div class="print-container">
    <div style="text-align:center; margin-bottom:5px;">
      <h1><?=htmlspecialchars($business['business_name'])?></h1>
      <p><?=htmlspecialchars($business['business_address'])?></p>
      <p>Tel: <?=htmlspecialchars($business['business_contact_num'])?></p>
    </div>
    <hr>

    <div class="flex">
      <span><b>ID:</b> <?= htmlspecialchars($transaction['transaction_id']) ?></span>
      <span><b>Date:</b> <?= date("F j, Y", strtotime($transaction['transaction_date'])) ?></span>
    </div>
    <hr>

    <h2>Service Details</h2>
    <?php if (!empty($services)): ?>
      <?php foreach ($services as $s): ?>
        <div class="flex">
          <span><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['employee_name']) ?>)</span>
          <span>₱ <?= number_format($s['price'], 2) ?></span>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-style:italic; color:gray;">No services</p>
    <?php endif; ?>
    <hr>

    <h2>Items Details</h2>
    <?php if (!empty($items)): ?>
      <?php foreach ($items as $i): ?>
        <div class="flex">
          <span><?= htmlspecialchars($i['name']) ?> (x<?= intval($i['qty']) ?>)</span>
          <span>₱ <?= number_format($i['subtotal'], 2) ?></span>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="font-style:italic; color:gray;">No items</p>
    <?php endif; ?>
    <hr>

    <div class="flex"><span>Total Services</span><span>₱ <?= number_format($totalServices,2) ?></span></div>
    <div class="flex"><span>Total Items</span><span>₱ <?= number_format($totalItems,2) ?></span></div>
    <div class="flex"><span>Discount</span><span>₱ <?= number_format($transaction['transaction_discount'] ?? 0, 2) ?></span></div>

    <hr>
    <!-- VAT Breakdown -->
    <div class="flex"><span>VATable Sales</span><span>₱ <?= number_format($vatableSale, 2) ?></span></div>
    <div class="flex"><span>VAT-Exempt Sales</span><span>₱ 0.00</span></div>
    <div class="flex"><span>VAT Zero-Rated Sales</span><span>₱ 0.00</span></div>
    <div class="flex"><span>VAT Amount (12%)</span><span>₱ <?= number_format($vatAmount, 2) ?></span></div>
    <hr>

    <div class="flex" style="font-weight:bold; border-top:1px dashed #000; padding-top:2px;">
      <span>Total</span><span>₱ <?= number_format($transaction['transaction_total'], 2) ?></span>
    </div>
    <div class="flex"><span>Payment</span><span>₱ <?= number_format($transaction['transaction_payment'], 2) ?></span></div>
    <div class="flex"><span>Change</span><span>₱ <?= number_format($transaction['transaction_change'], 2) ?></span></div>
    <hr>

    <div style="text-align:center; font-size:11px; margin-top:5px;">
      <p>Thank you for your purchase!</p>
      <p><i>Please come again</i></p>
    </div>
  </div>

  <div class="printButtonDiv" style="display:flex; justify-content:center; margin-top:10px;">
    <button onclick="window.print()" class="btn-print" 
            style="display:flex; align-items:center; gap:5px; justify-content:center; padding:6px 12px; border:none; border-radius:4px; background:#444; color:white; cursor:pointer;">
        <span class="material-icons">print</span>
        Print Receipt
    </button>
  </div>
</body>
</html>
