$.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { requestType: "fetch_all_transaction" },
    dataType: "json",
    success: function (res) {
        if (res.status === 200) {
            let html = '';

            if (res.data.length > 0) {
                res.data.forEach((data) => {
                    const date = new Date(data.transaction_date);
                    const formattedDate = date.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });

                    html += `
                        <tr class="hover:bg-gray-200 transition-colors">
                            <td class="p-3 text-left font-mono">${formattedDate}</td>
                            <td class="p-3 text-left font-semibold">${data.transaction_id}</td>
                            <td class="p-3 text-left font-semibold">₱ ${parseFloat(data.transaction_total).toFixed(2)}</td>
                            <td class="p-3 text-center">
                                <button class="bg-red-800 hover:bg-red-700 text-white font-semibold py-1 px-3 rounded view-btn" data-id="${data.transaction_id}">
                                    View
                                </button>
                            </td>
                        </tr>
                    `;
                });

                $('#transactionTableBody').html(html);

                // View button click event -> open receipt.php in new tab
                $('.view-btn').on('click', function() {
                    const transactionId = $(this).data('id');
                    window.open(`receipt.php?transaction_id=${transactionId}`, '_blank');
                });


            } else {
                $('#transactionTableBody').html(`
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-400 italic">
                            <span class="material-icons" style="font-size: 48px; display: block; margin-bottom: 8px;">
                                shopping_cart
                            </span>
                            No transaction found
                        </td>
                    </tr>
                `);
            }
        }
    }
});