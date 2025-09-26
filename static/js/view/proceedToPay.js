$(".proceedToPayBtn").click(function (e) { 
    e.preventDefault();
    openTransactionModal();
});

// Close button
$("#closeTransactionModal").click(function (e) { 
    e.preventDefault();
    $("#transactionModal").fadeOut();
});

// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#transactionModal")) {
        $("#transactionModal").fadeOut();
    }
});

// Global state for summary calculations
let baseSubtotal = 0;
let currentDiscount = 0;
let currentTotalWithVAT = 0;

// Helper: update summary values
function updateSummary(modal) {
    // Clamp discount
    let discount = currentDiscount > baseSubtotal ? baseSubtotal : currentDiscount;

    let newSubtotal = baseSubtotal - discount;
    let vat = newSubtotal * 0.12;
    let newTotal = newSubtotal + vat;

    // Update displayed values
    modal.find("span:contains('Subtotal')").next().text(`₱${newSubtotal.toFixed(2)}`);
    modal.find("span:contains('VAT (12%)')").next().text(`₱${vat.toFixed(2)}`);
    modal.find(".mt-4.border-t.pt-3.flex.justify-between.items-center.text-2xl.font-bold.text-gray-900 span:last-child")
        .text(`₱${newTotal.toFixed(2)}`);
    modal.find("div.flex.font-bold.text-gray-900.text-xl span:last-child")
        .text(`₱${newTotal.toFixed(2)}`);

    // Save latest total for payment calculations
    currentTotalWithVAT = newTotal;

    // Update payment/change if user already typed something
    let payment = parseFloat(modal.find("#paymentInput").val()) || 0;
    let change = payment - currentTotalWithVAT;
    if (change < 0) change = 0;
    modal.find("#change").text(`₱${change.toFixed(2)}`);
}

// AJAX + render modal
function openTransactionModal() {
    const modal = $("#transactionModal");
    modal.find(".service-details, .item-details").remove();

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_all_cart" },
        dataType: "json",
        success: function(res) {
            if (res.status === 200) {
                const services = res.data.services;
                const items = res.data.items;

                // Group services by employee
                const groupedServices = {};
                services.forEach(s => {
                    if (!groupedServices[s.service_employee_id]) {
                        groupedServices[s.service_employee_id] = {
                            employee_name: s.emp_fname && s.emp_lname 
                                ? `${s.emp_fname} ${s.emp_lname} #${s.emp_id}`
                                : "No Name",
                            services: []
                        };
                    }
                    groupedServices[s.service_employee_id].services.push(s);
                });

                // Service Details
                const serviceContainer = $('<div class="service-details mb-4"><h3 class="font-semibold text-gray-700 mb-2">Service Details</h3></div>');
                for (const empId in groupedServices) {
                    const emp = groupedServices[empId];
                    const empDiv = $('<div class="mb-2"></div>');
                    empDiv.append(`<p class="font-medium text-gray-600 capitalize">${emp.employee_name}</p>`);

                    const serviceList = $('<div class="ml-4 space-y-1"></div>');
                    emp.services.forEach(s => {
                        serviceList.append(`
                            <div class="flex justify-between text-gray-700">
                                <span>${s.service_name}</span>
                                <span>₱${parseFloat(s.service_price).toFixed(2)}</span>
                            </div>
                        `);
                    });

                    empDiv.append(serviceList);
                    serviceContainer.append(empDiv);
                }
                modal.find("button#closeTransactionModal").after(serviceContainer);

                // Item Details
                const itemContainer = $('<div class="item-details mb-4"><h3 class="font-semibold text-gray-700 mb-2">Item Details</h3></div>');
                const itemList = $('<div class="space-y-1 text-gray-700"></div>');
                items.forEach(i => {
                    const subtotal = parseFloat(i.prod_price) * parseInt(i.item_qty);
                    itemList.append(`
                        <div class="flex justify-between">
                            <span>${i.prod_name} x ${i.item_qty}</span>
                            <span>₱${subtotal.toFixed(2)}</span>
                        </div>
                    `);
                });
                itemContainer.append(itemList);
                serviceContainer.after(itemContainer);

                // Summary calculations
                const totalService = services.reduce((sum,s)=>sum+parseFloat(s.service_price),0);
                const totalItem = items.reduce((sum,i)=>sum+parseFloat(i.prod_price)*parseInt(i.item_qty),0);
                baseSubtotal = totalService + totalItem;
                currentDiscount = 0; // reset discount
                const vat = baseSubtotal * 0.12;
                currentTotalWithVAT = baseSubtotal + vat;

                // Render summary
                modal.find(".border-t.pt-3.mt-3.space-y-2").html(`
                    <div class="flex justify-between">
                        <span>Total Services</span>
                        <span>${services.length} | ₱${totalService.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Total Items</span>
                        <span>${items.length} | ₱${totalItem.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between items-center text-red-500">
                        <span>Item Discount</span>
                        <input type="number" min="0" placeholder="Enter discount" name="InputedDiscount" 
                               class="ml-2 border border-gray-300 rounded px-2 py-1 text-sm w-28 focus:outline-none focus:ring-1 focus:ring-red-500">
                    </div>
                    <div class="flex justify-between font-semibold text-gray-800">
                        <span>Subtotal</span>
                        <span>₱${baseSubtotal.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-gray-800">
                        <span>VAT (12%)</span>
                        <span>₱${vat.toFixed(2)}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 text-xl">
                        <span>Total</span>
                        <span>₱${currentTotalWithVAT.toFixed(2)}</span>
                    </div>
                `);

                // Update total at the bottom
                modal.find(".mt-4.border-t.pt-3.flex.justify-between.items-center.text-2xl.font-bold.text-gray-900 span:last-child")
                    .text(`₱${currentTotalWithVAT.toFixed(2)}`);

                // Attach listeners
                modal.find('input[name="InputedDiscount"]').on("input", function () {
                    currentDiscount = parseFloat($(this).val()) || 0;
                    updateSummary(modal);
                });

                modal.find("#paymentInput").on("input", function () {
                    let payment = parseFloat($(this).val()) || 0;
                    let change = payment - currentTotalWithVAT;
                    if (change < 0) change = 0;
                    modal.find("#change").text(`₱${change.toFixed(2)}`);
                });

                modal.fadeIn();
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
}
