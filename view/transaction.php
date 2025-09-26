<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">
 <!-- Topbar -->
  <header class="bg-red-900 text-white px-6 py-6 flex items-center space-x-3">
    <!-- <span class="material-icons cursor-pointer">arrow_back</span> -->
    <h1 class="text-lg font-semibold">TRANSACTION LIST</h1>
  </header>

  
  <!-- Top Bar -->
  <header class="flex flex-wrap gap-2 px-4 py-3 border-b bg-white">
    <input type="text" placeholder="Enter Transaction Code"
      class="flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400">

    <!-- Buttons with Material Icons -->
    <a href="refund_exchange" <?=$authorize?> 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
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

  </header>

 <!-- Table / Cart -->
<section class="flex-1 flex flex-col px-4 py-3">
  <div class="overflow-x-auto rounded-lg shadow">
    <table class="min-w-full border border-gray-200 text-sm">
      <!-- Table Header -->
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-4 py-2 text-left font-semibold border-b">Date</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Transaction ID</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Amount</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Action</th>
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody id="transactionTableBody">
        
       
      </tbody>
    </table>
  </div>
</section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
   
  </footer>

  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>














<!-- Tailwind Modal -->
<div id="transactionModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" style="display:none;">
  <div class="flex items-center justify-center min-h-screen px-4 w-full">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm sm:max-w-md mx-auto relative">

      <!-- Header -->
      <div class="flex justify-between items-center border-b px-4 py-3">
        <h2 class="text-lg font-semibold">Receipt</h2>
        <button id="closeModal" class="text-gray-500 hover:text-gray-700 font-bold text-xl">×</button>
      </div>

      <!-- Body (Receipt Area for print) -->
      <!-- Body (Receipt Area for print) -->
<div id="printArea" class="p-4 text-xs sm:text-sm font-mono">
  
  <!-- Store Header -->
  <div class="text-center mb-3">
    <h1 class="text-base font-bold">Revvedup</h1>
    <p class="text-xs text-gray-500">123 Main Street, City</p>
    <p class="text-xs text-gray-500">Tel: (123) 456-7890</p>
  </div>

  <hr class="border-dashed border-gray-400 mb-2">

  <!-- Transaction Info -->
  <div class="mb-2 flex justify-between">
    <p><span class="font-semibold">Transaction ID:</span> <span id="modalTransactionId"></span></p>
    <p><span class="font-semibold">Date:</span> <span id="modalTransactionDate"></span></p>
  </div>


  <hr class="border-dashed border-gray-400 mb-2">

  <!-- Services Section -->
  <div class="mb-3">
    <h3 class="font-semibold text-left">Service Details</h3>
    <div id="modalServicesList"></div>
  </div>

  <hr class="border-dashed border-gray-400 mb-2">

  <!-- Items Section -->
  <div class="mb-3">
    <h3 class="font-semibold text-left">Items Details</h3>
    <div id="modalItemsList"></div>
  </div>

  <hr class="border-dashed border-gray-400 mb-2">

  <!-- Totals -->
  <div class="space-y-1">
    <p class="flex justify-between"><span>Total Services</span> <span id="modalTotalServices"></span></p>
    <p class="flex justify-between"><span>Total Items</span> <span id="modalTotalItems"></span></p>
    <p class="flex justify-between text-red-600"><span>Discount</span> <span id="modalDiscount"></span></p>
    <p class="flex justify-between font-semibold"><span>Subtotal</span> <span id="modalSubtotal"></span></p>
    <p class="flex justify-between"><span>VAT (12%)</span> <span id="modalVAT"></span></p>
    <p class="flex justify-between font-bold border-t pt-1"><span>Total</span> <span id="modalTotal"></span></p>
    <p class="flex justify-between"><span>Payment</span> <span id="modalPayment"></span></p>
    <p class="flex justify-between"><span>Change</span> <span id="modalChange"></span></p>
  </div>

  <hr class="border-dashed border-gray-400 my-2">

  <!-- Footer -->
  <div class="text-center text-xs mt-3">
    <p>Thank you for your purchase!</p>
    <p class="italic">Please come again</p>
  </div>
</div>


      <!-- Footer Buttons -->
      <div class="flex justify-between border-t px-4 py-3">
        <button id="printBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-1 rounded">
          Print
        </button>
        <button id="closeModalFooter" class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-3 py-1 rounded">
          Close
        </button>
      </div>

    </div>
  </div>
</div>


<script>
  document.getElementById("printBtn").addEventListener("click", function() {
  let printContents = document.getElementById("printArea").innerHTML;
  let win = window.open("", "", "width=400,height=600");

 win.document.write(`
  <html>
    <head>
      <title>Receipt</title>
      <style>
        body { 
          font-family: monospace; 
          font-size: 12px; 
          margin: 0; 
          padding: 0;
        }
        .print-container {
          width: 58mm;            /* lapad ng receipt */
          margin: 0 auto;         /* auto margin para center sa page */
          padding: 10px;
        }
        .flex { display: flex; justify-content: space-between; }
        hr { border: none; border-top: 1px dashed #000; margin: 4px 0; }

        @media print {
          body {
            display: flex;
            justify-content: center; 
            align-items: flex-start;
          }
        }
      </style>
    </head>
    <body>
      <div class="print-container">
        ${printContents}
      </div>
    </body>
  </html>
`);


  win.document.close();

  // Hintayin muna bago mag print para loaded lahat
  win.focus();
  win.print();

  // Isasara lang after printing
  win.onafterprint = () => {
    win.close();
  };
});

</script>








<?php 
include "../src/components/view/footer.php";
?>

<script src="../static/js/view/transaction.js"></script>