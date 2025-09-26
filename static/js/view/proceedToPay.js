$(".proceedToPayBtn").click(function (e) { 
    e.preventDefault();
    openTransactionModal();
});

// Close button
$("#closeTransactionModal").click(function (e) { 
    e.preventDefault();
    closeTransactionSidebar();
});

// Close kapag click outside sidebar content
$(document).on("click", function (e) {
    if ($(e.target).is("#transactionModal")) {
        closeTransactionSidebar();
    }
});

// --- GLOBALS for computation ---
let g_totalService = 0;
let g_totalItem = 0;

// --- AJAX + render sidebar ---
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

                // Save totals globally
                g_totalService = services.reduce((sum,s)=>sum+parseFloat(s.service_price),0);
                g_totalItem = items.reduce((sum,i)=>sum+parseFloat(i.prod_price)*parseInt(i.item_qty),0);

                // Initial computation
                updateComputation();

                // --- SHOW SIDEBAR WITH SLIDE ---
                modal.css("display", "flex"); 
                setTimeout(() => {
                    $("#transactionSidebar").removeClass("translate-x-full");
                }, 10);
            }
        },
        error: function(err) {
            console.log(err);
        }
    });
}

// --- Function to close sidebar ---
function closeTransactionSidebar() {
    $("#transactionSidebar").addClass("translate-x-full");
    setTimeout(() => {
        $("#transactionModal").css("display", "none");
    }, 300); // match transition duration
}

// --- Auto Update Function (unchanged) ---
function updateComputation() {
    let discount = parseFloat($("input[name=InputedDiscount]").val()) || 0;
    let payment = parseFloat($("#paymentInput").val()) || 0;

    if (discount > g_totalItem) {
        discount = g_totalItem;
        $("input[name=InputedDiscount]").val(discount.toFixed(2));
    }

    let discountedItems = g_totalItem - discount;
    let vat = discountedItems * 0.12;
    let subtotal = g_totalService + discountedItems;
    let grandTotal = subtotal + vat;
    let change = payment - grandTotal;

    $("#totalServices").text(`${g_totalService > 0 ? '-' : '0'} | ₱${g_totalService.toFixed(2)}`);
    $("#totalItems").text(`${g_totalItem > 0 ? '-' : '0'} | ₱${g_totalItem.toFixed(2)}`);
    $("#subtotal").text(`₱${subtotal.toFixed(2)}`);
    $("#vatAmount").text(`₱${vat.toFixed(2)}`);
    $("#grandTotal").text(`₱${grandTotal.toFixed(2)}`);
    $("#change").text(`₱${(change > 0 ? change : 0).toFixed(2)}`);

    $(".mt-4.border-t.pt-3.flex.justify-between.items-center.text-2xl.font-bold.text-gray-900 span:last-child")
        .text(`₱${grandTotal.toFixed(2)}`);
}

$(document).on("input", "input[name=InputedDiscount], #paymentInput", function() {
    updateComputation();
});
