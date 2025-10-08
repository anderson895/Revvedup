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

        if (res.status === 200) {
            const transaction = res.data;

            // Show transaction info
            $('.transaction-info').show();
            $('span:contains("Transaction No.") strong').text(transaction.transaction_id);
            $('span:contains("Date:") strong').text(new Date(transaction.transaction_date).toLocaleDateString());

            if (transaction.transaction_item && transaction.transaction_item.length > 0) {
                transaction.transaction_item.forEach(item => {
                    const unitPrice = (item.qty > 0 && item.subtotal > 0) 
                        ? (item.subtotal / item.qty).toFixed(2) 
                        : "0.00";

                    // Add data-prod-id to <tr>
                    const row = `
                    <tr class="hover:bg-gray-50 transition" data-prod-id="${item.prod_id}">
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
            } else {
                tbody.append(`<tr><td colspan="5" class="text-center py-4 text-gray-500 font-medium">No items in this transaction</td></tr>`);
            }

            // Input handlers
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
            $('button:contains("Refund All")').off().click(function() {
                $('table tbody tr').each(function() {
                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    $(this).find('.exchange-qty').val(0);
                    $(this).find('.refund-qty').val(maxQty).trigger('input');
                });
            });

            // Exchange All button
            $('button:contains("Exchange All")').off().click(function() {
                $('table tbody tr').each(function() {
                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    $(this).find('.refund-qty').val(0).trigger('input');
                    $(this).find('.exchange-qty').val(maxQty);
                });
            });

            // Complete Transaction button
            $('#btnComplete_transaction').off().click(function() {
                const refundData = [];
                const exchangeData = [];
                let valid = true;

                $('table tbody tr').each(function() {
                    const itemName = $(this).find('td:first').text();
                    const prodId = $(this).data('prod-id');
                    let refundQty = parseInt($(this).find('.refund-qty').val()) || 0;
                    let exchangeQty = parseInt($(this).find('.exchange-qty').val()) || 0;

                    const maxQty = parseInt($(this).find('td:nth-child(2)').text());
                    if (refundQty + exchangeQty > maxQty) {
                        alert(`The combined refund and exchange quantity for "${itemName}" exceeds the transaction quantity.`);
                        valid = false;
                        return false;
                    }

                    if (refundQty > 0) refundData.push({prod_id: prodId, name: itemName, qty: refundQty});
                    if (exchangeQty > 0) exchangeData.push({prod_id: prodId, name: itemName, qty: exchangeQty});
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
                            setTimeout(() => { location.reload(); }, 1000);
                        } else {
                            alertify.error(res.message);
                        }
                    }
                });
            });

        } else if (res.status === 404) {
            $('.transaction-info').hide();
            tbody.append(`<tr><td colspan="5" class="text-center py-4 text-red-500 font-bold">TRANSACTION ID NOT EXIST</td></tr>`);
        }
    },
    error: function() {
        const tbody = $('.transactionTableBody');
        tbody.empty();
        tbody.append(`<tr><td colspan="5" class="text-center py-4 text-red-500 font-medium">Failed to fetch transaction data</td></tr>`);
        $('.transaction-info').hide();
    }
});
