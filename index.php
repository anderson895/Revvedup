<?php 
include "src/components/header.php";
?>



<body class="min-h-screen bg-gradient-to-b from-black to-red-900 flex flex-col items-center text-white relative">
<!-- Navbar -->
<div class="absolute top-4 right-6 flex items-center gap-4">
    <a href="#" class="text-gray-300 hover:text-white text-sm">Log In as</a>
    <a href="admin_login" class="text-gray-300 hover:text-white text-sm">Admin</a>
    <a href="employee_login" class="text-gray-300 hover:text-white text-sm">Employee</a>
</div>



 <!-- Spinner Overlay -->
  <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50  bg-white/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
  </div>


<!-- Container for Logo and Form -->
<div class="flex flex-col items-center justify-center flex-grow -mt-16"> 
  <!-- Logo -->
  <img src="static/images/login_logo.png" alt="RevvedUp Logo" class="mx-auto w-56 mb-2">


</div>
</body>

<?php 
include "src/components/footer.php";
?>
<script src="static/js/admin_login.js"></script>