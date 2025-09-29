var currentScope = "weekly";
var currentView = "sales";
var chart;

// Initialize ApexChart
function initChart() {
    var options = {
        chart: { type: 'bar', height: 350, toolbar: { show: false } },
        series: [{ name: 'Product Sales', data: [] }],
        xaxis: { categories: [] },
        colors: ['#9ca3af'],
        plotOptions: { bar: { columnWidth: '50%', borderRadius: 5 } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e5e7eb' }
    };
    chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
}

// Render weekly/monthly buttons dynamically
function renderTimeButtons(data){
    let html = '';
    data.forEach(d => {
        html += `<button class="timeBtn cursor-pointer px-3 py-1 border rounded hover:bg-gray-200" data-label="${d.label}">
                    ${d.label}
                 </button>`;
    });
    $("#timeButtons").html(html);

    $(".timeBtn").off("click").on("click", function(){
        let label = $(this).data("label");
        loadAnalytics(currentScope, currentView, label);
    });
}

function loadAnalytics(scope, view="sales", filterLabel=null){
    currentScope = scope;
    currentView = view;

    $("#loader").css("display", "flex");

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_analytics", scope: scope },
        dataType: "json",
        success: function(res){
            // Fallback for invalid responses
            if(!res || res.status !== 200 || !Array.isArray(res.data) || res.data.length === 0){
                chart.updateSeries([{ name:'Product Sales', data:[0] }]);
                chart.updateOptions({ xaxis:{ categories:['N/A'] }, colors:['#9ca3af'] });
                $("#infoValue1").text("₱ 0.00");
                $("#infoValue2").text("₱ 0.00");
                $("#timeButtons").html('');
                return;
            }

            let data = res.data;

            if(filterLabel){
                data = data.filter(d => d.label === filterLabel);
            } else {
                renderTimeButtons(data);
            }

            // Sanitize data
            let labels = data.map(d => d.label ? String(d.label) : 'N/A');
            let sales = data.map(d => isFinite(Number(d.total_sales)) ? Number(d.total_sales) : 0);
            let capital = data.map(d => isFinite(Number(d.capital_total)) ? Number(d.capital_total) : 0);
            let revenue = data.map(d => isFinite(Number(d.revenue)) ? Number(d.revenue) : 0);

            // Ensure arrays are not empty
            if(labels.length === 0) { labels = ['N/A']; }
            if(sales.length === 0) { sales = [0]; }
            if(capital.length === 0) { capital = [0]; }
            if(revenue.length === 0) { revenue = [0]; }

            if(view === "revenue"){
                $("#chartTitle").text("Total Sales, Capital & Revenue");
                $("#btnBackToSales").removeClass("hidden");

                chart.updateOptions({
                    xaxis:{ categories: labels },
                    colors:['#9ca3af','#991b1b','#3a3a3aff']
                });

                chart.updateSeries([
                    { name:'Total Sales', data:sales },
                    { name:'Capital', data:capital },
                    { name:'Revenue', data:revenue }
                ]);

                $("#infoLabel1").text("Total Sales");
                $("#infoValue1").text("₱ " + sales.reduce((a,b)=>a+b,0).toLocaleString());
                $("#infoBox2").removeClass("hidden");
                $("#infoLabel2").text("Revenue");
                $("#infoValue2").text("₱ " + revenue.reduce((a,b)=>a+b,0).toLocaleString());

            } else {
                $("#chartTitle").text("Product Sales");
                $("#btnBackToSales").addClass("hidden");

                chart.updateOptions({
                    xaxis:{ categories: labels },
                    colors:['#9ca3af']
                });

                chart.updateSeries([{ name:'Product Sales', data:sales }]);

                $("#infoLabel1").text("Product Sales");
                $("#infoValue1").text("₱ " + sales.reduce((a,b)=>a+b,0).toLocaleString());
                $("#infoBox2").addClass("hidden");
            }
        },
        error: function(err){
            console.error(err);
            chart.updateSeries([{ name:'Product Sales', data:[0] }]);
            chart.updateOptions({ xaxis:{ categories:['N/A'] }, colors:['#9ca3af'] });
            $("#infoValue1").text("₱ 0.00");
            $("#infoValue2").text("₱ 0.00");
            $("#timeButtons").html('');
        },
        complete: function(){
            $("#loader").css("display", "none");
        }
    });
}



// Button bindings
$("#weeklyBtn").click(() => {
    loadAnalytics("weekly", currentView);

    // Weekly becomes active
    $("#weeklyBtn").removeClass("bg-gray-200 text-gray-700 border-gray-400")
                   .addClass("bg-red-800 text-white border-red-800");

    // Monthly becomes inactive
    $("#monthlyBtn").removeClass("bg-red-800 text-white border-red-800")
                    .addClass("bg-gray-200 text-gray-700 border-gray-400");
});

$("#monthlyBtn").click(() => {
    loadAnalytics("monthly", currentView);

    // Monthly becomes active
    $("#monthlyBtn").removeClass("bg-gray-200 text-gray-700 border-gray-400")
                    .addClass("bg-red-800 text-white border-red-800");

    // Weekly becomes inactive
    $("#weeklyBtn").removeClass("bg-red-800 text-white border-red-800")
                   .addClass("bg-gray-200 text-gray-700 border-gray-400");
});



$("#revenueBtn").click(()=> loadAnalytics(currentScope, "revenue"));
$("#btnBackToSales").click(()=> loadAnalytics(currentScope, "sales"));

// Init
$(document).ready(()=>{
    initChart();
    loadAnalytics("weekly", "sales");
});




$("#printBtn").click(() => {
    const title = document.querySelector("#chartTitle").innerText;

    // Kunin ang data para sa table
    let timeButtons = document.querySelectorAll("#timeButtons .timeBtn");
    let info1 = document.querySelector("#infoValue1").innerText;
    let info2Visible = !document.querySelector("#infoBox2").classList.contains("hidden");
    let info2 = document.querySelector("#infoValue2").innerText;

    // Build HTML table
    let tableHTML = `
        <table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse: collapse; text-align:center;">
            <thead>
                <tr style="background:#991b1b; color:white;">
                    <th>${currentScope === 'weekly' ? 'Week' : 'Month'}</th>
                    <th>Sales</th>
                    ${info2Visible ? '<th>Revenue</th>' : ''}
                </tr>
            </thead>
            <tbody>
    `;

    timeButtons.forEach(btn => {
        let label = btn.dataset.label;
        // Kunin sales value mula sa chart data (kung available)
        let salesValue = info1; // pwede palitan kung per week/month value available
        let revenueValue = info2Visible ? info2 : '';
        tableHTML += `<tr>
            <td>${label}</td>
            <td>${salesValue}</td>
            ${info2Visible ? `<td>${revenueValue}</td>` : ''}
        </tr>`;
    });

    tableHTML += `</tbody></table>`;

    // Print
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
        <head>
            <title>${title}</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; }
                h1 { text-align: center; color: #991b1b; margin-bottom: 20px; }
                table th, table td { padding: 10px; }
            </style>
        </head>
        <body>
            <h1>${title}</h1>
            ${tableHTML}
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
});


