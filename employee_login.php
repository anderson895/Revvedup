<?php 
include "src/components/header.php";
?>


<body class="min-h-screen bg-gradient-to-b from-black to-red-900 flex flex-col items-center text-white relative">
<!-- Navbar -->
<div class="absolute top-4 right-6 flex items-center gap-4">
    <a href="#" class="text-gray-300 hover:text-white text-sm">Log In as</a>
    <a href="admin_login" class="text-gray-300 hover:text-white text-sm">Admin</a>
    <a href="employee_login" class="text-white font-semibold border-b-2 border-white text-sm">Employee</a>
</div>

  <div id="spinner" class="absolute inset-0 flex items-center justify-center z-50  bg-white/70" style="display:none;">
      <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
  </div>


<!-- Container for Logo and Form -->
<div class="flex flex-col items-center justify-center flex-grow -mt-16"> 
  <!-- Logo -->
  <img src="static/images/login_logo.png" alt="RevvedUp Logo" class="mx-auto w-56 mb-2">

  <!-- Login Form -->
  <div class="bg-transparent p-6 rounded-lg w-80">
      <h1 class="text-2xl font-bold text-center mb-4">Employee</h1>
      <form id="frmLogin" method="POST" class="flex flex-col space-y-4">
        <input type="text" placeholder="PIN" name="pin" class="px-4 py-2 rounded bg-white text-black placeholder-gray-500 focus:outline-none">
        <button type="submit" class="bg-white cursor-pointer text-black font-semibold py-2 rounded hover:bg-gray-200">Login</button>
        <!-- <a href="#" class="underline text-xs text-gray-300 hover:text-white text-center">Forgot PIN</a> -->
      </form>
  </div>
</div>
</body>
<?php 
include "src/components/footer.php";
?>
<script src="static/js/employee_login.js"></script>