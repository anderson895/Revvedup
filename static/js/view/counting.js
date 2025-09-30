const getDataAnalytics = () => {
    $.ajax({
        url: "../controller/end-points/controller.php?requestType=getDataCounting",
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            $('.PendingAppointmentCount').fadeOut();

            if(response.status === 200 && response.data) {
                const { CustomerCount, PendingAppointmentCount, ApprovedAppointmentCount, EmployeeCount, TotalSales } = response.data;

                $('.CustomerCount').text(CustomerCount);
                $('.ApprovedAppointmentCount').text(ApprovedAppointmentCount);
                $('.EmployeeCount').text(EmployeeCount);
                $('.TotalSales').text(TotalSales);

                // Update PendingAppointmentCount badge using show/hide
                if(PendingAppointmentCount > 0){
                    $('.PendingAppointmentCount').text(PendingAppointmentCount).fadeIn();
                } else {
                    $('.PendingAppointmentCount').fadeOut();
                }
            }
        },
        error: function(err) {
            console.error('Failed to fetch analytics', err);
        }
    });
};

// Initial fetch
getDataAnalytics();

// Refresh every 1 second
setInterval(getDataAnalytics, 1000);
