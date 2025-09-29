






    // START CODE FOR FETCHING EMPLOYEE RECORD
$(document).ready(function () {
  const monthNames = [
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
  ];

  let currentDate = new Date();
  alignToMonday(currentDate);

  function alignToMonday(date) {
    let day = date.getDay(); // 0=Sun ... 6=Sat
    let diff = (day === 0 ? -6 : 1 - day);
    date.setDate(date.getDate() + diff);
  }

  function updateLabels() {
    let monday = new Date(currentDate);
    let month = monthNames[monday.getMonth()];
    let year = monday.getFullYear();

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
    let monday = new Date(currentDate);
    let month = monday.getMonth() + 1;
    let year = monday.getFullYear();

    let firstDayOfMonth = new Date(year, month - 1, 1);
    let startOffset = (firstDayOfMonth.getDay() + 6) % 7;
    let weekNumber = Math.ceil((monday.getDate() + startOffset) / 7);

    $.ajax({
      url: "../controller/end-points/controller.php",
      method: "GET",
      data: { 
        requestType: "fetch_all_employee_record",
        month,
        year,
        week: weekNumber
      },
      dataType: "json",
      success: function (res) {
        if (res.status === 200 && res.data.length > 0) {
          employees = res.data.map(emp => {
            let dayArr = [];
            for (let i = 1; i <= 7; i++) dayArr.push(emp.days[i] ?? 0);
            return {
              emp_id: emp.emp_id,
              name: emp.name,
              days: dayArr,
              commission: parseFloat(emp.commission),
              deductions: parseFloat(emp.deductions),
              months: emp.months
            };
          });
          renderTable();
          $("#tableFooter").show(); // show footer when records exist
        } else {
          employees = [];
          $("#employeeTableBody").html(
            `<tr><td colspan="12" class="text-center p-4 text-gray-500">No records available</td></tr>`
          );
          $("#tableFooter").hide(); // hide footer when no records
        }
      },
      error: function (err) {
        console.error("AJAX Error:", err);
        $("#employeeTableBody").html(
          `<tr><td colspan="12" class="text-center p-4 text-gray-500">Error loading data</td></tr>`
        );
        $("#tableFooter").hide(); 
      }
    });
  }

  function renderTable() {
    let tbody = $("#employeeTableBody");
    tbody.empty();

    let colTotals = Array(7).fill(0);
    let totalCommission = 0;
    let totalDeductions = 0;
    let totalOverall = 0;

    employees.forEach(emp => {
      let row = `<tr class="hover:bg-gray-50">
        <td class="p-2 border-r font-medium capitalize">${emp.name}</td>`;

      emp.days.forEach((val, i) => {
        row += `<td class="p-2 border-r text-center">${val}</td>`;
        colTotals[i] += val;
      });

      row += `<td class="p-2 border-r text-center">${emp.commission.toLocaleString()}</td>`;
      row += `<td class="p-2 border-r text-center">${emp.deductions.toLocaleString()}</td>`;
      row += `<td class="p-2 border-r text-center font-bold">${(emp.commission - emp.deductions).toLocaleString()}</td>`;

      row += `<td class="p-2 border-r text-center flex items-center justify-center space-x-1">
        <button class="btnUpdateEmpRecord text-gray-600 hover:text-blue-600 material-icons text-sm"
            data-emp_id="${emp.emp_id}"
            data-deductions="${emp.deductions}"
            data-emp_name="${emp.name}">
            edit
        </button>
        
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

  fetchEmployees();
});


// END CODE FOR FETCHING EMPLOYEE RECORD



// START CODE FOR MODAL SCRIPT

$(document).on("click", ".btnUpdateEmpRecord", function () {
    const emp_id = $(this).data('emp_id');
    const emp_name = $(this).data('emp_name');
    const deductions = $(this).data('deductions');


    console.log(deductions);
    // Set employee ID
    $('#empId').val(emp_id);
    $('#deduction').val(deductions);

    // Kunin ang current month, year at week from labels
    const monthYear = $("#monthLabel").text();        // e.g. "September 2025"
    const week = $("#weekLabel").text().replace(/[()]/g,''); // e.g. " Week 5"
        

    // Ilagay sa display sa modal
    $('#modalEmpName').text(emp_name);
    $('#modalMonthWeek').text(`${monthYear}${week}`);
    $('#deductionDate').val(`${monthYear}${week}`);

    // Show modal
    $("#UpdateEmpRecorModal").fadeIn();
});



// Close modal
$("#closeEmpRecorModal").click(function () {
  $("#UpdateEmpRecorModal").fadeOut();
});



// Close kapag click outside modal-content
$(document).on("click", function (e) {
    if ($(e.target).is("#UpdateEmpRecorModal")) {
        $("#UpdateEmpRecorModal").fadeOut();
    }
});





$("#FrmEditDeduction").submit(function (e) { 
    e.preventDefault();

    // Show spinner and disable button
    $('.spinner').show();
    $('#FrmEditDeduction button[type="submit"]').prop('disabled', true);

    var formData = new FormData(this);
    formData.append('requestType', 'EditDeduction');

    $.ajax({
        type: "POST",
        url: "../controller/end-points/controller.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            $('.spinner').hide();
            $('#FrmEditDeduction button[type="submit"]').prop('disabled', false);

            if (response.status === 200) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    window.location.href = 'employee';
                });
            } else {
                Swal.fire('Error', response.message || 'Something went wrong.', 'error');
            }
        }
    });

});










// DELETE EMPLOYEE RECORD
// $(document).on("click", ".btnDeleteEmpRecord", function () {
//     const emp_id = $(this).data('emp_id');
//     const emp_name = $(this).data('emp_name');

//     Swal.fire({
//         title: `Archive ${emp_name}?`,
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#d33',
//         cancelButtonColor: '#3085d6',
//         confirmButtonText: 'Yes, delete it!',
//         cancelButtonText: 'Cancel'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $.ajax({
//                 type: "POST",
//                 url: "../controller/end-points/controller.php",
//                 data: {
//                     requestType: "ArchivedEmployeeRecord",
//                     emp_id: emp_id
//                 },
//                 dataType: "json",
//                 success: function(response) {
//                     if (response.status === 200) {
//                         Swal.fire('Deleted!', response.message, 'success');
//                         // Refresh the table
//                         fetchEmployees();
//                     } else {
//                         Swal.fire('Error', response.message || 'Unable to delete record.', 'error');
//                     }
//                 },
//                 error: function(err) {
//                     console.error("AJAX Error:", err);
//                     Swal.fire('Error', 'Server error while deleting record.', 'error');
//                 }
//             });
//         }
//     });
// });
