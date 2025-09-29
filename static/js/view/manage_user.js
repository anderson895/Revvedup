$(document).ready(function(){

  // Open modals
  $("#addUserBtn").click(()=> $("#addUserModal").fadeIn());
  $(document).on("click", function(e){
    if ($(e.target).is("#addUserModal")) $("#addUserModal").fadeOut();
    if ($(e.target).is("#updateUserModal")) $("#updateUserModal").fadeOut();
  });
  $("#closeUpdateUserModal").click(()=> $("#updateUserModal").fadeOut());

  // Add user
  $("#frmAddUser").submit(function(e){
    e.preventDefault();
    var formData = new FormData();
    formData.append('firstname', $('#firstnameAdd').val().trim());
    formData.append('lastname', $('#lastnameAdd').val().trim());
    formData.append('username', $('#usernameAdd').val().trim());
    formData.append('email', $('#emailAdd').val().trim());
    formData.append('pin', $('#pinAdd').val().trim());
    formData.append('requestType', 'AddUser');

    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      dataType: "json",
      success: function(res){
        if(res.status===200) Swal.fire('Success!', res.message, 'success').then(()=> location.reload());
        else Swal.fire('Error!', res.message || "Something went wrong.", 'error');
      }
    });
  });

  // Fetch users
  function fetchUsers(){
    $.ajax({
      url: "../controller/end-points/controller.php",
      method: "GET",
      data: { requestType: "fetch_all_users" },
      dataType: "json",
      success: function(res){
        $('#userTableBody').empty();
        if(res.status===200 && res.data.length>0){
          res.data.forEach(data=>{
            if(data.position!=='employee') return;

            let isActive = data.status == 1;
            let statusText = isActive ? 'Active' : 'Inactive';
            let btnLabel   = isActive ? 'Deactivate' : 'Activate';
            let btnClass   = isActive 
                ? 'bg-red-100 text-red-700 hover:bg-red-200'
                : 'bg-green-100 text-green-700 hover:bg-green-200';

            $('#userTableBody').append(`
              <tr>
                <td class="px-4 py-2">${data.firstname || ''}</td>
                <td class="px-4 py-2">${data.lastname || ''}</td>
                <td class="px-4 py-2">${data.username || ''}</td>
                <td class="px-4 py-2">${data.email || ''}</td>
                <td class="px-4 py-2">${data.pin || ''}</td>
                <td class="px-4 py-2">${statusText || ''}</td>
                <td class="px-4 py-2 flex justify-center space-x-2">
                  <button 
                  class="updateBtn flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 
                        hover:bg-blue-50 hover:text-blue-600 transition-colors shadow-sm"
                  data-user_id="${data.user_id}" 
                  data-firstname="${data.firstname}" 
                  data-lastname="${data.lastname}" 
                  data-username="${data.username}" 
                  data-email="${data.email}" 
                  data-pin="${data.pin || ''}">
                  <span class="material-icons text-base">edit</span>
                  Edit
                </button>

                <button 
                  class="statusBtn flex items-center gap-2 px-4 py-2 rounded-lg font-medium shadow-sm 
                        ${btnClass} transition-colors"
                  data-user_id="${data.user_id}" 
                  data-status="${data.status}">
                  <span class="material-icons text-base">
                    ${data.status === 'active' ? 'check_circle' : 'block'}
                  </span>
                  ${btnLabel}
                </button>

                </td>
              </tr>
            `);
          });
        } else {
          $('#userTableBody').append('<tr><td colspan="7" class="p-4 text-center text-gray-400 italic">No record found</td></tr>');
        }
      }
    });
  }
  fetchUsers();

  // Open update modal
  $(document).on("click", ".updateBtn", function(){
    $("#userId").val($(this).data("user_id"));
    $("#firstnameUpdate").val($(this).data("firstname"));
    $("#lastnameUpdate").val($(this).data("lastname"));
    $("#usernameUpdate").val($(this).data("username"));
    $("#emailUpdate").val($(this).data("email"));
    $("#pinUpdate").val($(this).data("pin"));
    $('#updateUserModal').fadeIn();
  });

  // Update user
  $("#frmUpdateUser").submit(function(e){
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('requestType','UpdateUser');
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function(res){
        if(res.status===200) Swal.fire('Success!', res.message, 'success').then(()=> fetchUsers());
        else Swal.fire('Error!', res.message || "Error updating.", 'error');
      }
    });
  });

  // Activate / Deactivate toggle
  $(document).on('click', '.statusBtn', function(){
    const userId = $(this).data("user_id");
    const currentStatus = $(this).data("status"); // 1=active, 0=inactive
    const username = $(this).closest('tr').find('td:eq(2)').text();

    let action = currentStatus == 1 ? 'deactivateUser' : 'restoreUser';
    let confirmText = currentStatus == 1 ? 'Deactivate' : 'Activate';
    let color = currentStatus == 1 ? 'red' : 'green';

    Swal.fire({
      title: `${confirmText} <span style="color:${color};">${username}</span>?`,
      html: `This will ${confirmText.toLowerCase()} the user.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: `Yes, ${confirmText.toLowerCase()}!`,
      cancelButtonText: 'No, cancel!'
    }).then(result=>{
      if(result.isConfirmed){
        $.ajax({
          url: "../controller/end-points/controller.php",
          type: "POST",
          data: { userId: userId, requestType: action },
          dataType: "json",
          success: function(res){
            if(res.status===200) Swal.fire('Success!', res.message, 'success').then(()=> fetchUsers());
            else Swal.fire('Error!', res.message, 'error');
          }
        });
      }
    });
  });

});