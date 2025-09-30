<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col bg-gray-100 min-h-screen">

  <!-- Topbar -->
  <header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">PRODUCT INVENTORY</h1>
  </header>



  <!-- Search Bar -->
<div class="px-6 py-4  flex justify-center sm:justify-start">
  <div class="relative w-full sm:max-w-xs">
    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2">
      search
    </span>
    <input
      type="text"
      id="searchInput"
      class="w-full pl-10 pr-4 py-2 rounded-md  border border-gray-700 
              placeholder-gray-500 focus:outline-none "
      placeholder="Search inventory..."
    />
  </div>
</div>

  <!-- Content -->
  <section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <!-- Table Header -->
      <div class="flex justify-between items-center  px-4 py-3">
        <h2 class="text-gray-700 font-semibold">Inventory List</h2>

        <button class="p-2 rounded-md hover:bg-gray-100" id="addProductBtn">
        <span class="material-icons text-green-600">add_box</span>
        </button>

      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Reference Number
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Date
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody id="appointmentTableBody" class="bg-white divide-y divide-gray-200">
          <!-- DYNAMIC CONTENT -->
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


<script src="../static/js/view/booking_request.js"></script>