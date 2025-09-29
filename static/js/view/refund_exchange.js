
const transactionId =$('#transactionId').val();
$.ajax({
    url: "../controller/end-points/controller.php",
    method: "GET",
    data: {transactionId:transactionId, requestType: "fetch_transaction_record" },
    dataType: "json",
    success: function (res) {
        
    }
});