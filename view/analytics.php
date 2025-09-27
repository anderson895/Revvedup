<?php 
include "../src/components/view/header.php";
?>

<main class="flex-1 flex flex-col">

 <!-- Header -->
<header class="bg-red-900 text-white px-6 py-6 flex items-center space-x-3">
  <span id="btnBackToSales" class="material-icons cursor-pointer hover:text-gray-200 hidden">
    arrow_back
  </span>
  <h1 id="mainTitle" class="text-lg font-semibold">SALES REPORT</h1>
</header>

<section class="flex-1 p-6">
  <!-- Toggle Buttons -->
  <div class="flex justify-end mb-4 space-x-2">
    <button id="weeklyBtn" class="px-4 py-2 border rounded hover:bg-gray-200">Weekly</button>
    <button id="monthlyBtn" class="px-4 py-2 border border-red-800 text-red-800 rounded hover:bg-red-500 hover:text-white">Monthly</button>
  </div>

  <!-- Chart Card -->
  <div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-2">
      <h2 id="chartTitle" class="text-center text-sm text-red-900 font-semibold w-full">
        Product Sales
      </h2>
      <button id="revenueBtn" 
        class="bg-gray-300 text-black font-semibold text-sm px-4 py-2 rounded min-w-[220px] whitespace-nowrap hover:bg-gray-400 transition">
        Total Sales & Revenue
      </button>
    </div>

    <div id="salesChart"></div>

    <!-- Time buttons (weeks/months) -->
    <div id="timeButtons" class="flex flex-wrap gap-2 mt-4"></div>

    <!-- Sales Info -->
    <div id="salesInfo" class="flex justify-center mt-6 gap-6">
      <div class="bg-gray-100 px-6 py-3 rounded shadow text-center">
        <span id="infoLabel1" class="block text-gray-600 text-sm">Product Sales</span>
        <span id="infoValue1" class="block text-2xl font-bold">₱ 0.00</span>
      </div>
      <div id="infoBox2" class="bg-gray-100 px-6 py-3 rounded shadow text-center hidden">
        <span id="infoLabel2" class="block text-gray-600 text-sm">Revenue</span>
        <span id="infoValue2" class="block text-2xl font-bold">₱ 0.00</span>
      </div>
    </div>
  </div>
</section>

<footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3"></footer>
<br class="block sm:hidden">
<br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
?>

<script>
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
        html += `<button class="timeBtn px-3 py-1 border rounded hover:bg-gray-200" data-label="${d.label}">
                    ${d.label}
                 </button>`;
    });
    $("#timeButtons").html(html);

    $(".timeBtn").off("click").on("click", function(){
        let label = $(this).data("label");
        loadAnalytics(currentScope, currentView, label);
    });
}

// Load analytics from API
function loadAnalytics(scope, view="sales", filterLabel=null){
    currentScope = scope;
    currentView = view;

    $.ajax({
        url: "../controller/end-points/controller.php",
        method: "GET",
        data: { requestType: "fetch_analytics", scope: scope },
        dataType: "json",
        success: function(res){
            if(!res || res.status !== 200 || !res.data) {
                chart.updateSeries([{ name:'Product Sales', data: [] }]);
                chart.updateOptions({ xaxis:{ categories: [] } });
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

            let labels = data.map(d => d.label);
            let sales = data.map(d => d.total_sales || 0);
            let capital = data.map(d => d.capital_total || 0);
            let revenue = data.map(d => d.revenue || 0);

            if(view === "revenue"){
                $("#chartTitle").text("Total Sales, Capital & Revenue");
                $("#btnBackToSales").removeClass("hidden");

                chart.updateSeries([
                    { name: 'Total Sales', data: sales },
                    { name: 'Capital', data: capital },
                    { name: 'Revenue', data: revenue }
                ]);

                chart.updateOptions({ xaxis:{ categories: labels }, colors: ['#9ca3af','#991b1b','#3a3a3aff'] });

                $("#infoLabel1").text("Total Sales");
                $("#infoValue1").text("₱ " + sales.reduce((a,b)=>a+Number(b),0).toLocaleString());
                $("#infoBox2").removeClass("hidden");
                $("#infoLabel2").text("Revenue");
                $("#infoValue2").text("₱ " + revenue.reduce((a,b)=>a+Number(b),0).toLocaleString());

            } else {
                $("#chartTitle").text("Product Sales");
                $("#btnBackToSales").addClass("hidden");

                chart.updateSeries([{ name:'Product Sales', data: sales }]);
                chart.updateOptions({ xaxis:{ categories: labels }, colors:['#9ca3af'] });

                $("#infoLabel1").text("Product Sales");
                $("#infoValue1").text("₱ " + sales.reduce((a,b)=>a+Number(b),0).toLocaleString());
                $("#infoBox2").addClass("hidden");
            }
        },
        error: function(err){ console.error(err); }
    });
}

// Button bindings
$("#weeklyBtn").click(()=> loadAnalytics("weekly", currentView));
$("#monthlyBtn").click(()=> loadAnalytics("monthly", currentView));
$("#revenueBtn").click(()=> loadAnalytics(currentScope, "revenue"));
$("#btnBackToSales").click(()=> loadAnalytics(currentScope, "sales"));

// Init
$(document).ready(()=>{
    initChart();
    loadAnalytics("weekly", "sales");
});
</script>
