$(".btnRefundExchange").click(function (e) { 
    e.preventDefault();

    $("#RefundExchangeModal").fadeIn();
    
});


// Close button
$("#closeAddProductModal").click(function (e) { 
    e.preventDefault();
    $("#addProductModal").fadeOut();
});

// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#RefundExchangeModal")) {
        $("#RefundExchangeModal").fadeOut();
    }
});





