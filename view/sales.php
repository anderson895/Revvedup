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
    <input type="text" id="transactionSearch" placeholder="Enter Transaction ID"
      class="flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400">

    <!-- Buttons with Material Icons -->
    <a href="#" 
       class="btnRefundExchange font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
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
    <div id="paginationControls" class="flex justify-center mt-3"></div>

  </div>
</section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
   
  </footer>

  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>








<?php 

?>



<?php 
include "../src/components/view/footer.php";
include "modal_refund.php";
?>



<script src="../static/js/view/sales.js"></script>
