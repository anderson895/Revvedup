<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

  <!-- Top Bar -->
  <header class="flex flex-wrap gap-2 px-4 py-3 border-b bg-white">
    <input type="text" placeholder="Enter Item Code"
      class="flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400">

    <!-- Buttons with Material Icons -->
    <a href="refund_exchange" <?=$authorize?> 
       class="flex items-center gap-2 bg-red-600 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      <span class="material-icons text-base">currency_exchange</span>
      REFUND | EXCHANGE
    </a>

    <a href="service" 
       class="flex items-center gap-2 bg-red-600 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      SERVICE
    </a>

    <a href="item" 
       class="flex items-center gap-2 bg-red-600 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      ITEM
    </a>
  </header>

  <!-- Table / Cart -->
  <section class="flex-1 flex flex-col">
    
    <!-- Desktop Table Header -->
    <div class="hidden sm:grid grid-cols-5 gap-2 text-sm font-semibold border-b bg-gray-50 px-4 py-2">
      <span>Item ID</span>
      <span>Item Name</span>
      <span>Quantity</span>
      <span>Unit Price</span>
      <span>Total</span>
    </div>

    <!-- Empty Cart -->
    <div class="flex-1 flex items-center justify-center px-4">
      <div class="text-center text-gray-400">
        <span class="material-icons mx-auto text-7xl">shopping_cart</span>
        <p>No items in cart</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
    <!-- Proceed Button -->
    <button class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition duration-200 ease-in-out w-full sm:w-auto">
      <span class="material-icons text-base">payment</span>
      Proceed to Payment
    </button>

    <!-- Total Box -->
    <div class="flex items-center gap-2 bg-red-800 text-white px-6 py-2 rounded-lg font-semibold shadow text-center w-full sm:w-auto">
      <span class="text-xl">₱</span> TOTAL
    </div>
  </footer>

  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
?>
