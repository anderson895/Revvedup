let salesChart = null;
let appointmentChart = null;
let employeeChart = null;
let productChart = null;

const getDashboardAnalytics = () => {
    $.ajax({
        url: "../controller/end-points/controller.php?requestType=getDashboardAnalytics",
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.status !== 200 || !response.data) return;

            const { 
                CustomerCount, 
                EmployeeCount, 
                PendingAppointmentCount, 
                ApprovedAppointmentCount, 
                CanceledAppointmentCount, 
                TotalSales, 
                SalesLast7Days,
                EmployeeServices,
                PopularItems
            } = response.data;

            // -----------------------
            // Update cards
            // -----------------------
            $('.CustomerCount').text(CustomerCount || 0);
            $('.EmployeeCount').text(EmployeeCount || 0);
            $('.PendingAppointmentCountDashboard').text(PendingAppointmentCount || 0);
            $('.TotalSales').text(`₱${parseFloat(TotalSales || 0).toFixed(2)}`);

            // -----------------------
            // Sales Line Chart
            // -----------------------
            const salesSeries = (SalesLast7Days && SalesLast7Days.length > 0)
                ? SalesLast7Days.map(d => parseFloat(d.total))
                : [0];
            const salesCategories = (SalesLast7Days && SalesLast7Days.length > 0)
                ? SalesLast7Days.map(d => d.date)
                : ['No Data'];

            if(!salesChart) {
                const salesOptions = {
                    chart: { type: 'line', height: 300 },
                    series: [{ name: 'Sales', data: salesSeries }],
                    xaxis: { categories: salesCategories },
                    stroke: { curve: 'smooth' },
                    dataLabels: { enabled: false },
                    noData: { text: 'No Sales Data' }
                };
                salesChart = new ApexCharts(document.querySelector("#salesChart"), salesOptions);
                salesChart.render();
            } else {
                salesChart.updateOptions({ xaxis: { categories: salesCategories } });
                salesChart.updateSeries([{ data: salesSeries }]);
            }

            // -----------------------
            // Appointments Pie Chart
            // -----------------------
            const appointmentSeries = [
                parseInt(PendingAppointmentCount) || 0,
                parseInt(ApprovedAppointmentCount) || 0,
                parseInt(CanceledAppointmentCount) || 0
            ];

            if(!appointmentChart) {
                const appointmentOptions = {
                    chart: { type: 'pie', height: 300 },
                    labels: ['Pending', 'Approved', 'Canceled'],
                    series: appointmentSeries,
                    noData: { text: 'No Appointments' }
                };
                appointmentChart = new ApexCharts(document.querySelector("#appointmentChart"), appointmentOptions);
                appointmentChart.render();
            } else {
                appointmentChart.updateSeries(appointmentSeries);
            }

            // -----------------------
            // Employee Services Pie Chart
            // -----------------------
            const employeeLabels = EmployeeServices.map(e => e.employee_name);
            const employeeSeries = EmployeeServices.map(e => e.service_count);

            if(!employeeChart) {
                const employeeOptions = {
                    chart: { type: 'pie', height: 300 },
                    labels: employeeLabels,
                    series: employeeSeries,
                    title: { text: 'Services Rendered per Employee', align: 'center' }
                };
                employeeChart = new ApexCharts(document.querySelector("#employeeChart"), employeeOptions);
                employeeChart.render();
            } else {
                employeeChart.updateOptions({ labels: employeeLabels });
                employeeChart.updateSeries(employeeSeries);
            }

            // -----------------------
            // Popular Items Pie Chart
            // -----------------------
            const productLabels = PopularItems.map(p => p.name);
            const productSeries = PopularItems.map(p => p.total_sold);

            if(!productChart) {
                const productOptions = {
                    chart: { type: 'pie', height: 300 },
                    labels: productLabels,
                    series: productSeries,
                    title: { text: 'Most Sold Products', align: 'center' }
                };
                productChart = new ApexCharts(document.querySelector("#productChart"), productOptions);
                productChart.render();
            } else {
                productChart.updateOptions({ labels: productLabels });
                productChart.updateSeries(productSeries);
            }
        },
        error: function(err) {
            console.error('Failed to fetch analytics', err);
        }
    });
};

// Initial fetch
getDashboardAnalytics();

// Refresh every 5 seconds
setInterval(getDashboardAnalytics, 5000);
