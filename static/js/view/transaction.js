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

                // View button click event
                $('.view-btn').on('click', function() {
                    const transactionId = $(this).data('id');
                    const transaction = res.data.find(t => t.transaction_id == transactionId);

                    if (transaction) {
                        // Transaction Info
                        $('#modalTransactionId').text(transaction.transaction_id);
                        const formattedDate = new Date(transaction.transaction_date).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        });
                        $('#modalTransactionDate').text(formattedDate);

                        // --------------------
                        // SERVICES grouped by emp_id
                        // --------------------
                       const services = JSON.parse(transaction.transaction_service || "[]");
                        let grouped = {};
                        let totalServices = 0;

                        services.forEach(s => {
                            if (!grouped[s.emp_id]) {
                                grouped[s.emp_id] = { 
                                    employee_name: s.employee_name || `Employee #${s.emp_id}`, 
                                    services: [] 
                                };
                            }
                            grouped[s.emp_id].services.push({ name: s.name, price: parseFloat(s.price) });
                            totalServices += parseFloat(s.price);
                        });

                        let servicesHtml = '';
                        Object.keys(grouped).forEach(empId => {
                            const emp = grouped[empId];
                            servicesHtml += `
                                <div class=" p-2 rounded mb-2">
                                    <p class="font-semibold text-gray-700 mb-1">${emp.employee_name}</p>
                                    ${emp.services.map(s => `
                                        <div class="flex justify-between text-sm pl-4">
                                            <span>${s.name}</span>
                                            <span>₱ ${s.price.toFixed(2)}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            `;
                        });
                        $('#modalServicesList').html(servicesHtml || `<p class="text-gray-400 italic">No services</p>`);
                        $('#modalTotalServices').text("₱ " + totalServices.toFixed(2));


                        // --------------------
                        // ITEMS
                        // --------------------
                        const items = JSON.parse(transaction.transaction_item || "[]");
                        let itemsHtml = '';
                        let totalItems = 0;

                        items.forEach(i => {
                            itemsHtml += `
                                <div class="flex justify-between text-sm">
                                    <span>${i.name} (x${i.qty})</span>
                                    <span>₱ ${parseFloat(i.subtotal).toFixed(2)}</span>
                                </div>
                            `;
                            totalItems += parseFloat(i.subtotal);
                        });

                        $('#modalItemsList').html(itemsHtml || `<p class="text-gray-400 italic">No items</p>`);
                        $('#modalTotalItems').text("₱ " + totalItems.toFixed(2));

                        // --------------------
                        // Totals
                        // --------------------
                        const discount = parseFloat(transaction.transaction_discount) || 0;
                        const vat = parseFloat(transaction.transaction_vat) || 0;
                        const subtotal = totalServices + totalItems - discount;

                        $('#modalDiscount').text("₱ " + discount.toFixed(2));
                        $('#modalSubtotal').text("₱ " + subtotal.toFixed(2));
                        $('#modalVAT').text("₱ " + vat.toFixed(2));
                        $('#modalTotal').text("₱ " + parseFloat(transaction.transaction_total).toFixed(2));
                        $('#modalPayment').text("₱ " + parseFloat(transaction.transaction_payment).toFixed(2));
                        $('#modalChange').text("₱ " + parseFloat(transaction.transaction_change).toFixed(2));

                        // Show modal
                        $('#transactionModal').fadeIn();
                    }
                });

                // Close modal events
                $('#closeModal, #closeModalFooter').on('click', function() {
                    $('#transactionModal').fadeOut();
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
