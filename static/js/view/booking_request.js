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
                    res.data.forEach(data => {
                    let statusColor = '';
                    if (data.status === "pending") statusColor = 'bg-yellow-500';
                    else if (data.status === "completed") statusColor = 'bg-green-600';
                    else statusColor = 'bg-red-600';

                    // Disable cancel button if status is not pending
                    const disabled = data.status !== "pending" ? "disabled cursor-not-allowed opacity-50" : "";

                    $('#appointmentTableBody').append(`
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-2 font-medium text-gray-700">${data.reference_number}</td>
                            <td class="px-4 py-2 text-gray-600">${data.appointmentDate} ${data.appointmentTime}</td>
                            <td class="px-4 py-2">
                                <span class="capitalize px-2 py-1 rounded-full text-white text-xs ${statusColor}">
                                    ${data.status}
                                </span>
                            </td>
                            <td class="px-4 py-2 flex justify-center gap-2">
                                <button class="seeDetailsBtn cursor-pointer bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition"
                                    data-id='${data.appointment_id}'
                                    data-reference='${data.reference_number}'
                                    data-fullname='${data.fullname}'
                                    data-contact='${data.contact}'
                                    data-service='${data.service}'
                                    data-date='${data.appointmentDate}'
                                    data-time='${data.appointmentTime}'
                                    data-emergency='${data.emergency}'
                                    data-status='${data.status}'>
                                    <span class="material-icons text-sm align-middle">visibility</span> See Details
                                </button>
                                <button class="cancelBtn cursor-pointer bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition ${disabled}"
                                    data-appointment_id='${data.appointment_id}' ${data.status !== "pending" ? "disabled" : ""}>
                                    <span class="material-icons text-sm align-middle">cancel</span> Cancel
                                </button>
                            </td>
                        </tr>
                    `);
                });

                } else {
                    tbody.append(`
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-400 italic">
                                No record found
                            </td>
                        </tr>
                    `);
                }
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




});