<!-- Transaction Sidebar Modal -->
<div id="RefundExchangeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display: none;">
  <!-- Modal Content -->
  <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 relative">
    
    <!-- Modal Header -->
    <h2 class="text-2xl sm:text-3xl font-semibold italic text-center text-gray-800 mb-6">
      Refund / Exchange
    </h2>

    <!-- Form -->
    <form action="refund_exchange" method="GET" class="grid grid-cols-1 gap-6" >
      
      <!-- Transaction ID Field -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <label for="transactionId" class="text-gray-700 font-medium">Transaction ID</label>
        <input type="number" id="transactionId" name="transactionId" placeholder="Enter transaction ID" 
               class="w-full sm:w-2/3 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-900 focus:border-red-900 transition"/>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end">
        <button type="submit" id="BtnFindRecord" 
                class="cursor-pointer bg-red-900 text-white py-2 px-6 rounded-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-900 transition">
          Find Record
        </button>
      </div>

    </form>
    
  </div>
</div>







<script src="../static/js/view/modal_refund.js"></script>