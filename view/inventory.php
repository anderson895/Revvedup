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
      
      <!-- Tabs Header -->
      <div class="flex justify-between items-center px-4 py-3 border-b">
        <div class="flex space-x-4">
          <button id="activeTab" class="cursor-pointer px-4 py-2 rounded-md bg-gray-200 text-gray-700 font-medium">Active</button>
          <button id="archiveTab" class="cursor-pointer px-4 py-2 rounded-md hover:bg-gray-100 text-gray-700 font-medium">Archive</button>
        </div>

        <button class="p-2 cursor-pointer rounded-md hover:bg-gray-100" id="addProductBtn" <?= $authorize ?>>
          <span class="material-icons text-green-600">add_box</span>
        </button>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full border-collapse">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Item ID</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Item Name</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Capital</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Unit Price</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Qty.</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Sales Speed</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Category</th>
              <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Status</th>
              <th class="px-4 py-2 text-center text-sm font-medium text-gray-600" <?= $authorize ?>>Actions</th>
            </tr>
          </thead>
          <tbody id="productTableBody" class="divide-y">
            <!-- DYNAMIC PART -->
          </tbody>
        </table>
      </div>
    </div>
</section>


  <!-- Fixed Footer Legend -->
<footer class="fixed bottom-0 left-0 w-full md:left-20 md:w-[calc(100%-5rem)] bg-white py-4 px-6 shadow-t z-20">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-center items-center space-y-2 md:space-y-0 md:space-x-6 text-sm text-gray-600">
    <div class="flex items-center space-x-2">
      <span class="w-3 h-3 rounded-full bg-green-600"></span>
      <span>In Stock</span>
    </div>
    <div class="flex items-center space-x-2">
      <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
      <span>Low Stock</span>
    </div>
    <div class="flex items-center space-x-2">
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
    <form id="frmAddProduct" class="space-y-4" enctype="multipart/form-data">
      
      <!-- One Row Inputs -->
      <div class="grid grid-cols-4 gap-4">

        <!-- Item Name -->
        <div class="relative">
          <input 
            type="text" 
            id="itemName"
            name="itemName"
            placeholder=" "
            class="peer block w-full rounded-lg border border-gray-300 px-2.5 pb-2.5 pt-4 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
          />
          <label for="itemName" 
            class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300 
            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
            peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
            Item Name
          </label>
        </div>

        <!-- Capital -->
        <div class="relative">
          <span class="absolute left-3 top-3 text-gray-500">₱</span>
          <input 
            type="number" 
            id="capital"
            name="capital"
            step="0.01"
            placeholder=" "
            class="peer block w-full rounded-lg border border-gray-300 pl-7 pr-2 pb-2.5 pt-4 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
          />
          <label for="capital" 
            class="absolute left-7 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300 
            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
            peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
            Capital
          </label>
        </div>

        <!-- Price -->
        <div class="relative">
          <span class="absolute left-3 top-3 text-gray-500">₱</span>
          <input 
            type="number" 
            id="price"
            name="price"
            step="0.01"
            placeholder=" "
            class="peer block w-full rounded-lg border border-gray-300 pl-7 pr-2 pb-2.5 pt-4 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
          />
          <label for="price" 
            class="absolute left-7 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300 
            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
            peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
            Price
          </label>
        </div>

        <!-- Stocks -->
        <div class="relative">
          <input 
            type="number" 
            id="stockQty"
            name="stockQty"
            placeholder=" "
            class="peer block w-full rounded-lg border border-gray-300 px-2.5 pb-2.5 pt-4 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
          />
          <label for="stockQty" 
            class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300 
            peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
            peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
            Stocks
          </label>
        </div>

      </div>



       <!-- Category -->
      <div class="relative">
        <select 
          id="category" 
          name="category"
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pt-4 pb-2.5 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        >
          <option value="" disabled selected>Select Category</option>
          <option value="Engine & Transmission">Engine & Transmission</option>
          <option value="Brakes">Brakes</option>
          <option value="Exhaust Systems">Exhaust Systems</option>
          <option value="Wheels & Tires">Wheels & Tires</option>
        </select>
        <label for="category" 
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300
          peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
          peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
          Category
        </label>
      </div>

      <!-- Description -->
      <div class="relative">
        <textarea
          id="description"
          name="description"
          placeholder=" "
          rows="4"
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pt-4 pb-2.5 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none resize-none"
        ></textarea>
        <label for="description"
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300
          peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
          peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
          Description
        </label>
      </div>




      <!-- File Upload -->
      <div>
        <label for="itemImage" class="block mb-2 text-sm font-medium text-gray-700">Upload Image</label>
        <input 
          type="file" 
          id="itemImage"
          name="itemImage"
          accept="image/*"
          class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-0 focus:border-red-900"
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
        class="bg-red-900 text-white cursor-pointer px-6 py-2 rounded shadow hover:bg-red-700"
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
    <form id="frmUpdateProduct" class="grid grid-cols-4 gap-4 items-center" enctype="multipart/form-data">
      
      <!-- Hidden Product ID -->
      <input type="hidden" id="productId" name="productId">

      <!-- Item Name -->
      <div class="relative col-span-1">
        <input 
          type="text" 
          id="itemNameUpdate"
          name="itemName"
          placeholder=" "
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pb-2.5 pt-4 
                 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        />
        <label for="itemNameUpdate" 
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform 
                 bg-white px-1 text-sm text-gray-500 duration-300 
                 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 
                 peer-placeholder-shown:scale-100
                 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 
                 peer-focus:text-red-900">
          Item Name
        </label>
      </div>

      <!-- Capital -->
      <div class="relative col-span-1">
        <span class="absolute left-3 top-3 text-gray-500">₱</span>
        <input 
          type="number" 
          id="capitalUpdate"
          name="capital"
          step="0.01"
          placeholder=" "
          class="peer block w-full rounded-lg border border-gray-300 pl-7 pr-2 pb-2.5 pt-4 
                 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        />
        <label for="capitalUpdate" 
          class="absolute left-7 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform 
                 bg-white px-1 text-sm text-gray-500 duration-300 
                 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 
                 peer-placeholder-shown:scale-100
                 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 
                 peer-focus:text-red-900">
          Capital
        </label>
      </div>

      <!-- Price -->
      <div class="relative col-span-1">
        <span class="absolute left-3 top-3 text-gray-500">₱</span>
        <input 
          type="number" 
          id="priceUpdate"
          name="price"
          step="0.01"
          placeholder=" "
          class="peer block w-full rounded-lg border border-gray-300 pl-7 pr-2 pb-2.5 pt-4 
                 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        />
        <label for="priceUpdate" 
          class="absolute left-7 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform 
                 bg-white px-1 text-sm text-gray-500 duration-300 
                 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 
                 peer-placeholder-shown:scale-100
                 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 
                 peer-focus:text-red-900">
          Price
        </label>
      </div>

      <!-- Stocks -->
      <div class="relative col-span-1">
        <input 
          type="number" 
          id="stockQtyUpdate"
          name="stockQty"
          placeholder=" "
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pb-2.5 pt-4 
                 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        />
        <label for="stockQtyUpdate" 
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform 
                 bg-white px-1 text-sm text-gray-500 duration-300 
                 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 
                 peer-placeholder-shown:scale-100
                 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 
                 peer-focus:text-red-900">
          Stocks
        </label>
      </div>



            <!-- Category -->
      <div class="relative col-span-4">
        <select 
          id="categoryUpdate" 
          name="category"
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pt-4 pb-2.5 
                 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none"
        >
          <option value="" disabled selected>Select Category</option>
          <option value="Engine & Transmission">Engine & Transmission</option>
          <option value="Brakes">Brakes</option>
          <option value="Exhaust Systems">Exhaust Systems</option>
          <option value="Wheels & Tires">Wheels & Tires</option>
        </select>
        <label for="categoryUpdate" 
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform 
                 bg-white px-1 text-sm text-gray-500 duration-300
                 peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 
                 peer-placeholder-shown:scale-100
                 peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 
                 peer-focus:text-red-900">
          Category
        </label>
      </div>






            <!-- Description -->
      <div class="relative col-span-4">
        <textarea
          id="descriptionUpdate"
          name="description"
          placeholder=" "
          rows="4"
          class="peer block w-full rounded-lg border border-gray-300 px-2.5 pt-4 pb-2.5 text-sm text-gray-900 bg-transparent focus:border-red-900 focus:ring-0 focus:outline-none resize-none"
        ></textarea>
        <label for="descriptionUpdate"
          class="absolute start-2.5 top-2 z-10 origin-[0] -translate-y-4 scale-75 transform bg-white px-1 text-sm text-gray-500 duration-300
          peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:scale-100
          peer-focus:top-2 peer-focus:-translate-y-4 peer-focus:scale-75 peer-focus:text-red-900">
          Description
        </label>
      </div>







      <!-- File Upload -->
      <div class="col-span-4">
        <label for="itemImageUpdate" class="block mb-2 text-sm font-medium text-gray-700">Upload Image</label>
        <input 
          type="file" 
          id="itemImageUpdate"
          name="itemImage"
          accept="image/*"
          class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm 
                 focus:outline-none focus:ring-0 focus:border-red-900"
        >
        <!-- Preview -->
        <img id="previewImageUpdate" class="mt-3 max-h-40 rounded shadow hidden" />
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end mt-6 space-x-3 col-span-4">
        <button 
          id="closeUpdateProductModal"
          type="button"
          class="bg-gray-300 cursor-pointer text-gray-700 px-6 py-2 rounded shadow hover:bg-gray-400"
        >
          Cancel
        </button>
        <button 
          type="submit" 
          class="bg-red-900 cursor-pointer text-white px-6 py-2 rounded shadow hover:bg-red-700"
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