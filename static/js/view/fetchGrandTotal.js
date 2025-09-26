
// Fetch total cart (services + items) for the current user
function fetchGrandTotal() {
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_total_cart" },
        dataType: "json",
        success: function(res) {
            if (res.status === 200) {
                let total = parseFloat(res.data) || 0;
                $('.totalPrice').text(total.toFixed(2));
            } else {
                $('.totalPrice').text('0.00');
            }
        },
        error: function() {
            $('.totalPrice').text('0.00');
        }
    });
}


fetchGrandTotal();
