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

        // console.log("Add to cart:", prodId, "Quantity:", qty);

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








$('#frmAddToItem').submit(function(e){
    e.preventDefault();

    const item_id = $(this).data('editing-id'); // undefined if adding
    const prodId = $('#selectedProductId').val();
    const qty = parseInt($('#modalProdQty').val(), 10);

    console.log(prodId); // debug

    if(!prodId || isNaN(qty) || qty <= 0){
        alertify.error("Please select a product and enter a valid quantity.");
        return;
    }

    let formData = new FormData(this);
    formData.append('requestType', item_id ? 'updateItemCart' : 'AddToItem');
    if(item_id) formData.append('item_id', item_id);

    $.ajax({
        type: 'POST',
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(res){
            if(res.status === 200){
                Swal.fire('Success!', res.message, 'success').then(()=>{
                    window.location.href = 'item';
                });
            } else {
                Swal.fire('Error', res.message || 'Something went wrong', 'error');
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
                        <tr class="hover:bg-gray-200 transition-colors">
                            <td class="p-3 text-center font-mono">${data.prod_id}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_name}</td>
                            <td class="p-3 text-center font-semibold">${data.item_qty}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_price}</td>
                            <td class="p-3 text-center font-semibold">${data.prod_price * data.item_qty}</td>
                            <td class="p-3 text-center">
                                <button class="EditBtn bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold transition"
                                data-item_id ='${data.item_id}'>Edit</button>
                                <button class="removeBtn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition"
                                data-item_id ='${data.item_id}'
                                data-prod_name ='${data.prod_name}'
                                >Remove</button>
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
















$(document).on('click', '.removeBtn', function(e) {
        e.preventDefault();
        const id = $(this).data("item_id");
        const prod_name = $(this).data("prod_name");
        
        Swal.fire({
            title: `Remove <span style="color:red;">${prod_name}</span> from cart?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    type: 'POST',
                    data: { id: id, requestType: 'deleteCart',table:'item_cart',collumn:'item_id' },
                    dataType: 'json', 
                    success: function(response) {
                      console.log(response);
                        if (response.status === 200) {
                            Swal.fire(
                                'Removed!',
                                response.message, 
                                'success'
                            ).then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message, 
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'There was a problem with the request.',
                            'error'
                        );
                    }
                });
            }
        });
    });













    $(document).on('click', '.EditBtn', function(e) {
    e.preventDefault();

    const item_id = $(this).data('item_id');

    // Fetch item data by ID
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "getItemById", item_id: item_id },
        dataType: "json",
        success: function(res) {
            if(res.status === 200){
                const data = res.data;

                // Populate modal fields
                $('#selectedProductId').val(data.prod_id);
                $('#searchInput').val(data.prod_name);
                $('#modalProdQty').val(data.item_qty);
                $('#modalProdPrice').text(`₱${data.prod_price}`);
                $('#modalProdName').text(data.prod_name);
                $('#modalProdImg').attr('src', `../static/upload/${data.prod_img}`);

                // Show modal
                $('#productModal').removeClass('hidden');

                // Store item_id for update
                $('#frmAddToItem').data('editing-id', item_id);

                // Change submit button text
                $('#frmAddToItem button[type="submit"]').text('Update Item');
            } else {
                Swal.fire('Error', res.message || 'Item not found', 'error');
            }
        }
    });
});
