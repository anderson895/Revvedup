<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

  <header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">ITEM</h1>
  </header>
  <!-- Top Bar -->
  <header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
  <!-- Top Row -->
   <div class="flex items-center space-x-2 text-sm text-gray-600">
      <!-- Input with search icon -->
      <div class="relative flex-1 min-w-[150px]">
        <span class="material-icons absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-base">
          search
        </span>
        <input 
          type="text" 
          id="searchInput"
          placeholder="Search Item"
          class="w-full border rounded pl-8 pr-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400"
          autocomplete="off"
        >
        <!-- Hidden input to store prod_id -->
       
        
        <div id="autocompleteList" class="absolute z-50 w-full bg-white border rounded mt-1 max-h-48 overflow-y-auto hidden"></div>
      </div>



    </div>



<!-- Product Modal -->
<div id="productModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm hidden">
  <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6 relative">
    
    <!-- Close Button -->
    <button type="button" id="closeModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
    
    <!-- Product Info -->
    <div class="flex flex-col items-center mb-4 text-center">
      <img id="modalProdImg" src="" alt="Product Image" class="w-32 h-32 object-cover rounded-lg border mb-4">
      <h3 id="modalProdName" class="text-xl font-semibold text-gray-800 mb-1"></h3>
      <p id="modalProdPrice" class="text-red-600 font-medium text-lg"></p>
    </div>

    <!-- Add to Cart Form -->
    <form id="frmAddToItem" class="space-y-4">
      <!-- Hidden Product ID -->
      <input type="hidden" id="selectedProductId" name="selectedProductId">

      <!-- Quantity -->
      <div>
        <label for="modalProdQty" class="block text-sm font-medium text-gray-700">Quantity</label>
        <input 
          type="number" 
          id="modalProdQty" 
          name="modalProdQty" 
          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-400" 
          min="1" 
          value="1"
        >
      </div>

      <!-- Submit Button -->
      <div class="flex justify-center">
        <button type="submit" id="addToCartBtn" class="cursor-pointer bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900 transition">Add to Cart</button>
      </div>
    </form>
  </div>
</div>







  <!-- Bottom Row (Buttons) -->
  <div class="flex flex-wrap gap-2 justify-end">
    <a href="#" 
       class="btnRefundExchange font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      REFUND | EXCHANGE
    </a>

    <a href="service" 
       class="font-bold flex items-center gap-2 bg-red-800 hover:bg-red-700 transition text-white px-4 py-2 rounded text-sm whitespace-nowrap inline-block text-center shadow">
      SERVICE
    </a>

    <a href="item" 
       class="flex items-center gap-2 bg-pink-200 text-red-800 px-4 py-2 rounded border border-black-300 text-sm font-bold shadow-sm">
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
          <th class="px-4 py-2 text-center font-semibold border-b">Item ID</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Item Name</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Quantity</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Unit Price</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Total</th>
          <th class="px-4 py-2 text-center font-semibold border-b">Action</th>
        </tr>
      </thead>

      <!-- Table Body -->
      <tbody id="itemTableBody">
        <!-- DYNAMIC PART -->
      </tbody>
    </table>
  </div>
</section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
    <!-- Proceed Button -->
    <button class="proceedToPayBtn cursor-pointer flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition duration-200 ease-in-out w-full sm:w-auto">
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
        <span>Subtotal (VAT Inclusive)</span>
        <span id="subtotal">₱0.00</span>
      </div>

      <!-- VAT Breakdown -->
      <div class="mt-2 border-t pt-2 space-y-1 text-gray-700">
        <div class="flex justify-between">
          <span>VATable Sales</span>
          <span id="vatableSales">₱0.00</span>
        </div>
        <div class="flex justify-between">
          <span>VAT-Exempt Sales</span>
          <span id="vatExemptSales">₱0.00</span>
        </div>
        <div class="flex justify-between">
          <span>VAT Zero-Rated Sales</span>
          <span id="vatZeroRatedSales">₱0.00</span>
        </div>
        <div class="flex justify-between font-semibold">
          <span>VAT Amount (12%)</span>
          <span id="vatAmount">₱0.00</span>
        </div>
      </div>
    </div>

    <!-- Total -->
    <div class="mt-4 border-t pt-3 flex justify-between items-center text-2xl font-bold text-gray-900">
      <span>Total Due</span>
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
        class="flex-1 bg-red-900 text-white py-2 rounded-xl hover:bg-red-700 transition cursor-pointer">
        Complete Transaction
      </button>

      <button type="button" id="BtnVoidCart"
              class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-xl hover:bg-gray-300 transition">
        Void Transaction
      </button>
    </div>

  </div>
</div>





<?php 
include "../src/components/view/footer.php";
include "modal_refund.php";
?>

<script src="../static/js/view/item.js"></script>
<script src="../static/js/view/fetchGrandTotal.js"></script>
<script src="../static/js/view/proceedToPay.js"></script>