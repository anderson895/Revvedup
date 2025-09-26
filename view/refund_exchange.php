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
       class="flex items-center gap-2 bg-pink-200 text-red-800 px-4 py-2 rounded border border-black-300 text-sm font-bold shadow-sm">
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
          <th class="px-4 py-2 text-left font-semibold border-b">Item Name</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Quantity</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Unit Price</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Refund</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Exchange</th>
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody>
        <tr class="hover:bg-gray-50 transition">
          <td class="px-4 py-2 border-b">ItemName_2</td>
          <td class="px-4 py-2 border-b">4</td>
          <td class="px-4 py-2 border-b">1,896.00</td>

          <!-- Refund Inputs -->
          <td class="px-4 py-2 border-b">
            <div class="flex flex-row gap-2">
              <!-- Quantity -->
              <input type="number" placeholder="Quantity"
                class="border rounded px-2 py-1 w-24 focus:outline-none focus:ring focus:ring-blue-300">

              <!-- Total Price -->
              <input type="text" placeholder="Total Price"
                class="border rounded px-2 py-1 w-32 focus:outline-none focus:ring focus:ring-blue-300">
            </div>
          </td>


          <!-- Exchange Input -->
          <td class="px-4 py-2 border-b">
            <input type="number" placeholder="Qty"
              class="border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:ring-blue-300">
          </td>
        </tr>
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
