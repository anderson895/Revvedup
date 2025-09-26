<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

 <header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
  <!-- Top Row -->
  <div class="flex items-center space-x-2 text-sm text-gray-600">
  <input 
    type="text" 
    placeholder="Add Service"
    class="flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400"
  >
    <button 
      class="bg-red-800 text-white px-3 py-2 rounded hover:bg-red-900 transition flex items-center justify-center"
    >
      <span class="material-icons text-white text-base">add</span>
    </button>
  </div>



  <!-- Bottom Row (Buttons) -->
  <div class="flex flex-wrap gap-2 justify-end">
    <a href="refund_exchange" <?=$authorize?> 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      REFUND | EXCHANGE
    </a>

    <a href="service" 
       class="flex items-center gap-2 bg-pink-200 text-red-800 px-4 py-2 rounded border border-black-300 text-sm font-bold shadow-sm">
      SERVICE
    </a>

    <a href="item" 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      ITEM
    </a>
  </div>
</header>

  <!-- Table / Cart -->
<section class="flex-1 flex flex-col px-4 py-3">
  <div class="overflow-x-auto rounded-lg shadow">
    <table class="min-w-full border border-gray-200 text-sm">
      <!-- Table Header -->
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-4 py-2 text-left font-semibold border-b">Service Name</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Price</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Employee</th>
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

        
        <!-- <tr class="hover:bg-gray-50 transition">
          <td class="px-4 py-2 border-b">Haircut</td>
          <td class="px-4 py-2 border-b">₱150</td>
          <td class="px-4 py-2 border-b">John Doe</td>
          <td class="px-4 py-2 border-b">
             <button class="text-red-600 hover:text-red-800 transition">
                <span class="material-icons">delete</span>
            </button>
          </td>
        </tr> -->
       
      </tbody>
    </table>
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
