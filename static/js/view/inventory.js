// Open modal
$("#addProductBtn").click(function (e) { 
    e.preventDefault();
    $("#addProductModal").fadeIn();
});

// Close button
$("#closeAddProductModal").click(function (e) { 
    e.preventDefault();
    $("#addProductModal").fadeOut();
});

// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#addProductModal")) {
        $("#addProductModal").fadeOut();
    }
});


$("#frmAddProduct").submit(function (e) { 
    e.preventDefault();

    var itemName = $('#itemName').val().trim();
    var price = $('#price').val().trim();
    var stockQty = $('#stockQty').val().trim();
    var category = $('#category').val();  // ✅ Category
    var itemImage = $('#itemImage').val();

    // Validate Item Name
    if (!itemName) {
        alertify.error("Please enter item name.");
        return;
    }

    // Validate Price
    if (!price) {
        alertify.error("Please enter a price.");
        return;
    }
    if (isNaN(price)) {
        alertify.error("Price must be a valid number.");
        return;
    }
    var priceValue = parseFloat(price);
    if (priceValue <= 0) {
        alertify.error("Price must be greater than zero.");
        return;
    }

    // Validate Stock Quantity
    if (!stockQty) {
        alertify.error("Please enter stock quantity.");
        return;
    }
    if (isNaN(stockQty)) {
        alertify.error("Quantity must be a valid number.");
        return;
    }
    var stockValue = parseInt(stockQty);
    if (stockValue <= 0) {
        alertify.error("Stock quantity must be greater than zero.");
        return;
    }

    // ✅ Validate Category
    if (!category) {
        alertify.error("Please select a category.");
        return;
    }

    // Validate Image Upload
    if (!itemImage) {
        alertify.error("Please upload an item image.");
        return;
    } else {
       var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
        if (!allowedExtensions.exec(itemImage)) {
            alertify.error("Invalid file type. Only JPG, JPEG, PNG, GIF, or WEBP allowed.");
            return;
        }

    }

    // Show spinner and disable button
    $('.spinner').show();
    $('#frmAddProduct button[type="submit"]').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'AddProduct');

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            $('.spinner').hide();
            $('#frmAddProduct button[type="submit"]').prop('disabled', false);

            if (response.status === 200) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    window.location.href = 'inventory';
                });
            } else {
                Swal.fire('Error', response.message || 'Something went wrong.', 'error');
            }
        }
    });

});












 // Tabs
    $('#activeTab').on('click', function () {
        $(this).addClass('bg-gray-200');
        $('#archiveTab').removeClass('bg-gray-200');
        fetchProducts('fetch_all_product'); // Active products
    });

    $('#archiveTab').on('click', function () {
        $(this).addClass('bg-gray-200');
        $('#activeTab').removeClass('bg-gray-200');
        fetchProducts('fetch_archived_product'); // Archived products
    });

    // Filters & Search
    const salesSpeedFilter = $('#salesSpeedFilter');
    const categoryFilter = $('#categoryFilter');
    const statusFilter = $('#statusFilter');
    const searchInput = $('#searchInput');

    function applyFilters() {
        const speed = salesSpeedFilter.val();
        const category = categoryFilter.val();
        const status = statusFilter.val();
        const term = searchInput.val().toLowerCase();

        $('#productTableBody tr').each(function () {
            const row = $(this);
            const rowSpeed = row.find('td:eq(5)').text().trim().split(' ')[0] + ' ' + row.find('td:eq(5)').text().trim().split(' ')[1]; // e.g., "Fast moving"
            const rowCategory = row.find('td:eq(6)').text().trim();
            const rowStatusColor = row.find('td:eq(7) span').attr('class');
            let rowStatus = '';
            if (rowStatusColor.includes('green')) rowStatus = 'in-stock';
            else if (rowStatusColor.includes('yellow')) rowStatus = 'low-stock';
            else if (rowStatusColor.includes('red')) rowStatus = 'out-of-stock';

            const matchSpeed = !speed || rowSpeed === speed;
            const matchCategory = !category || rowCategory === category;
            const matchStatus = !status || rowStatus === status;
            const matchSearch = !term || row.text().toLowerCase().includes(term);

            row.toggle(matchSpeed && matchCategory && matchStatus && matchSearch);
        });
    }

    salesSpeedFilter.on('change', applyFilters);
    categoryFilter.on('change', applyFilters);
    statusFilter.on('change', applyFilters);
    searchInput.on('input', applyFilters);

    // Fetch products
    function fetchProducts(requestType) {
        $.ajax({
            url: "../controller/end-points/controller.php",
            method: "GET",
            data: { requestType: requestType },
            dataType: "json",
            success: function (res) {
                $('#productTableBody').empty();

                if (res.status === 200) {
                    let isAdmin = (res.position === "admin");

                    if (res.data.length > 0) {
                        res.data.forEach(data => {
                            let stockColor = '';
                            if (data.prod_qty > 10) stockColor = 'bg-green-600';
                            else if (data.prod_qty > 0) stockColor = 'bg-yellow-500';
                            else stockColor = 'bg-red-600';

                            let actionButtons = '';
                            if (isAdmin) {
                                if (requestType === 'fetch_all_product') {
                                    actionButtons = `
                                        <button class="updateBtn cursor-pointer text-gray-700 hover:text-blue-600"
                                            data-prod_id ='${data.prod_id}'
                                            data-prod_name='${data.prod_name}'
                                            data-prod_capital='${data.prod_capital}'
                                            data-prod_price='${data.prod_price}'
                                            data-prod_qty='${data.prod_qty}'
                                            data-prod_category='${data.prod_category}'
                                            data-prod_description='${data.prod_description}'>
                                            <span class="material-icons text-sm">edit</span>
                                        </button>
                                        <button class="removeBtn cursor-pointer text-gray-700 hover:text-blue-600"
                                            data-prod_id='${data.prod_id}'
                                            data-prod_name='${data.prod_name}'>
                                            <span class="material-icons text-sm">archive</span>
                                        </button>
                                    `;
                                } else if (requestType === 'fetch_archived_product') {
                                    actionButtons = `
                                        <button class="restoreBtn cursor-pointer text-gray-700 hover:text-green-600"
                                            data-prod_id='${data.prod_id}'
                                            data-prod_name='${data.prod_name}'>
                                            <span class="material-icons text-sm">restore</span>
                                        </button>
                                    `;
                                }
                            }

                            $('#productTableBody').append(`
                                <tr>
                                    <td class="px-4 py-2">${data.prod_id}</td>
                                    <td class="px-4 py-2 flex items-center space-x-2">
                                        <img src="../static/upload/${data.prod_img || '../static/images/default.png'}" 
                                            alt="${data.prod_img}" 
                                            class="w-8 h-8 object-cover rounded" />
                                        <span>${data.prod_name}</span>
                                    </td>
                                    <td class="px-4 py-2">₱ ${data.prod_capital}</td>
                                    <td class="px-4 py-2">₱ ${data.prod_price}</td>
                                    <td class="px-4 py-2">${data.prod_qty}</td>
                                    <td class="px-4 py-2 font-semibold">${data.movement} (${data.total_sold_week} pcs per week)</td>
                                    <td class="px-4 py-2 font-semibold">${data.prod_category}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block w-3 h-3 rounded-full ${stockColor}"></span>
                                    </td>
                                    <td class="px-4 py-2 flex justify-center space-x-2">
                                        ${actionButtons}
                                    </td>
                                </tr>
                            `);
                        });
                        applyFilters(); // Apply filters after table is rendered
                    } else {
                        $('#productTableBody').append(`
                            <tr>
                                <td colspan="9" class="p-4 text-center text-gray-400 italic">No record found</td>
                            </tr>
                        `);
                    }
                }
            }
        });
    }

    // Initialize active products on page load
    fetchProducts('fetch_all_product');



