




















$('.serviceNameInput').on('keypress', function(e) {
    if (e.which === 13) { // 13 is the Enter key
        e.preventDefault(); 
        $('#addServiceBtn').click(); 
    }
});

$("#addServiceBtn").click(function (e) { 
    e.preventDefault();


    $('.titleAction').text('Add Service');

    const serviceNameInput = $('.serviceNameInput').val();
    $("#serviceName").val(serviceNameInput);

    // FETCH All employee
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_all_employee" },
        dataType: "json",
        success: function (res) {
            if (res.status === 200) {
                $('#employee').empty();

                $('#employee').append(`<option value="" disabled selected>Select Employee</option>`);

                if (res.data.length > 0) {
                    res.data.forEach(emp => {
                        $('#employee').append(`
                            <option value="${emp.emp_id}">
                                ${emp.emp_fname} ${emp.emp_lname}
                            </option>
                        `);
                    });
                } else {
                    $('#employee').append(`<option disabled>No employees found</option>`);
                }
            }
        }
    });

    $("#addServiceModal").fadeIn();
});




// Close button
$("#closeAddProductModal").click(function (e) { 
    e.preventDefault();
    $("#addServiceModal").fadeOut();
});

// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#addServiceModal")) {
        $("#addServiceModal").fadeOut();
    }
});







$('#frmAddService').submit(function(e) {
    e.preventDefault();

    var service_id = $(this).data('editing-id');
    var serviceName = $('#serviceName').val().trim();
    var price = $('#price').val().trim();
    var employee = $('#employee').val().trim();

    if(!serviceName || !price || isNaN(price) || price <= 0 || !employee) {
        alertify.error("Please fill all fields correctly.");
        return;
    }

    var formData = new FormData(this);
    formData.append('requestType', service_id ? 'updateServiceCart' : 'AddServiceCart');
    if(service_id) formData.append('service_id', service_id);

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            if(response.status === 200){
                Swal.fire('Success!', response.message, 'success').then(() => {
                    window.location.href = 'service';
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
    data: { requestType: "fetch_all_service_cart" },
    dataType: "json",
    success: function (res) {
        if (res.status === 200) {

            let html = '';

            if (res.data.length > 0) {
                res.data.forEach((data) => {


                    html += `
                        <tr class="hover:bg-gray-200 transition-colors">
                            <td class="p-3 text-center font-mono">${data.service_name}</td>
                            <td class="p-3 text-center font-semibold">${data.service_price}</td>
                            <td class="p-3 text-center font-semibold">${data.emp_fname} ${data.emp_lname}</td>
                            <td class="p-3 text-center">
                                <button class="EditBtn bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold transition"
                                data-service_id='${data.service_id}'>Edit</button>
                                <button class="removeBtn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition"
                                data-service_id='${data.service_id}'
                                data-service_name='${data.service_name}'
                                >Remove</button>
                            </td>
                        </tr>
                    `;
                });

                $('#serviceTableBody').html(html);

            } else {
                $('#serviceTableBody').html(`
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
        const id = $(this).data("service_id");
        const service_name = $(this).data("service_name");
        
        Swal.fire({
            title: `Remove <span style="color:red;">${service_name}</span> from cart?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, remove it!',
            cancelButtonText: 'No, cancel!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    type: 'POST',
                    data: { id: id, requestType: 'deleteCart',table:'service_cart',collumn:'service_id' },
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

    $('.titleAction').text('Edit Service');

    const service_id = $(this).data('service_id');
   
    

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "getServiceById", service_id: service_id },
        dataType: "json",
        success: function(res) {
            if(res.status === 200) {
                const data = res.data;

                // Fill modal inputs
                $('#serviceName').val(data.service_name);
                $('#price').val(data.service_price);
                
                // Populate employees
                $.ajax({
                    url: "../controller/end-points/controller.php",
                    method: "GET",
                    data: { requestType: "fetch_all_employee" },
                    dataType: "json",
                    success: function(empRes) {
                        if(empRes.status === 200) {
                            $('#employee').empty();
                            $('#employee').append(`<option value="" disabled>Select Employee</option>`);
                            empRes.data.forEach(emp => {
                                $('#employee').append(`
                                    <option value="${emp.emp_id}" ${emp.emp_id == data.service_employee_id ? 'selected' : ''}>
                                        ${emp.emp_fname} ${emp.emp_lname}
                                    </option>
                                `);
                            });

                            // Show modal
                            $('#addServiceModal').fadeIn();

                            // Change form for editing
                            $('#frmAddService').data('editing-id', service_id);
                            $('#frmAddService button[type="submit"]').text('Update Service');
                        }
                    }
                });

            } else {
                Swal.fire('Error', res.message || 'Service not found', 'error');
            }
        }
    });
});






