<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

  <!-- Topbar -->
  <header class="bg-red-900 text-white px-6 py-6 flex items-center space-x-3">
    <h1 class="text-lg font-semibold">SALES REPORT</h1>
  </header>

  <!-- Content -->
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
        
        <!-- Revenue Toggle Button -->
        <button id="revenueBtn" 
          class="bg-gray-300 text-black font-semibold text-sm px-4 py-2 rounded min-w-[220px] whitespace-nowrap hover:bg-gray-400 transition">
          Total Sales & Revenue
        </button>
      </div>

      <div id="salesChart"></div>

      <!-- Sales Info -->
      <div id="salesInfo" class="flex justify-center mt-6 gap-6">
        <div class="bg-gray-100 px-6 py-3 rounded shadow text-center">
          <span id="infoLabel1" class="block text-gray-600 text-sm">Product Sales</span>
          <span id="infoValue1" class="block text-2xl font-bold">500</span>
        </div>
        <div id="infoBox2" class="bg-gray-100 px-6 py-3 rounded shadow text-center hidden">
          <span id="infoLabel2" class="block text-gray-600 text-sm">Revenue</span>
          <span id="infoValue2" class="block text-2xl font-bold">₱ 0.00</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="flex flex-col sm:flex-row gap-3 justify-between items-stretch sm:items-center bg-white border-t px-4 py-3">
  </footer>

  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>

<?php 
include "../src/components/view/footer.php";
?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<script>
  // Default Chart Options (Product Sales only)
  var options = {
    chart: {
      type: 'bar',
      height: 350,
      toolbar: { show: false }
    },
    series: [{
      name: 'Product Sales',
      data: [400, 500, 600, 500, 350, 420, 410]
    }],
    xaxis: {
      categories: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun']
    },
    colors: ['#9ca3af'], // gray
    plotOptions: {
      bar: {
        columnWidth: '50%',
        borderRadius: 5
      }
    },
    dataLabels: { enabled: false },
    grid: { borderColor: '#e5e7eb' }
  };

  var chart = new ApexCharts(document.querySelector("#salesChart"), options);
  chart.render();

  // Weekly Button
  $("#weeklyBtn").on("click", function () {
    if ($("#chartTitle").text().includes("Revenue")) {
      // Revenue Weekly Data
      chart.updateSeries([
        { name: 'Total Sales', data: [500, 600, 700, 650, 550, 600, 580] },
        { name: 'Revenue', data: [400, 500, 600, 580, 500, 550, 530] }
      ]);
      $("#infoValue1").text("₱ 12,000");
      $("#infoValue2").text("₱ 10,000");
    } else {
      // Product Sales Weekly Data
      chart.updateSeries([{ name: 'Product Sales', data: [400, 500, 600, 500, 350, 420, 410] }]);
      $("#infoValue1").text("500");
    }
  });

  // Monthly Button
  $("#monthlyBtn").on("click", function () {
    if ($("#chartTitle").text().includes("Revenue")) {
      // Revenue Monthly Data
      chart.updateSeries([
        { name: 'Total Sales', data: [1500, 1800, 2000, 2200, 1700, 2100, 1900] },
        { name: 'Revenue', data: [1300, 1600, 1800, 2000, 1500, 1800, 1700] }
      ]);
      $("#infoValue1").text("₱ 250,000");
      $("#infoValue2").text("₱ 220,000");
    } else {
      // Product Sales Monthly Data
      chart.updateSeries([{ name: 'Product Sales', data: [1500, 1800, 2000, 2200, 1700, 2100, 1900] }]);
      $("#infoValue1").text("12,000");
    }
  });

  // Revenue Button
  $("#revenueBtn").on("click", function () {
    $("#chartTitle").text("Total Sales & Revenue");

    chart.updateSeries([
      { name: 'Total Sales', data: [500, 600, 700, 650, 550, 600, 580] },
      { name: 'Revenue', data: [400, 500, 600, 580, 500, 550, 530] }
    ]);

    chart.updateOptions({
      colors: ['#991b1b', '#6b7280'] // red & gray
    });

    // Show both info boxes
    $("#infoLabel1").text("Total Sales");
    $("#infoValue1").text("₱ 12,000");

    $("#infoBox2").removeClass("hidden");
    $("#infoLabel2").text("Revenue");
    $("#infoValue2").text("₱ 10,000");
  });
</script>
