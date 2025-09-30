$(document).ready(function () {
   // Fetch appointments
function fetchAppointments() {
    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_appointment" },
        dataType: "json",
        success: function (res) {
            const tbody = $('#appointmentTableBody');
            tbody.empty();

            if (res.status === 200 && res.data.length > 0) {
                const appointmentIds = []; // store IDs

                res.data.forEach(data => {
                    appointmentIds.push(data.appointment_id);

                    const statusLower = data.status.toLowerCase();
                    let statusColor = statusLower === "pending" ? 'bg-yellow-500' :
                                      statusLower === "completed" ? 'bg-green-600' :
                                      statusLower === "request canceled" ? 'bg-orange-500' :
                                      statusLower === "canceled" || statusLower === "approved" ? 'bg-red-600' : 'bg-gray-500';

                    const buttonsDisabled = !(statusLower === "pending" || statusLower === "request canceled") 
                                            ? "disabled cursor-not-allowed opacity-50" : "";

                    const seeBtn = `<button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                        data-id='${data.appointment_id}'>
                                        <span class="material-icons align-middle text-sm">visibility</span> See
                                    </button>`;

                    const approveBtn = `<button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition ${buttonsDisabled}"
                                            data-appointment_id='${data.appointment_id}' ${buttonsDisabled}>
                                            <span class="material-icons align-middle text-sm">check_circle</span> Approve
                                        </button>`;

                    const cancelBtn = `<button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition ${buttonsDisabled}"
                                            data-appointment_id='${data.appointment_id}' ${buttonsDisabled}>
                                            <span class="material-icons align-middle text-sm">cancel</span> Cancel
                                        </button>`;

                    tbody.append(`
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-gray-700 font-medium">${data.reference_number}</td>
                            <td class="px-6 py-4 text-gray-600">${data.appointmentDate} ${data.appointmentTime}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold text-white ${statusColor} rounded-full">
                                    ${data.status}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex justify-center gap-2">
                                ${seeBtn}
                                ${approveBtn}
                                ${cancelBtn}
                            </td>
                        </tr>
                    `);
                });

                // Mark all fetched appointments as seen
                markAsSeen(appointmentIds);
            } else {
                tbody.append('<tr><td colspan="4" class="p-4 text-center text-gray-400 italic">No record found</td></tr>');
            }
        }
    });
}

// Mark appointments as seen
function markAsSeen(ids) {
    if (ids.length === 0) return;

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "POST",
        data: { 
            requestType: "mark_seen",
            appointmentIds: ids 
        },
        success: function (res) {
            console.log("Appointments marked as seen:", res);
        }
    });
}

// Initial fetch
fetchAppointments();

// Search filter
$('#searchInput').on('input', function () {
    const term = $(this).val().toLowerCase();
    $('#appointmentTableBody tr').each(function () {
        $(this).toggle($(this).text().toLowerCase().includes(term));
    });
});












    
    // Show modal on See Details click
    $(document).on('click', '.seeDetailsBtn', function () {

        
        const btn = $(this);
        const content = `
            <p><strong>Reference:</strong> ${btn.data('reference')}</p>
            <p><strong>Customer:</strong> ${btn.data('fullname')}</p>
            <p><strong>Contact:</strong> ${btn.data('contact')}</p>
            <p><strong>Service:</strong> ${btn.data('service')}</p>
            <p><strong>Date & Time:</strong> ${btn.data('date')} ${btn.data('time')}</p>
            <p><strong>Emergency:</strong> ${btn.data('emergency') == "1" ? "Yes" : "No"}</p>
            <p><strong>Status:</strong> ${btn.data('status')}</p>
        `;
        $('#modalContent').html(content);
        $('#detailsModal').removeClass('opacity-0 pointer-events-none');
    });

    // Close modal
    $('#closeModal, #detailsModal').click(function (e) {
        if (e.target.id === 'detailsModal' || e.target.id === 'closeModal') {
            $('#detailsModal').addClass('opacity-0 pointer-events-none');
        }
    });








    // Cancel appointment
   $(document).on('click', '.cancelBtn', function () {
    const appointmentId = $(this).data('appointment_id');

    Swal.fire({
        title: 'Cancel Appointment',
        text: "Are you sure you want to cancel this appointment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed, send AJAX request
            $.ajax({
                url: "../controller/end-points/controller.php",
                method: "POST",
                data: { requestType: "cancel_appointment", appointment_id: appointmentId },
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") { // <-- match your PHP response
                        Swal.fire({
                            icon: 'success',
                            title: 'Canceled!',
                            text: res.message,
                            timer: 1000,
                            showConfirmButton: false
                        });

                        // Delay reload until after Swal timer
                        setTimeout(() => {
                            location.reload();
                        }, 1000);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: res.message || "Failed to cancel appointment. Please try again."
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "An error occurred. Please try again."
                    });
                }
            });
        }
    });
});









// Approve appointment
$(document).on('click', '.approveBtn', function () {
    const appointmentId = $(this).data('appointment_id');

    Swal.fire({
        title: 'Approve Appointment',
        text: "Are you sure you want to approve this appointment?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, approve it!',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "../controller/end-points/controller.php",
                method: "POST",
                data: { requestType: "approve_appointment", appointment_id: appointmentId },
                dataType: "json",
                success: function (res) {
                    if (res.status === "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approved!',
                            text: res.message,
                            timer: 1000,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            fetchAppointments();
                        }, 1000);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: res.message || "Failed to approve appointment."
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "An error occurred. Please try again."
                    });
                }
            });
        }
    });
});


});