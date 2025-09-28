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
<div class="flex items-center bg-white px-4 py-2">
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="prevWeek">
    chevron_left
  </button>
  <span class="mx-2 font-medium text-gray-700" id="monthLabel"></span>
  <span class="ml-2 text-sm text-gray-500" id="weekLabel"></span>
  <button class="material-icons text-gray-600 hover:text-gray-800 cursor-pointer" id="nextWeek">
    chevron_right
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
      <tfoot>
        <tr class="bg-red-900 text-white font-semibold">
          <td class="p-2 "> </td>
          <td class="p-2  text-center" id="colTue">0</td>
          <td class="p-2  text-center" id="colWed">0</td>
          <td class="p-2  text-center" id="colThu">0</td>
          <td class="p-2  text-center" id="colFri">0</td>
          <td class="p-2  text-center" id="colSat">0</td>
          <td class="p-2  text-center" id="colSun">0</td>
          <td class="p-2  text-center" id="colMon">0</td>
          <td class="p-2  text-center" id="colCommission">0</td>
          <td class="p-2  text-center" id="colDeductions">0</td>
          <td class="p-2  text-center" id="colOverall">0</td>
          <td class="p-2 "> </td>
        </tr>
      </tfoot>
    </table>
  </div>
</main>

<?php 
include "../src/components/view/footer.php";
?>


<script>
$(document).ready(function () {
  const monthNames = [
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
  ];

  // set to today (Monday-Sunday aligned)
  let currentDate = new Date();
  alignToMonday(currentDate);

  function alignToMonday(date) {
    let day = date.getDay(); // 0=Sun ... 6=Sat
    let diff = (day === 0 ? -6 : 1 - day);
    date.setDate(date.getDate() + diff);
  }

  function updateLabels() {
    let monday = new Date(currentDate);
    let sunday = new Date(currentDate);
    sunday.setDate(monday.getDate() + 6);

    let month = monthNames[monday.getMonth()];
    let year = monday.getFullYear();

    // compute week number in month
    let firstDayOfMonth = new Date(year, monday.getMonth(), 1);
    let startOffset = (firstDayOfMonth.getDay() + 6) % 7;
    let weekNumber = Math.ceil((monday.getDate() + startOffset) / 7);

    $("#monthLabel").text(`${month} ${year}`);
    $("#weekLabel").text(`( Week ${weekNumber} )`);
  }

  $("#prevWeek").click(function () {
    currentDate.setDate(currentDate.getDate() - 7);
    updateLabels();
    fetchEmployees();
  });

  $("#nextWeek").click(function () {
    currentDate.setDate(currentDate.getDate() + 7);
    updateLabels();
    fetchEmployees();
  });

  updateLabels();

  let employees = [];

  function fetchEmployees() {
    $.ajax({
      url: "../controller/end-points/controller.php",
      method: "GET",
      data: { requestType: "fetch_all_employee_record" },
      dataType: "json",
      success: function (res) {
        if (res.status === 200) {
          employees = res.data.map((emp) => {
            // days are {1:0,2:0,...7:0} => convert to array [Mon..Sun]
            let dayArr = [];
            for (let i = 1; i <= 7; i++) {
              dayArr.push(emp.days[i] ?? 0);
            }

            return {
              id: emp.id,
              name: emp.name,
              days: dayArr,
              commission: parseFloat(emp.commission),
              deductions: parseFloat(emp.deductions),
              months: emp.months,
            };
          });
          renderTable();
        } else {
          console.warn("No employee records found");
          $("#employeeTableBody").html(
            `<tr><td colspan="11" class="text-center p-4 text-gray-500">No records available</td></tr>`
          );
        }
      },
      error: function (err) {
        console.error("AJAX Error:", err);
      },
    });
  }

  function renderTable() {
    let tbody = $("#employeeTableBody");
    tbody.empty();

    let colTotals = Array(7).fill(0);
    let totalCommission = 0;
    let totalDeductions = 0;
    let totalOverall = 0;

    employees.forEach((emp) => {
      let row = `<tr class="hover:bg-gray-50">
        <td class="p-2 border-r font-medium">${emp.name}</td>`;

      emp.days.forEach((val, i) => {
        row += `<td class="p-2 text-center">${val}</td>`;
        colTotals[i] += val;
      });

      row += `<td class="p-2 border text-center">${emp.commission.toLocaleString()}</td>`;
      row += `<td class="p-2 border text-center">${emp.deductions.toLocaleString()}</td>`;
      row += `<td class="p-2 border text-center font-bold">${(
        emp.commission - emp.deductions
      ).toLocaleString()}</td>`;
      row += `<td class="p-2 text-center flex items-center justify-center space-x-1">
                <button class="text-gray-600 hover:text-blue-600 material-icons text-sm">edit</button>
                <button class="text-gray-600 hover:text-red-600 material-icons text-sm">delete</button>
              </td></tr>`;

      tbody.append(row);

      totalCommission += emp.commission;
      totalDeductions += emp.deductions;
      totalOverall += emp.commission - emp.deductions;
    });

    // update footer totals
    $("#colMon").text(colTotals[0]);
    $("#colTue").text(colTotals[1]);
    $("#colWed").text(colTotals[2]);
    $("#colThu").text(colTotals[3]);
    $("#colFri").text(colTotals[4]);
    $("#colSat").text(colTotals[5]);
    $("#colSun").text(colTotals[6]);
    $("#colCommission").text(totalCommission.toLocaleString());
    $("#colDeductions").text(totalDeductions.toLocaleString());
    $("#colOverall").text(totalOverall.toLocaleString());
  }

  // 🔹 initial fetch
  fetchEmployees();
});


</script>
