






    // START CODE FOR FETCHING EMPLOYEE RECORD
$(document).ready(function () {
  const monthNames = [
    "January","February","March","April","May","June",
    "July","August","September","October","November","December"
  ];

  let currentDate = null; // no default date
  let employees = [];
  let userPosition = "";

  function alignToMonday(date) {
    let day = date.getDay(); // 0=Sun ... 6=Sat
    let diff = (day === 0 ? -6 : 1 - day);
    date.setDate(date.getDate() + diff);
  }

  function updateLabels() {
    if (!currentDate) {
      $("#monthLabel").text("No month selected");
      $("#weekLabel").text("");
      return;
    }

    let monday = new Date(currentDate);
    let month = monthNames[monday.getMonth()];
    let year = monday.getFullYear();

    // Calculate sequential week of year
    let firstDayOfYear = new Date(year, 0, 1);
    let dayDiff = Math.floor((monday - firstDayOfYear) / (1000 * 60 * 60 * 24));
    let weekNumber = Math.ceil((dayDiff + firstDayOfYear.getDay() + 1) / 7);

    $("#monthLabel").text(`${month} ${year}`);
    $("#weekLabel").text(`( Week ${weekNumber} )`);
  }

  $("#prevWeek").click(function () {
    if (!currentDate) return;
    currentDate.setDate(currentDate.getDate() - 7);
    alignToMonday(currentDate);
    updateLabels();
    fetchEmployees();
  });

  $("#nextWeek").click(function () {
    if (!currentDate) return;
    currentDate.setDate(currentDate.getDate() + 7);
    alignToMonday(currentDate);
    updateLabels();
    fetchEmployees();
  });

  function fetchEmployees() {
    let month = currentDate ? currentDate.getMonth() + 1 : null;
    let year  = currentDate ? currentDate.getFullYear() : null;

    let weekNumber = null;
    if (currentDate) {
      let firstDayOfYear = new Date(currentDate.getFullYear(), 0, 1);
      let dayDiff = Math.floor((currentDate - firstDayOfYear) / (1000 * 60 * 60 * 24));
      weekNumber = Math.ceil((dayDiff + firstDayOfYear.getDay() + 1) / 7);
    }

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
        if (res.status === 200) {
          userPosition = res.position;

          // set default from controller
          if (!currentDate && res.default) {
            let def = res.default;
            currentDate = new Date(def.year, def.month - 1, 1);

            // calculate date for the Monday of the default week
            let firstDayOfYear = new Date(def.year, 0, 1);
            let daysFromJan1 = (def.week - 1) * 7;
            currentDate = new Date(firstDayOfYear.getTime() + daysFromJan1 * 24 * 60 * 60 * 1000);
            alignToMonday(currentDate);
          }

          employees = res.data.map(emp => {
            let dayArr = [];
            for (let i = 1; i <= 7; i++) dayArr.push(emp.days[i] ?? 0);
            return {
              emp_id: emp.user_id,
              name: emp.name,
              days: dayArr,
              commission: parseFloat(emp.commission),
              deductions: parseFloat(emp.deductions),
              months: emp.months
            };
          });

          renderTable();
          updateLabels();
          $("#tableFooter").show();
        } else {
          employees = [];
          $("#employeeTableBody").html(
            `<tr><td colspan="12" class="text-center p-4 text-gray-500">No records available</td></tr>`
          );
          $("#tableFooter").hide();
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

      row += `<td class="p-2 text-center flex items-center justify-center space-x-1">`;
      if (userPosition === "admin") {
        row += `<button class="btnUpdateEmpRecord cursor-pointer text-gray-600 hover:text-blue-600 material-icons text-sm"
                    data-emp_id="${emp.emp_id}"
                    data-deductions="${emp.deductions}"
                    data-emp_name="${emp.name}">
                    edit
                </button>`;
      }
      row += `</td></tr>`;

      tbody.append(row);

      totalCommission += emp.commission;
      totalDeductions += emp.deductions;
      totalOverall += emp.commission - emp.deductions;
    });

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

  // initial load
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
