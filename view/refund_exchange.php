<?php 
include "../src/components/view/header.php";

$transactionId=$_GET['transactionId'];
?>

<main class="flex-1 flex flex-col">

<header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">REFUND AND EXCHANGE</h1>
</header>

<header class="px-4 py-3 border-b bg-white flex flex-col gap-2">
  <div class="flex justify-between text-sm text-gray-600">
    <input type="hidden" id="transactionId" name="transactionId" value="<?=$transactionId?>">
    <span>Transaction No. <strong>123456</strong></span>
    <span>Date: <strong><?= date("Y/m/d") ?></strong></span>
  </div>

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

<section class="flex-1 flex flex-col px-4 py-3">
  <div class="overflow-x-auto rounded-lg shadow">
    <table class="min-w-full border border-gray-200 text-sm">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="px-4 py-2 text-left font-semibold border-b">Item Name</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Quantity</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Unit Price</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Refund</th>
          <th class="px-4 py-2 text-left font-semibold border-b">Exchange</th>
        </tr>
      </thead>
      <tbody class="transactionTableBody"></tbody>
    </table>
  </div>
</section>

<footer class="flex flex-col sm:flex-row gap-3 justify-between items-center bg-white border-t px-4 py-4">
  <div class="flex-1">
    <div class="flex justify-between items-center bg-gray-50 border px-4 py-2 rounded shadow-sm w-full sm:w-80">
      <span class="font-medium text-gray-700">Total Refund</span>
      <span class="font-bold text-lg text-gray-900">0.00</span>
    </div>
  </div>

  <div class="flex flex-col sm:flex-row gap-3 items-center">
    <button class="bg-red-800 hover:bg-red-700 text-white px-6 py-2 rounded shadow font-medium w-full sm:w-auto">
      Complete Transaction
    </button>
    <button class="bg-red-800 hover:bg-red-700 text-white px-4 py-2 rounded shadow font-medium">
      Refund All
    </button>
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

function updateGrandTotal() {
    let grandTotal = 0;
    $('.refund-total').each(function () {
        const val = parseFloat($(this).val().replace(/,/g, '')) || 0;
        grandTotal += val;
    });
    $('.flex-1 span.font-bold').text(grandTotal.toLocaleString());
}

function enforceMaxCombinedQty($row) {
    const maxQty = parseInt($row.find('td:nth-child(2)').text());
    let refundQty = parseInt($row.find('.refund-qty').val()) || 0;
    let exchangeQty = parseInt($row.find('.exchange-qty').val()) || 0;

    if (refundQty + exchangeQty > maxQty) {
        const $input = $(document.activeElement);
        const otherInput = $input.hasClass('refund-qty') ? $row.find('.exchange-qty') : $row.find('.refund-qty');
        const adjusted = Math.max(maxQty - (parseInt(otherInput.val()) || 0), 0);
        $input.val(adjusted);

        if ($input.hasClass('refund-qty')) {
            const price = parseFloat($input.data('price'));
            const total = (adjusted * price).toFixed(2);
            $input.siblings('.refund-total').val(parseFloat(total).toLocaleString());
        }
    }
}

$.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { transactionId: transactionId, requestType: "fetch_transaction_record" },
    dataType: "json",
    success: function(res) {
        const tbody = $('.transactionTableBody');
        tbody.empty();

        if (res.status === 200 && res.data.transaction_item.length > 0) {
            const transaction = res.data;
            $('span:contains("Transaction No.") strong').text(transaction.transaction_id);
            $('span:contains("Date:") strong').text(new Date(transaction.transaction_date).toLocaleDateString());

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
                                data-price="${unitPrice}" max="${item.qty}" min="0" value="0">
                            <input type="text" placeholder="Total Price"
                                class="refund-total border rounded px-2 py-1 w-32 focus:outline-none focus:ring focus:ring-blue-300" readonly value="0.00">
                        </div>
                    </td>

                    <td class="px-4 py-2 border-b">
                        <input type="number" placeholder="Qty"
                            class="exchange-qty border rounded px-2 py-1 w-20 focus:outline-none focus:ring focus:ring-blue-300"
                            max="${item.qty}" min="0" value="0">
                    </td>
                </tr>`;
                tbody.append(row);
            });

            // Handle input events
            $(document).on('input', '.refund-qty, .exchange-qty', function() {
                let val = $(this).val();
                if (val === null || val === "" || isNaN(val)) val = 0;
                val = parseInt(val);
                if (val < 0) val = 0;
                $(this).val(val);

                const $row = $(this).closest('tr');
                enforceMaxCombinedQty($row);

                if ($(this).hasClass('refund-qty')) {
                    const price = parseFloat($(this).data('price'));
                    const total = (parseInt($(this).val()) * price).toFixed(2);
                    $(this).siblings('.refund-total').val(parseFloat(total).toLocaleString());
                    updateGrandTotal();
                }
            });

            // Refund All button
            $('button:contains("Refund All")').click(function() {
                $('table tbody tr').each(function() {
                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    $(this).find('.exchange-qty').val(0);
                    $(this).find('.refund-qty').val(maxQty).trigger('input');
                });
            });

            // Exchange All button
            $('button:contains("Exchange All")').click(function() {
                $('table tbody tr').each(function() {
                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    $(this).find('.refund-qty').val(0).trigger('input');
                    $(this).find('.exchange-qty').val(maxQty);
                });
            });

            // Complete Transaction button
            $('button:contains("Complete Transaction")').click(function() {
                const refundData = [];
                const exchangeData = [];
                let valid = true;

                $('table tbody tr').each(function() {
                    const itemName = $(this).find('td:first').text();
                    let refundQty = parseInt($(this).find('.refund-qty').val()) || 0;
                    let exchangeQty = parseInt($(this).find('.exchange-qty').val()) || 0;

                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    if (refundQty + exchangeQty > maxQty) {
                        alert(`The combined refund and exchange quantity for "${itemName}" exceeds the transaction quantity.`);
                        valid = false;
                        return false;
                    }

                    if (refundQty > 0) refundData.push({name: itemName, qty: refundQty});
                    if (exchangeQty > 0) exchangeData.push({name: itemName, qty: exchangeQty});
                });

                if (!valid) return;

                $.ajax({
                url: "../controller/end-points/controller.php",
                method: "POST",
                data: {
                    transactionId: transactionId,
                    requestType: "complete_transaction",
                    refund: JSON.stringify(refundData),
                    exchange: JSON.stringify(exchangeData)
                },
                dataType: "json",
                success: function(res) {
                    if (res.status === 200) {
                        alertify.success('Transaction completed successfully!');
                          setTimeout(() => {
                              location.reload();
                          }, 1000); // 1000 milliseconds = 1 second

                    } else {
                        alertify.error(res.message);
                    }
                }
            });

            });

        } else {
            tbody.append(`<tr><td colspan="5" class="text-center py-4 text-gray-500 font-medium">No records found</td></tr>`);
            $('span:contains("Transaction No.") strong').text('-');
            $('span:contains("Date:") strong').text('-');
            $('.flex-1 span.font-bold').text('0.00');
        }
    },
    error: function() {
        const tbody = $('.transactionTableBody');
        tbody.empty();
        tbody.append(`<tr><td colspan="5" class="text-center py-4 text-red-500 font-medium">Failed to fetch transaction data</td></tr>`);
        $('span:contains("Transaction No.") strong').text('-');
        $('span:contains("Date:") strong').text('-');
        $('.flex-1 span.font-bold').text('0.00');
    }
});
</script>


<!-- <script src="../static/js/view/refund_exchange.js"></script> -->