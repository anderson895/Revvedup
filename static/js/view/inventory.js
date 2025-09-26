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
