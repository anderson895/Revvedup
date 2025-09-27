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
                    const empDiv = $(`<div class="mb-2" data-emp-id="${empId}"></div>`); 
                    empDiv.append(`<p class="font-medium text-gray-600 capitalize">${emp.employee_name}</p>`);

                    const serviceList = $('<div class="ml-4 space-y-1"></div>');
                    // Service Details
                    emp.services.forEach(s => {
                        serviceList.append(`
                            <div class="flex justify-between text-gray-700" 
                                data-service-id="${s.service_id}">
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
                    itemList.append(`
                        <div class="flex justify-between" 
                            data-item-id="${i.item_id}" 
                            data-prod-id="${i.prod_id}">
                            <span>${i.prod_name} x ${i.item_qty}</span>
                            <span>₱${(parseFloat(i.prod_price)*i.item_qty).toFixed(2)}</span>
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

// --- Close sidebar ---
function closeTransactionSidebar() {
    $("#transactionSidebar").addClass("translate-x-full");
    setTimeout(() => {
        $("#transactionModal").css("display", "none");
    }, 300);
}

// --- Update computation ---
function updateComputation() {
    const discountInput = $("input[name=InputedDiscount]");
    let discount = parseFloat(discountInput.val()) || 0;
    const payment = parseFloat($("#paymentInput").val()) || 0;

    // Prevent discount from exceeding total items
    if (discount > g_totalItem) {
        discount = g_totalItem;
        discountInput.val(discount.toFixed(2));
    }

    const discountedItems = g_totalItem - discount;
    const vat = discountedItems * 0.12;
    const subtotal = g_totalService + discountedItems;
    const grandTotal = subtotal + vat;
    const change = payment - grandTotal;

    // Display totals properly
    $("#totalServices").text(`₱${g_totalService > 0 ? g_totalService.toFixed(2) : '0.00'}`);
    $("#totalItems").text(`₱${g_totalItem > 0 ? g_totalItem.toFixed(2) : '0.00'}`);
    $("#subtotal").text(`₱${subtotal.toFixed(2)}`);
    $("#vatAmount").text(`₱${vat.toFixed(2)}`);
    $("#grandTotal").text(`₱${grandTotal.toFixed(2)}`);
    $("#change").text(`₱${(change > 0 ? change : 0).toFixed(2)}`);

    $(".mt-4.border-t.pt-3.flex.justify-between.items-center.text-2xl.font-bold.text-gray-900 span:last-child")
        .text(`₱${grandTotal.toFixed(2)}`);
}

// --- Input listeners ---
$(document).on("input", "input[name=InputedDiscount], #paymentInput", function() {
    updateComputation();
});

// --- SUBMIT TRANSACTION ---
// --- SUBMIT TRANSACTION ---
$('#BtnSubmit').click(function (e) { 
    e.preventDefault();

    // Collect services with emp_id
    let servicesArray = [];
    $(".service-details > div").each(function() {
        let empId = $(this).data("emp-id");
        $(this).find(".flex.justify-between").each(function() {
            let serviceName = $(this).find("span:first").text();
            let servicePrice = parseFloat($(this).find("span:last").text().replace('₱',''));
            servicesArray.push({ 
                service_id: $(this).data("service-id"), 
                name: serviceName, 
                price: servicePrice, 
                emp_id: empId 
            });
        });
    });

    // Collect items with prod_id
    let itemsArray = [];
    $(".item-details .flex.justify-between").each(function() {
        let itemText = $(this).find("span:first").text();
        let [name, qty] = itemText.split(' x ');
        let subtotal = parseFloat($(this).find("span:last").text().replace('₱',''));
        let prodId = $(this).data("prod-id"); 

        itemsArray.push({ 
            item_id: $(this).data("item-id"),
            prod_id: prodId, 
            name: name.trim(), 
            qty: parseInt(qty), 
            subtotal: subtotal 
        });
    });

    // ✅ Check if cart is empty
    if (servicesArray.length === 0 && itemsArray.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "Empty Cart",
            text: "Put cart first before submitting!",
            confirmButtonText: "OK"
        });
        return; // stop execution
    }

    // Collect other info
    let discount = parseFloat($("input[name=InputedDiscount]").val()) || 0;
    let payment = parseFloat($("#paymentInput").val()) || 0;
    let vat = parseFloat($("#vatAmount").text().replace('₱','')) || 0;
    let grandTotal = parseFloat($("#grandTotal").text().replace('₱','')) || 0;
    let change = parseFloat($("#change").text().replace('₱','')) || 0;

    // Build POST data
    let postData = {
        requestType: 'CheckOutOrder',
        services: servicesArray,
        items: itemsArray,
        discount: discount,
        vat: vat,
        grandTotal: grandTotal,
        payment: payment,
        change: change
    };

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "POST",
        data: postData,
        dataType: "json",
        success: function (response) {
            if (response.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Transaction submitted successfully!",
                    confirmButtonText: "OK"
                }).then(() => {
                    if (response.transaction_id) {
                        // Open receipt in new tab
                        window.open("receipt?transaction_id=" + response.transaction_id, "_blank");
                        // Reload current page
                        window.location.href = 'sales';
                    }
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message || "Failed to submit transaction."
                });
            }
        },
        error: function (xhr, status, error) {
            console.error("Update error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "Failed to update. Please try again."
            });
        }
    });
});
