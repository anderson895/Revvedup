$('#BtnVoidCart').click(function (e) { 
    e.preventDefault();

    // Show warning first
    Swal.fire({
        title: 'Are you sure?',
        text: "This will remove all items from the cart!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, void it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceed with AJAX if confirmed
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
        }
    });
});
