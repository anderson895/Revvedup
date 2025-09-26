<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col bg-gray-100 min-h-screen">

  <!-- Topbar -->
  <header class="bg-red-900 text-white px-6 py-4 flex items-center space-x-3">
    <span class="material-icons cursor-pointer">arrow_back</span>
    <h1 class="text-lg font-semibold">Product Inventory</h1>
  </header>

  <!-- Content -->
  <section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <!-- Table Header -->
      <div class="flex justify-between items-center  px-4 py-3">
        <h2 class="text-gray-700 font-semibold">Inventory List</h2>
        <button class="p-2 rounded-md hover:bg-gray-100">
        <span class="material-icons text-green-600">add_box</span>
        </button>

      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
          <thead class="bg-gray-50 ">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Item ID</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Item Name</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Unit Price</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Qty.</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Sales Speed</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
              <th class="px-4 py-2 text-center text-sm font-medium text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <!-- Sample Row 1 -->
            <tr>
              <td class="px-4 py-2">ID001</td>
              <td class="px-4 py-2">ItemName_1</td>
              <td class="px-4 py-2">₱ 0.00</td>
              <td class="px-4 py-2">50</td>
              <td class="px-4 py-2">Fast Moving</td>
              <td class="px-4 py-2">
                <span class="inline-block w-3 h-3 rounded-full bg-green-600"></span>
              </td>
              <td class="px-4 py-2 flex justify-center space-x-2">
                <button class="text-gray-700 hover:text-blue-600">
                  <span class="material-icons text-sm">edit</span>
                </button>
                <button class="text-gray-700 hover:text-red-600">
                  <span class="material-icons text-sm">delete</span>
                </button>
              </td>
            </tr>

            <!-- Sample Row 2 -->
            <tr>
              <td class="px-4 py-2">ID002</td>
              <td class="px-4 py-2">ItemName_2</td>
              <td class="px-4 py-2">₱ 0.00</td>
              <td class="px-4 py-2">30</td>
              <td class="px-4 py-2">Slow Moving</td>
              <td class="px-4 py-2">
                <span class="inline-block w-3 h-3 rounded-full bg-yellow-500"></span>
              </td>
              <td class="px-4 py-2 flex justify-center space-x-2">
                <button class="text-gray-700 hover:text-blue-600">
                  <span class="material-icons text-sm">edit</span>
                </button>
                <button class="text-gray-700 hover:text-red-600">
                  <span class="material-icons text-sm">delete</span>
                </button>
              </td>
            </tr>

            <!-- Sample Row 3 -->
            <tr>
              <td class="px-4 py-2">ID003</td>
              <td class="px-4 py-2">ItemName_3</td>
              <td class="px-4 py-2">₱ 0.00</td>
              <td class="px-4 py-2">0</td>
              <td class="px-4 py-2">Fast Moving</td>
              <td class="px-4 py-2">
                <span class="inline-block w-3 h-3 rounded-full bg-red-600"></span>
              </td>
              <td class="px-4 py-2 flex justify-center space-x-2">
                <button class="text-gray-700 hover:text-blue-600">
                  <span class="material-icons text-sm">edit</span>
                </button>
                <button class="text-gray-700 hover:text-red-600">
                  <span class="material-icons text-sm">delete</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    
  </section>

<!-- Footer -->
<footer class="flex justify-center items-center bg-white  px-4 py-4">
  <!-- Legend -->
  <div class="flex space-x-6 text-sm text-gray-600">
    <div class="flex items-center space-x-1">
      <span class="w-3 h-3 rounded-full bg-green-600"></span>
      <span>In Stock</span>
    </div>
    <div class="flex items-center space-x-1">
      <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
      <span>Low Stock</span>
    </div>
    <div class="flex items-center space-x-1">
      <span class="w-3 h-3 rounded-full bg-red-600"></span>
      <span>Out of Stock</span>
    </div>
  </div>
</footer>



  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
?>
