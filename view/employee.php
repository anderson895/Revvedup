<?php 
include "../src/components/view/header.php";
?>
  
<main class="flex-1 flex flex-col bg-gray-100 min-h-screen">
  
  <!-- Topbar -->
  <header class="bg-red-900 text-white flex items-center space-x-3 px-6 py-4">
    <span class="material-icons cursor-pointer hover:text-gray-200" id="btnBack">
      arrow_back
    </span>
    <h1 class="text-lg font-semibold">Employee Management</h1>
  </header>

<!-- Month + Week Selector -->
<div class="flex items-center bg-white px-4 py-2 space-x-2">
  <!-- Prev Month -->
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="prevMonth">
    keyboard_double_arrow_left
  </button>

  <!-- Prev Week -->
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="prevWeek">
    chevron_left
  </button>

  <span class="mx-2 font-medium text-gray-700" id="monthLabel"></span>
  <span class="ml-2 text-sm text-gray-500" id="weekLabel"></span>

  <!-- Next Week -->
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="nextWeek">
    chevron_right
  </button>

  <!-- Next Month -->
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="nextMonth">
    keyboard_double_arrow_right
  </button>
</div>





  <!-- Employee Table -->
  <div class="overflow-x-auto px-4 py-4">
    <table class="w-full text-sm text-gray-700 bg-white">
      <thead>
        <tr class="bg-white">
            <th class="p-2 border text-left"></th>
            <th class="p-2 border text-center">Mon</th>
            <th class="p-2 border text-center">Tue</th>
            <th class="p-2 border text-center">Wed</th>
            <th class="p-2 border text-center">Thu</th>
            <th class="p-2 border text-center">Fri</th>
            <th class="p-2 border text-center">Sat</th>
            <th class="p-2 border text-center">Sun</th>
            <th class="p-2 border text-center">Total Commission</th>
            <th class="p-2 border text-center">Total Deductions</th>
            <th class="p-2 border text-center">Overall TOTAL</th>
            <th class="p-2 border text-center"></th>
        </tr>
        </thead>

      <tbody id="employeeTableBody">

        <!-- Dynamic Rows via jQuery -->
      </tbody>
        <tfoot id="tableFooter" style="display:none;">
            <tr class="bg-red-900 text-white font-semibold">
                <td class="p-2 "> </td> <!-- Name column -->
                <td class="p-2 text-center" id="colMon">0</td>
                <td class="p-2 text-center" id="colTue">0</td>
                <td class="p-2 text-center" id="colWed">0</td>
                <td class="p-2 text-center" id="colThu">0</td>
                <td class="p-2 text-center" id="colFri">0</td>
                <td class="p-2 text-center" id="colSat">0</td>
                <td class="p-2 text-center" id="colSun">0</td>
                <td class="p-2 text-center" id="colCommission">0</td>
                <td class="p-2 text-center" id="colDeductions">0</td>
                <td class="p-2 text-center" id="colOverall">0</td>
                <td class="p-2 "> </td> <!-- Actions column -->
            </tr>
        </tfoot>


    </table>
  </div>
</main>




<!-- Edit Deductions Modal -->
<div id="UpdateEmpRecorModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30 backdrop-blur-sm" style="display:none;">
  <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
    <h2 class="text-lg font-semibold mb-2">Update Total Deduction</h2>

    <!-- Display Employee Name and Date/Week -->
    <div class="mb-4">
      <p class="text-sm text-gray-600">
        Employee: <span id="modalEmpName" class="font-medium"></span>
      </p>
      <p class="text-sm text-gray-600">
        Date / Week: <span id="modalMonthWeek" class="font-medium"></span>
      </p>
    </div>

    <form id="FrmEditDeduction">
      <input type="hidden" id="empId" name="empId">

      <div class="mb-4">
        <label for="deductionDate" class="block text-sm font-medium text-gray-700 mb-1">Total Deduction</label>


        <input type="hidden" id="deductionDate" name="deductionDate" class="w-full border rounded px-3 py-2">


        <input type="text" id="deduction" name="deduction" class="w-full border rounded px-3 py-2" required>
      </div>
      <div class="flex justify-end space-x-2">
        <button type="button" id="closeEmpRecorModal" class="cursor-pointer px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 cursor-pointer py-2 bg-red-900 text-white rounded hover:bg-red-800">Update</button>
      </div>
    </form>
  </div>
</div>





<?php 
include "../src/components/view/footer.php";
?>





<script src="../static/js/view/employee.js"></script>