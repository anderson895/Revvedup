<?php 
include "../src/components/view/header.php";
?>
  
  
 <!-- Main Content -->
<main class="flex-1 flex flex-col">

  <!-- Top Bar -->
  <header class="flex flex-wrap gap-2 px-4 py-3 border-b bg-white">
    <input type="text" placeholder="Enter Item Code"
      class="flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400">
    
    <button class="bg-red-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap">
      REFUND | EXCHANGE
    </button>
    <button class="bg-red-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap">
      SERVICE
    </button>
    <button class="bg-red-600 text-white px-4 py-2 rounded text-sm whitespace-nowrap">
      ITEM
    </button>
    <button class="flex items-center justify-center px-3 py-2 rounded hover:bg-gray-100">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 6a5 5 0 100 10 5 5 0 000-10z"/>
      </svg>
    </button>
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
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h14l-1.35 6.45A2 2 0 0117.7 21H8.3a2 2 0 01-1.95-1.55L4 5H2"/>
        </svg>
        <p>No items in cart</p>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
    <!-- Proceed Button -->
    <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition duration-200 ease-in-out w-full sm:w-auto">
      Proceed to Payment
    </button>

    <!-- Total Box -->
    <div class="bg-red-800 text-white px-6 py-2 rounded-lg font-semibold shadow text-center w-full sm:w-auto">
      TOTAL
    </div>
  </footer>
  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>



<?php 
include "../src/components/view/footer.php";
?>