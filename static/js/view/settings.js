$(document).ready(function(){

  // --- Tab Switching ---
  $(".tab-btn").on("click", function(){
    let tab = $(this).data("tab");

    // itago lahat
    $(".tab-content").addClass("hidden"); 
    $(".tab-btn").removeClass("border-white text-white")
                 .addClass("border-transparent text-gray-300");

    // ipakita yung napiling tab
    $("#" + tab).removeClass("hidden"); 
    $(this).removeClass("text-gray-300 border-transparent")
           .addClass("border-white text-white");

    // i-save sa localStorage
    localStorage.setItem("activeTab", tab);
  });

  // --- On page load: check kung may last active tab
  let lastTab = localStorage.getItem("activeTab");
  if (lastTab) {
    $(".tab-content").addClass("hidden");
    $(".tab-btn").removeClass("border-white text-white")
                 .addClass("border-transparent text-gray-300");

    $("#" + lastTab).removeClass("hidden"); 
    $(".tab-btn[data-tab='" + lastTab + "']")
      .removeClass("text-gray-300 border-transparent")
      .addClass("border-white text-white");
  }

  // 🔄 Common function for response
  function handleResponse(res, successMsg, errorMsg) {
    if (res.toLowerCase().includes("success")) {
      alertify.success(successMsg);
      setTimeout(function(){
        location.reload();
      }, 2000); // reload after delay
    } else {
      alertify.error(res || errorMsg);
    }
    console.log(res);
  }

  // --- Business Info Form Submit ---
  $("#frmUpdateBusinessInfo").on("submit", function(e){
    e.preventDefault();
    localStorage.setItem("activeTab", "business"); // tandaan kung saan tab galing
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: $(this).serialize() + "&requestType=update_business",
      success: function(res){
        handleResponse(res, "Business info updated!", "Failed to update business info.");
      },
      error: function(){
        alertify.error("Request failed.");
      }
    });
  });

  // --- Basic Info Form Submit ---
  $("#frmUpdateBasicInfo").on("submit", function(e){
    e.preventDefault();
    localStorage.setItem("activeTab", "basic"); // tandaan kung saan tab galing
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: $(this).serialize() + "&requestType=update_basic",
      success: function(res){
        handleResponse(res, "Basic info updated!", "Failed to update basic info.");
      },
      error: function(){
        alertify.error("Request failed.");
      }
    });
  });

  // --- Security Form Submit ---
  $("#frmUpdateSecurity").on("submit", function(e){
    e.preventDefault();
    localStorage.setItem("activeTab", "security"); // tandaan kung saan tab galing
    $.ajax({
      url: "../controller/end-points/controller.php",
      type: "POST",
      data: $(this).serialize() + "&requestType=update_security",
      success: function(res){
        handleResponse(res, "Security updated!", "Failed to update security.");
      },
      error: function(){
        alertify.error("Request failed.");
      }
    });
  });

});