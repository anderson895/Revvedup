<?php 
include "../src/components/view/header.php";
?>
<!-- Main Content -->
<main class="flex-1 p-6 bg-gray-100 min-h-screen">
  <!-- Stats Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Customers -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-500 text-sm">Total Customers</div>
      <div class="text-2xl font-bold CustomerCount">0</div>
    </div>
    <!-- Employees -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-500 text-sm">Total Employees</div>
      <div class="text-2xl font-bold EmployeeCount">0</div>
    </div>
    <!-- Pending Appointments -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-500 text-sm">Pending Appointments</div>
      <div class="text-2xl font-bold PendingAppointmentCountDashboard">0</div>
    </div>
    <!-- Total Sales -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-500 text-sm">Total Sales</div>
      <div class="text-2xl font-bold TotalSales">₱0.00</div>
    </div>
  </div>

  <!-- Charts Row 1 -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Sales Chart -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-700 font-semibold mb-2">Sales (Last 7 Days)</div>
      <div id="salesChart"></div>
    </div>
    <!-- Appointments Chart -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-700 font-semibold mb-2">Appointments by Status</div>
      <div id="appointmentChart"></div>
    </div>
  </div>

  <!-- Charts Row 2 -->
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Employee Services Chart -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-700 font-semibold mb-2">Services Rendered per Employee</div>
      <div id="employeeChart"></div>
    </div>
    <!-- Popular Products Chart -->
    <div class="bg-white shadow rounded-lg p-4">
      <div class="text-gray-700 font-semibold mb-2">Most Sold Products</div>
      <div id="productChart"></div>
    </div>
  </div>
</main>

<?php 
include "../src/components/view/footer.php";
?>

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="../static/js/view/dashboard.js"></script>
