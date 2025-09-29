<?php 
include "../src/components/view/header.php";


$transactionId=$_GET['transactionId'];
?>
  

  
<!-- Main Content -->
<main class="flex-1 flex flex-col">


<header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">REFUND AND EXCHANGE</h1>
  </header>


<header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
  <!-- Top Row -->
  <div class="flex justify-between text-sm text-gray-600">

  <input type="hidden" id="transactionId" name="transactionId" value="<?=$transactionId?>">

    <span>Transaction No. <strong>123456</strong></span>
    <span>Date: <strong><?= date("Y/m/d") ?></strong></span>
  </div>

  <!-- Bottom Row (Buttons) -->
  <div class="flex flex-wrap gap-2 justify-end">
    <a href="#" <?=$authorize?> 
       class="btnRefundExchange flex items-center gap-2 bg-pink-200 text-red-800 px-4 py-2 rounded border border-black-300 text-sm font-bold shadow-sm">
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
  </div>
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
      <tbody class="transactionTableBody">
        <!-- <tr class="hover:bg-gray-50 transition">
          <td class="px-4 py-2 border-b">ItemName_2</td>
          <td class="px-4 py-2 border-b">4</td>
          <td class="px-4 py-2 border-b">1,896.00</td>

          <td class="px-4 py-2 border-b">
            <div class="flex flex-row gap-2">
              <input type="number" placeholder="Quantity"
                class="border rounded px-2 py-1 w-24 focus:outline-none focus:ring focus:ring-blue-300">

              <input type="text" placeholder="Total Price"
                class="border rounded px-2 py-1 w-32 focus:outline-none focus:ring focus:ring-blue-300">
            </div>
          </td>

          <td class="px-4 py-2 border-b">
            <input type="number" placeholder="Qty"
              class="border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:ring-blue-300">
          </td>
        </tr> -->
      </tbody>
    </table>

  </div>
</section>

<!-- Footer -->
<footer class="flex flex-col sm:flex-row gap-3 justify-between items-center bg-white border-t px-4 py-4">
  <!-- Left: Total Refund -->
  <div class="flex-1">
    <div class="flex justify-between items-center bg-gray-50 border px-4 py-2 rounded shadow-sm w-full sm:w-80">
      <span class="font-medium text-gray-700">Total Refund</span>
      <span class="font-bold text-lg text-gray-900">1,896.00</span>
    </div>
  </div>

  <!-- Right: Action Buttons -->
  <div class="flex flex-col sm:flex-row gap-3 items-center">
    <!-- Complete Transaction -->
    <button class="bg-red-800 hover:bg-red-700 text-white px-6 py-2 rounded shadow font-medium w-full sm:w-auto">
      Complete Transaction
    </button>

    <!-- Refund All -->
    <button class="bg-red-800 hover:bg-red-700 text-white px-4 py-2 rounded shadow font-medium">
      Refund All
    </button>

    <!-- Exchange All -->
    <button class="bg-red-800 hover:bg-red-700 text-white px-4 py-2 rounded shadow font-medium">
      Exchange All
    </button>
  </div>
</footer>


  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
include "modal_refund.php";
?>



<script>
 const transactionId = $('#transactionId').val();

$.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { transactionId: transactionId, requestType: "fetch_transaction_record" },
    dataType: "json",
    success: function (res) {
        const tbody = $('.transactionTableBody');
        tbody.empty(); // Clear existing rows

        if (res.status === 200 && res.data.transaction_item.length > 0) {
            const transaction = res.data;

            // Update transaction info in header
            $('span:contains("Transaction No.") strong').text(transaction.transaction_id);
            $('span:contains("Date:") strong').text(new Date(transaction.transaction_date).toLocaleDateString());

            // Populate table with items
            transaction.transaction_item.forEach(item => {
                const unitPrice = (item.subtotal / item.qty).toFixed(2);
                const row = `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2 border-b">${item.name}</td>
                    <td class="px-4 py-2 border-b">${item.qty}</td>
                    <td class="px-4 py-2 border-b">${parseFloat(unitPrice).toLocaleString()}</td>

                    <td class="px-4 py-2 border-b">
                        <div class="flex flex-row gap-2">
                            <input type="number" placeholder="Quantity"
                                class="refund-qty border rounded px-2 py-1 w-24 focus:outline-none focus:ring focus:ring-blue-300"
                                data-price="${unitPrice}" max="${item.qty}" min="0">
                            <input type="text" placeholder="Total Price"
                                class="refund-total border rounded px-2 py-1 w-32 focus:outline-none focus:ring focus:ring-blue-300" readonly>
                        </div>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <input type="number" placeholder="Qty"
                            class="exchange-qty border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:ring-blue-300"
                            max="${item.qty}" min="0">
                    </td>
                </tr>`;
                tbody.append(row);
            });

            // Initialize total refund
            $('.flex-1 span.font-bold').text('0.00');

            // Event listener for refund quantity
            $(document).on('input', '.refund-qty', function () {
                let qty = parseInt($(this).val()) || 0;
                const maxQty = parseInt($(this).attr('max'));

                // Enforce max quantity
                if (qty > maxQty) {
                    qty = maxQty;
                    $(this).val(qty);
                }

                const price = parseFloat($(this).data('price'));
                const total = (qty * price).toFixed(2);
                $(this).siblings('.refund-total').val(parseFloat(total).toLocaleString());

                // Update grand total refund
                let grandTotal = 0;
                $('.refund-total').each(function () {
                    const val = parseFloat($(this).val().replace(/,/g, '')) || 0;
                    grandTotal += val;
                });
                $('.flex-1 span.font-bold').text(grandTotal.toLocaleString());
            });

            // Event listener for exchange quantity
            $(document).on('input', '.exchange-qty', function () {
                let qty = parseInt($(this).val()) || 0;
                const maxQty = parseInt($(this).attr('max'));

                // Enforce max quantity
                if (qty > maxQty) {
                    qty = maxQty;
                    $(this).val(qty);
                }
            });

        } else {
            // Show "No records found"
            const noRecordRow = `
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500 font-medium">No records found</td>
            </tr>`;
            tbody.append(noRecordRow);

            // Reset transaction info
            $('span:contains("Transaction No.") strong').text('-');
            $('span:contains("Date:") strong').text('-');
            $('.flex-1 span.font-bold').text('0.00');
        }
    },
    error: function () {
        const tbody = $('.transactionTableBody');
        tbody.empty();
        const errorRow = `
        <tr>
            <td colspan="5" class="text-center py-4 text-red-500 font-medium">Failed to fetch transaction data</td>
        </tr>`;
        tbody.append(errorRow);

        $('span:contains("Transaction No.") strong').text('-');
        $('span:contains("Date:") strong').text('-');
        $('.flex-1 span.font-bold').text('0.00');
    }
});


</script>

<script src="../static/js/view/refund_exchange.js"></script>