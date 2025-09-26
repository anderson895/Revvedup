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
          <th class="px-4 py-2 text-left font-semibold border-b">Action</th>
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody>
        <!-- Empty State (kung walang laman ang cart) -->
        <!-- <tr>
          <td colspan="3" class="px-4 py-6 text-center text-gray-400">
            <span class="material-icons text-6xl block mx-auto mb-2">shopping_cart</span>
            <p>No items in cart</p>
          </td>
        </tr> -->

        
        
       
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

<?php 
include "../src/components/view/footer.php";
?>
