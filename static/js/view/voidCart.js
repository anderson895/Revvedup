$('#BtnVoidCart').click(function (e) { 
    e.preventDefault();

    
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "POST",
        data: {requestType:"VoidCart"},
        dataType: "json",
        success: function (response) {
            if (response.status === 200) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "Void Successfully!",
                    confirmButtonText: "OK"
                }).then(() => {

                        window.location.href = 'item';
                  
                });
            }
        }
    });
});