$(document).on('click', '.restoreBtn', function(e) {
    e.preventDefault();
    const prod_id = $(this).data("prod_id");
    const prod_name = $(this).data("prod_name");

    Swal.fire({
        title: `Restore <span style="color:green;">${prod_name}</span>?`,
        text: 'This product will be moved back to active inventory.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, restore it!',
        cancelButtonText: 'Cancel',
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../controller/end-points/controller.php",
                type: 'POST',
                data: { prod_id: prod_id, requestType: 'restoreProduct' },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 200) {
                        Swal.fire('Restored!', response.message, 'success')
                        .then(() => {
                            fetchProducts('fetch_archived_product'); // Refresh archived list
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'There was a problem with the request.', 'error');
                }
            });
        }
    });
});









$(document).on('click', '.removeBtn', function(e) {
        e.preventDefault();
        const prod_id = $(this).data("prod_id");
        const prod_name = $(this).data("prod_name");
        
        console.log(prod_id);
    
        Swal.fire({
            title: `Are you sure to Archive <span style="color:red;">${prod_name}</span> ?`,
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, archive it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    type: 'POST',
                    data: { prod_id: prod_id, requestType: 'removeProduct' },
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














  
$(document).on("click", ".updateBtn", function () {
  const prod_id = $(this).data("prod_id");
  const prod_name = $(this).data("prod_name");
  const prod_capital = $(this).data("prod_capital");
  const prod_price = $(this).data("prod_price");
  const prod_qty = $(this).data("prod_qty");
  const prod_category = $(this).data("prod_category"); 
  const prod_description = $(this).data("prod_description"); 

  // Populate modal fields
  $("#productId").val(prod_id);
  $("#itemNameUpdate").val(prod_name);
  $("#capitalUpdate").val(prod_capital);
  $("#priceUpdate").val(prod_price);
  $("#stockQtyUpdate").val(prod_qty);

  $("#categoryUpdate").val(prod_category);
  $("#descriptionUpdate").val(prod_description);

  // Show modal
  $('#updateProductModal').fadeIn();
});


// Close modal
$(document).on("click", "#closeUpdateProductModal", function () {
  $('#updateProductModal').fadeOut();
});


// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#updateProductModal")) {
        $("#updateProductModal").fadeOut();
    }
});








$(document).on("submit", "#frmUpdateProduct", function (e) {
  e.preventDefault();

  var itemName = $('#itemNameUpdate').val().trim();
  var price = $('#priceUpdate').val().trim();
  var stockQty = $('#stockQtyUpdate').val().trim();
  var category = $('#categoryUpdate').val(); // ✅ Category
  var itemImage = $('#itemImageUpdate').val();

  // Validate Item Name
  if (!itemName) {
    alertify.error("Please enter item name.");
    return;
  }

  // Validate Price
  if (!price) {
    alertify.error("Please enter a price.");
    return;
  }
  if (isNaN(price)) {
    alertify.error("Price must be a valid number.");
    return;
  }
  var priceValue = parseFloat(price);
  if (priceValue <= 0) {
    alertify.error("Price must be greater than zero.");
    return;
  }

  // Validate Stock Quantity
  if (!stockQty) {
    alertify.error("Please enter stock quantity.");
    return;
  }
  if (isNaN(stockQty)) {
    alertify.error("Quantity must be a valid number.");
    return;
  }


  // ✅ Validate Category
  if (!category) {
    alertify.error("Please select a category.");
    return;
  }

  // Validate Image Upload (optional sa Update, pero kung may laman dapat valid type)
  if (itemImage) {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
        if (!allowedExtensions.exec(itemImage)) {
            alertify.error("Invalid file type. Only JPG, JPEG, PNG, GIF, or WEBP allowed.");
            return;
        }
    }


  const formData = new FormData(this);
  formData.append("requestType", "UpdateProduct");

  $.ajax({
    url: "../controller/end-points/controller.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      if (response.status === 200) {
        Swal.fire('Success!', response.message || 'Product updated.', 'success').then(() => {
          location.reload();
        });
      } else {
        alertify.error(response.message || "Error updating.");
      }
    },
    error: function (xhr, status, error) {
      console.error("Update error:", error);
      alertify.error("Failed to update. Please try again.");
    }
  });
});











