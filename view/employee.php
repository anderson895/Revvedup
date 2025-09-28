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

  <!-- Month Selector -->
  <div class="flex items-center bg-white  px-4 py-2">
    <button class="material-icons text-gray-600 hover:text-gray-800" id="prevMonth">chevron_left</button>
    <span class="mx-2 font-medium text-gray-700" id="monthLabel">April</span>
    <button class="material-icons text-gray-600 hover:text-gray-800" id="nextMonth">chevron_right</button>
  </div>

  <!-- Employee Table -->
  <div class="overflow-x-auto px-4 py-4">
    <table class="w-full border text-sm text-gray-700 bg-white">
      <thead>
        <tr class="bg-white border-b">
          <th class="p-2 border text-left"> </th>
          <th class="p-2 border text-center">Tue</th>
          <th class="p-2 border text-center">Wed</th>
          <th class="p-2 border text-center">Thu</th>
          <th class="p-2 border text-center">Fri</th>
          <th class="p-2 border text-center">Sat</th>
          <th class="p-2 border text-center">Sun</th>
          <th class="p-2 border text-center">Mon</th>
          <th class="p-2 border text-center">Total Commission</th>
          <th class="p-2 border text-center">Total Deductions</th>
          <th class="p-2 border text-center">Overall TOTAL</th>
          <th class="p-2 border text-center"> </th>
        </tr>
      </thead>
      <tbody id="employeeTableBody" class="divide-y">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  // Sample employee data
  let employees = [
    {name: "JOMEL", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
    {name: "LOYD", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
    {name: "BUKOL", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
    {name: "ANTOT", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
    {name: "BOSS NEO", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
    {name: "DAVE", days:[200,350,120,230,300,250,380], commission:1830, deductions:0},
  ];

  function renderTable(){
    let tbody = $("#employeeTableBody");
    tbody.empty();

    let colTotals = Array(7).fill(0);
    let totalCommission = 0;
    let totalDeductions = 0;
    let totalOverall = 0;

    employees.forEach(emp=>{
      let row = `<tr class="hover:bg-gray-50">
        <td class="p-2 border font-medium">${emp.name}</td>`;

      emp.days.forEach((val,i)=>{
        row += `<td class="p-2 border text-center">${val}</td>`;
        colTotals[i]+=val;
      });

      row += `<td class="p-2 border text-center">${emp.commission.toLocaleString()}</td>`;
      row += `<td class="p-2 border text-center">${emp.deductions.toLocaleString()}</td>`;
      row += `<td class="p-2 border text-center font-bold">${(emp.commission - emp.deductions).toLocaleString()}</td>`;
      row += `<td class="p-2 border text-center flex items-center justify-center space-x-1">
                <button class="text-gray-600 hover:text-blue-600 material-icons text-sm">edit</button>
                <button class="text-gray-600 hover:text-red-600 material-icons text-sm">delete</button>
              </td></tr>`;

      tbody.append(row);

      totalCommission += emp.commission;
      totalDeductions += emp.deductions;
      totalOverall += (emp.commission - emp.deductions);
    });

    // update footer totals
    $("#colTue").text(colTotals[0]);
    $("#colWed").text(colTotals[1]);
    $("#colThu").text(colTotals[2]);
    $("#colFri").text(colTotals[3]);
    $("#colSat").text(colTotals[4]);
    $("#colSun").text(colTotals[5]);
    $("#colMon").text(colTotals[6]);
    $("#colCommission").text(totalCommission.toLocaleString());
    $("#colDeductions").text(totalDeductions.toLocaleString());
    $("#colOverall").text(totalOverall.toLocaleString());
  }

  renderTable();
});
</script>
