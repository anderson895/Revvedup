

$("#addServiceBtn").click(function (e) { 
    e.preventDefault();

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

                // Check if there is data
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








$("#frmAddService").submit(function (e) { 
    e.preventDefault();

    var serviceName = $('#serviceName').val().trim();
    var price = $('#price').val().trim();
    var employee = $('#employee').val().trim();

    // Validate Service Name
    if (!serviceName) {
        alertify.error("Please enter service name.");
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

    // Validate Employee
    if (!employee) {
        alertify.error("Please select an employee.");
        return;
    }

    // Show spinner and disable button
    $('.spinner').show();
    $('#frmAddService button[type="submit"]').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'AddServiceCart');

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            $('.spinner').hide();
            $('#frmAddService button[type="submit"]').prop('disabled', false);

            if (response.status === 200) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    window.location.href = 'service';
                });
            } else {
                Swal.fire('Error', response.message || 'Something went wrong.', 'error');
            }
        },
        error: function() {
            $('.spinner').hide();
            $('#frmAddService button[type="submit"]').prop('disabled', false);
            Swal.fire('Error', 'Server error. Please try again later.', 'error');
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
                        <tr class="hover:bg-[#2B2B2B] transition-colors">
                            <td class="p-3 text-center font-mono">${data.service_name}</td>
                            <td class="p-3 text-center font-semibold">${data.service_price}</td>
                            <td class="p-3 text-center font-semibold">${data.emp_fname} ${data.emp_lname}</td>
                            <td class="p-3 text-center">
                                <button class="viewDetailsBtn bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-xs font-semibold transition"
                                data-service_id='${data.service_id}'>Update</button>
                                <button class="removeBtn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold transition"
                                data-service_id='${data.service_id}'>Remove</button>
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












