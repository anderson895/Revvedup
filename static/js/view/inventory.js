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

    // Validate Image Upload
    if (!itemImage) {
        alertify.error("Please upload an item image.");
        return;
    } else {
        var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if (!allowedExtensions.exec(itemImage)) {
            alertify.error("Invalid file type. Only JPG, JPEG, PNG, or GIF allowed.");
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



















        

   $.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: { requestType: "fetch_all_product" },
    dataType: "json",
    success: function (res) {
        if (res.status === 200) {
            let count = 1;

            // Clear previous content (optional)
            $('#productTableBody').empty();

            // Check if there is data
            if (res.data.length > 0) {
                res.data.forEach(data => {


                    $('#productTableBody').append(`
                        <tr>
                            <td class="px-4 py-2">${data.prod_id}</td>
                            <td class="px-4 py-2 flex items-center space-x-2">
                                <img src="../static/upload/${data.prod_img || '../static/images/default.png'}" 
                                    alt="${data.prod_img}" 
                                    class="w-8 h-8 object-cover rounded" />
                                <span>${data.prod_name}</span>
                            </td>
                            <td class="px-4 py-2">₱ ${data.prod_price}</td>
                            <td class="px-4 py-2">${data.prod_qty}</td>
                            <td class="px-4 py-2">Fast Moving <br>(Pending functionalities)</td>
                            <td class="px-4 py-2">
                                <span class="inline-block w-3 h-3 rounded-full
                                    ${data.prod_qty > 10 
                                        ? 'bg-green-600' 
                                        : (data.prod_qty > 0 
                                            ? 'bg-yellow-500' 
                                            : 'bg-red-600')
                                    }">
                                </span>
                            </td>
                            <td class="px-4 py-2 flex justify-center space-x-2">
                                <button class="updateBtn text-gray-700 hover:text-blue-600"
                                data-prod_id ='${data.prod_id}'
                                data-prod_name='${data.prod_name}'
                                data-prod_price='${data.prod_price}'
                                data-prod_qty='${data.prod_qty}'
                                >
                                    <span class="material-icons text-sm">edit</span>
                                </button>
                                <button class="text-gray-700 hover:text-red-600">
                                    <span class="material-icons text-sm">delete</span>
                                </button>
                            </td>
                        </tr>
                    `);


                });
            } else {
                $('#productTableBody').append(`
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



  // Search filter
  $('#searchInput').on('input', function () {
    const term = $(this).val().toLowerCase();
    $('#productTableBody tr').each(function () {
      $(this).toggle($(this).text().toLowerCase().includes(term));
    });
  });


















  

$(document).on("click", ".updateBtn", function () {

  const prod_id = $(this).data("prod_id");
  const prod_name = $(this).data("prod_name");
  const prod_price = $(this).data("prod_price");
  const prod_qty = $(this).data("prod_qty");


  $("#productId").val(prod_id);
  $("#itemNameUpdate").val(prod_name);
  $("#priceUpdate").val(prod_price);
  $("#stockQtyUpdate").val(prod_qty);


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
        Swal.fire('Success!', response.message || 'Event info updated.', 'success').then(() => {
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
