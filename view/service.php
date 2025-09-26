<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

  <header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">SERVICE</h1>
  </header>


 <header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
  <!-- Top Row -->
  <div class="flex items-center space-x-2 text-sm text-gray-600">
  <input 
    type="text" 
    placeholder="Add Service"
    class="serviceNameInput flex-1 min-w-[150px] border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400"
  >
    <button id="addServiceBtn" class="bg-red-800 text-white px-3 py-2 rounded hover:bg-red-900 transition flex items-center justify-center"
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
          <th class="px-4 py-2 text-center font-semibold border-b">Service Name</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Price</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Employee</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Action</th>
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody id="serviceTableBody">
        
       
      </tbody>
    </table>
  </div>
</section>


  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
    <!-- Proceed Button -->
    <button class="proceedToPayBtn flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition duration-200 ease-in-out w-full sm:w-auto">
      <span class="material-icons text-base">payment</span>
      Proceed to Payment
    </button>

    <!-- Total Box -->
    <div class="flex items-center gap-2 bg-red-800 text-white px-6 py-2 rounded-lg font-semibold shadow text-center w-full sm:w-auto">
      <span class="text-sm">TOTAL : ₱</span><span class="totalPrice"></span>
    </div>
  </footer>

  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>













<!-- Modal For Add/Update Service -->
<div id="addServiceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display:none;">
  <!-- Modal Content -->
  <div class="bg-white rounded-xl shadow-lg w-full max-w-xl p-8 relative">
    
    <!-- Title -->
    <h2 class="titleAction text-2xl italic font-semibold text-center mb-8 text-gray-800">Add Service</h2>

    <!-- Form -->
    <form id="frmAddService" class="grid grid-cols-1 sm:grid-cols-3 gap-4" enctype="multipart/form-data">
      <input type="text">
      <!-- Service Name -->
      <div class="col-span-1 sm:col-span-3">
        <label for="serviceName" class="block text-sm font-medium text-gray-700 mb-1">Service Name</label>
        <input 
          type="text" 
          placeholder="Enter service name" 
          id="serviceName"
          name="serviceName"
          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900"
        >
      </div>

      <!-- Price -->
      <div class="col-span-1">
        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <div class="flex items-center border rounded-lg px-3 py-2">
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
      </div>

      <!-- Employee -->
      <div class="col-span-2">
        <label for="employee" class="block text-sm font-medium text-gray-700 mb-1">Assign Employee</label>
        <select 
          id="employee" 
          name="employee"
          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-900"
        >
          <option value="" disabled selected>Select Employee</option>

          
         
        </select>
      </div>

      <!-- Submit Button -->
      <div class="col-span-1 sm:col-span-3 flex justify-end mt-6">
        <button 
          type="submit" 
          class="bg-red-900 text-white px-6 py-2 rounded-lg shadow hover:bg-red-700 transition"
        >
          Add Service
        </button>
      </div>

    </form>
  </div>
</div>















<!-- Transaction Sidebar -->
<div id="transactionModal" class="fixed inset-0 z-50 flex justify-end bg-black/30 backdrop-blur-sm" style="display:none;">
  <div id="transactionSidebar" 
       class="bg-white h-full w-full max-w-md shadow-2xl p-6 relative transform translate-x-full transition-transform duration-300 ease-in-out">

    <!-- Close Button -->
    <button id="closeTransactionModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>

    <!-- Item Details (Dynamic) -->
    <div class="mb-4 mt-10">
      <div class="item-details space-y-1 text-gray-700">
        <!-- Items will be appended here via jQuery -->
      </div>
    </div>

    <!-- Summary -->
    <div class="border-t pt-3 mt-3 space-y-2 text-gray-700 text-sm summary-details">
      <div class="flex justify-between">
        <span>Total Services</span>
        <span id="totalServices">0 | ₱0.00</span>
      </div>
      <div class="flex justify-between">
        <span>Total Items</span>
        <span id="totalItems">0 | ₱0.00</span>
      </div>
      <div class="flex justify-between items-center text-red-500">
        <span>Item Discount</span>
        <input type="text" placeholder="Enter discount" name="InputedDiscount" 
               class="ml-2 border border-gray-300 rounded px-2 py-1 text-sm w-28 focus:outline-none focus:ring-1 focus:ring-red-500">
      </div>
      <div class="flex justify-between font-semibold text-gray-800">
        <span>Subtotal</span>
        <span id="subtotal">₱0.00</span>
      </div>
      <div class="flex justify-between font-semibold text-gray-800">
        <span>VAT (12%)</span>
        <span id="vatAmount">₱0.00</span>
      </div>
    </div>

    <!-- Total -->
    <div class="mt-4 border-t pt-3 flex justify-between items-center text-2xl font-bold text-gray-900">
      <span>Total</span>
      <span id="grandTotal">₱0.00</span>
    </div>

    <!-- Payment & Change -->
    <div class="mt-4 border-t pt-3 space-y-2 text-gray-700 text-sm payment-section">
      <div class="flex justify-between items-center">
        <span>Payment</span>
        <input type="number" min="0" step="0.01" id="paymentInput" placeholder="₱0.00" 
               class="ml-2 border border-gray-300 rounded px-2 py-1 text-sm w-32 focus:outline-none focus:ring-1 focus:ring-green-500">
      </div>
      <div class="flex justify-between items-center font-semibold text-gray-800">
        <span>Change</span>
        <span id="change">₱0.00</span>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-5 flex gap-3">
      <button type="button" id="BtnSubmit" 
              class="flex-1 bg-red-900 text-white py-2 rounded-xl hover:bg-red-700 transition">
        Complete Transaction
      </button>
      <button type="button" 
              class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">
        Void Transaction
      </button>
    </div>

  </div>
</div>








<?php 
include "../src/components/view/footer.php";
?>

<script src="../static/js/view/service.js"></script>
<script src="../static/js/view/fetchGrandTotal.js"></script>
<script src="../static/js/view/proceedToPay.js"></script>