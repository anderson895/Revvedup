<?php 
include "../src/components/view/header.php";

$transactionId=$_GET['transactionId'];
?>

<main class="flex-1 flex flex-col">

<header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">REFUND AND EXCHANGE</h1>
</header>

<header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
 <div class="transaction-info flex justify-between text-sm text-gray-600">
  <input type="hidden" id="transactionId" name="transactionId" value="<?=$transactionId?>">
  <span>Transaction No. <strong></strong></span>
  <span>Date: <strong><?= date("Y/m/d") ?></strong></span>
</div>


  <div class="flex flex-wrap gap-2 justify-end">
    <a href="#" <?=$authorize?> 
       class="btnRefundExchange flex items-center gap-2 bg-pink-200 text-red-800 px-4 py-2 rounded border border-black-300 text-sm font-bold shadow-sm">
      REFUND | EXCHANGE
    </a>

    <a href="service" 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      SERVICE
    </a>

    <a href="item" 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      ITEM
    </a>
  </div>
</header>



<section class="flex-1 flex flex-col px-4 py-3">
  <div class="overflow-x-auto rounded-lg shadow">
    <table class="min-w-full border border-gray-200 text-sm">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-4 py-2 text-left font-semibold border-b">Item Name</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Quantity</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Unit Price</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Refund</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Exchange</th>
        </tr>
      </thead>
      <tbody class="transactionTableBody"></tbody>
    </table>
  </div>
</section>

<footer class="flex flex-col sm:flex-row gap-3 justify-between items-center bg-white border-t px-4 py-4">
  <div class="flex-1">
    <div class="flex justify-between items-center bg-gray-50 border px-4 py-2 rounded shadow-sm w-full sm:w-80">
      <span class="font-medium text-gray-700">Total Refund</span>
      <span class="font-bold text-lg text-gray-900">0.00</span>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 items-center">
    <button id="btnComplete_transaction" class=" bg-red-800 cursor-pointer hover:bg-red-700 text-white px-6 py-2 rounded shadow font-medium w-full sm:w-auto">
      Complete Transaction
    </button>
    <button class="bg-red-800 cursor-pointer hover:bg-red-700 text-white px-4 py-2 rounded shadow font-medium">
      Refund All
    </button>
    <button class="bg-red-800 cursor-pointer hover:bg-red-700 text-white px-4 py-2 rounded shadow font-medium">
      Exchange All
    </button>
  </div>
</footer>

<br class="block sm:hidden">
<br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
include "modal_refund.php";
?>


<script src="../static/js/view/refund_exchange.js"></script>