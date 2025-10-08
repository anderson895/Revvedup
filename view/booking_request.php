<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col bg-gray-100 min-h-screen">

  <!-- Topbar -->
  <header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-6">
    <h1 class="text-lg font-semibold">PRODUCT INVENTORY</h1>
  </header>



  <!-- Search Bar -->
<div class="px-6 py-4  flex justify-center sm:justify-start">
  <div class="relative w-full sm:max-w-xs">
    <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2">
      search
    </span>
    <input
      type="text"
      id="searchInput"
      class="w-full pl-10 pr-4 py-2 rounded-md  border border-gray-700 
              placeholder-gray-500 focus:outline-none "
      placeholder="Search inventory..."
    />
  </div>
</div>

  <!-- Content -->
  <section class="p-6 flex-1">
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <!-- Table Header -->
      <div class="flex justify-between items-center  px-4 py-3">
        <h2 class="text-gray-700 font-semibold">Request Appointment List</h2>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Reference Number
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Date
            </th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Status
            </th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody id="appointmentTableBody" class="bg-white divide-y divide-gray-200">
          <!-- DYNAMIC CONTENT -->
        </tbody>
      </table>
      </div>
    </div>

    
  </section>



  <br class="block sm:hidden">
  <br class="block sm:hidden">
</main>








<!-- Modal -->
<div id="detailsModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
    <button id="closeModal" class="absolute cursor-pointer top-2 right-2 text-gray-500 hover:text-gray-700">&times;</button>
    <h2 class="text-xl font-bold mb-4">Appointment Details</h2>
    <div id="modalContent" class="space-y-2 text-gray-700">
      <!-- Dynamic content goes here -->
    </div>
  </div>
</div>







<?php 
include "../src/components/view/footer.php";
?>


<script src="../static/js/view/booking_request.js"></script>