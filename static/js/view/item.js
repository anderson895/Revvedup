$(document).ready(function() {
    let products = [];

    const list = $("#autocompleteList");
    const input = $("#searchInput");
    const hiddenInput = $("#selectedProductId");

    // Fetch products from API
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_all_product" },
        dataType: "json",
        success: function(res) {
            if (res.status === 200 && res.data.length > 0) {
                products = res.data;
                input.prop("disabled", false);
            } else {
                console.warn("No products found.");
            }
        },
        error: function() {
            console.error("Error fetching products.");
        }
    });

    // Filter and show products in dropdown
    input.on("input", function() {
        const query = $(this).val().trim().toLowerCase();
        list.empty();
        hiddenInput.val(""); // clear hidden input when typing

        if (!query) {
            list.addClass("hidden");
            return;
        }

        const filtered = products.filter(p => 
            p.prod_status == 1 && p.prod_name.toLowerCase().includes(query)
        );

        if (filtered.length === 0) {
            // Show "No results found" message
            const noResult = $(`
                <div class="p-2 text-gray-500 italic">No results found</div>
            `);
            list.append(noResult);
            list.removeClass("hidden");
            return;
        }

        filtered.forEach(p => {
            const item = $(`
                <div class="flex items-center p-2 hover:bg-gray-100 cursor-pointer">
                    <img src="../static/upload/${p.prod_img}" alt="${p.prod_name}" class="w-8 h-8 object-cover rounded mr-2">
                    <span>${p.prod_name} - ₱${p.prod_price}</span>
                </div>
            `);

            item.on("click", function() {
                // Fill input & hidden value
                input.val(p.prod_name);
                hiddenInput.val(p.prod_id);
                list.addClass("hidden");

                // Populate modal
                $("#modalProdImg").attr("src", `../static/upload/${p.prod_img}`);
                $("#modalProdName").text(p.prod_name);
                $("#modalProdPrice").text(`₱${p.prod_price}`);
                $("#modalProdQty").val(1);

                // Show modal
                $("#productModal").removeClass("hidden");
            });

            list.append(item);
        });

        list.removeClass("hidden");
    });

    // Close modal
    $("#closeModal").on("click", function() {
        $("#productModal").addClass("hidden");
    });

    // Add to cart button
    $("#addToCartBtn").on("click", function() {
        const prodId = hiddenInput.val();
        const qty = $("#modalProdQty").val();

        console.log("Add to cart:", prodId, "Quantity:", qty);

        // Here you can do AJAX to add the product to cart

        $("#productModal").addClass("hidden"); // close modal
    });

    // Hide dropdown if clicking outside
    $(document).on("click", function(e) {
        if (!$(e.target).closest("#searchInput, #autocompleteList").length) {
            list.addClass("hidden");
        }
    });
});














$("#frmAddToItem").submit(function (e) { 
    e.preventDefault();

    var selectedProductId = $('#selectedProductId').val().trim();
    var modalProdQty = $('#modalProdQty').val().trim();

    // Validate Service Name
    if (!selectedProductId) {
        alertify.error("Select Product First.");
        return;
    }

    // Validate Price
    if (!modalProdQty) {
        alertify.error("Please enter a Qty.");
        return;
    }
    if (isNaN(modalProdQty)) {
        alertify.error("Price must be a valid number.");
        return;
    }
    
    // Show spinner and disable button
    $('.spinner').show();
    $('#frmAddToItem button[type="submit"]').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'AddToItem');

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            $('.spinner').hide();
            $('#frmAddToItem button[type="submit"]').prop('disabled', false);

            if (response.status === 200) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    window.location.href = 'item';
                });
            } else {
                Swal.fire('Error', response.message || 'Something went wrong.', 'error');
            }
        }
    });

});














   $.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { requestType: "fetch_all_item_cart" },
    dataType: "json",
    success: function (res) {
        if (res.status === 200) {
            let totalPrice = 0; // Initialize total
            let html = '';

            if (res.data.length > 0) {
                res.data.forEach((data) => {
                    // Make sure price is treated as a number
                    totalPrice += parseFloat(data.service_price);

                    html += `
                        <tr class="hover:bg-[#2B2B2B] transition-colors">
                            <td class="p-3 text-center font-mono">${data.prod_id}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_name}</td>
                            <td class="p-3 text-center font-semibold">${data.item_qty}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_price}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_price * data.item_qty}</td>
                            <td class="p-3 text-center">
                                <button class="viewDetailsBtn bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold transition"
                                data-item_id ='${data.item_id}'>Update</button>
                                <button class="removeBtn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition"
                                data-item_id ='${data.item_id}'>Remove</button>
                            </td>
                        </tr>
                    `;
                });

                $('#itemTableBody').html(html);

            } else {
                $('#itemTableBody').html(`
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-400 italic">
                            No record found
                        </td>
                    </tr>
                `);
            }
        }
    }
});
