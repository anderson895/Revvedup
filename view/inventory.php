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
          <tbody id="productTableBody" class="divide-y">
            <!-- Sample Row 1 -->
            <!-- 
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
            </tr> -->

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
















<!-- Modal Background -->
<div id="addProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display:none;">
  <!-- Modal Content -->
  <div class="bg-white rounded-md shadow-lg w-full max-w-xl p-8 relative">
    
    <!-- Title -->
    <h2 class="text-2xl italic text-center mb-8">Add New Item</h2>

    <!-- Form -->
    <form id="frmAddProduct" class="grid grid-cols-3 gap-4 items-center" enctype="multipart/form-data">
      <!-- Item Name -->
      <input 
        type="text" 
        placeholder="Item name" 
        id="itemName"
        name="itemName"
        class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900 col-span-1"
      >

      <!-- Price -->
      <div class="flex items-center border rounded px-3 py-2 col-span-1">
        <span class="text-gray-500 mr-2">₱</span>
        <input 
          type="number" 
          placeholder="00.00" 
          id="price"
          name="price"
          class="w-full outline-none"
          step="0.01"
        >
      </div>

      <!-- Stocks -->
      <input 
        type="number" 
        placeholder="Stocks" 
        id="stockQty"
        name="stockQty"
        class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900 col-span-1"
      >

      <!-- File Upload -->
      <div class="col-span-3">
        <label class="block mb-2 text-sm font-medium text-gray-700">Upload Image</label>
        <input 
          type="file" 
          id="itemImage"
          name="itemImage"
          accept="image/*"
          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900"
        >
        <!-- Preview -->
        <img id="previewImage" class="mt-3 max-h-40 rounded shadow hidden" />
      </div>
    </form>

    <!-- Action Button -->
    <div class="flex justify-end mt-6">
      <button 
        type="submit" 
        form="frmAddProduct"
        class="bg-red-900 text-white px-6 py-2 rounded shadow hover:bg-red-700"
      >
        Add Item
      </button>
    </div>
  </div>
</div>











<!-- Modal Background -->
<div id="updateProductModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display:none;">
  <!-- Modal Content -->
  <div class="bg-white rounded-md shadow-lg w-full max-w-xl p-8 relative">
    
    <!-- Title -->
    <h2 class="text-2xl italic text-center mb-8">Update Product</h2>

    <!-- Form -->
    <form id="frmUpdateProduct" class="grid grid-cols-3 gap-4 items-center" enctype="multipart/form-data">
      
      <!-- Hidden Product ID -->
      <input type="hidden" id="productId" name="productId">

      <!-- Item Name -->
      <input 
        type="text" 
        placeholder="Item name" 
        id="itemNameUpdate"
        name="itemName"
        class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900 col-span-1"
      >

      <!-- Price -->
      <div class="flex items-center border rounded px-3 py-2 col-span-1">
        <span class="text-gray-500 mr-2">₱</span>
        <input 
          type="number" 
          placeholder="00.00" 
          id="priceUpdate"
          name="price"
          class="w-full outline-none"
          step="0.01"
        >
      </div>

      <!-- Stocks -->
      <input 
        type="number" 
        placeholder="Stocks" 
        id="stockQtyUpdate"
        name="stockQty"
        class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900 col-span-1"
      >

      <!-- File Upload -->
      <div class="col-span-3">
        <label class="block mb-2 text-sm font-medium text-gray-700">Upload Image</label>
        <input 
          type="file" 
          id="itemImageUpdate"
          name="itemImage"
          accept="image/*"
          class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900"
        >
      </div>

      <!-- Action Buttons (inside form) -->
      <div class="flex justify-end mt-6 space-x-3 col-span-3">
        <button 
          id="closeUpdateProductModal"
          type="button"
          class="bg-gray-300 text-gray-700 px-6 py-2 rounded shadow hover:bg-gray-400"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          class="bg-red-900 text-white px-6 py-2 rounded shadow hover:bg-red-700"
        >
          Update
        </button>
      </div>

    </form>
  </div>
</div>




<?php 
include "../src/components/view/footer.php";
?>


<script src="../static/js/view/inventory.js"></script>