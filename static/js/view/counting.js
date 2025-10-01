const getDataAnalytics = () => {
    $.ajax({
        url: "../controller/end-points/controller.php?requestType=getDataCounting",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.status === 200 && response.data) {
                const { PendingAppointmentCount } = response.data;
                const $badge = $('.PendingAppointmentCount');

                if(PendingAppointmentCount > 0){
                    $badge.text(PendingAppointmentCount).fadeIn();
                } else {
                    $badge.fadeOut();
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
// setInterval(getDataAnalytics, 1000);
