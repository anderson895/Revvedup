<?php 
session_start();

include "auth.php";

$db = new auth_class();

if (isset($_SESSION['user_id'])) {
    $id = intval($_SESSION['user_id']);
    $On_Session = $db->check_account($id);

    if (empty($On_Session)) {
        header('location: ../login');
        exit();
    }
} else {
   header('location: ../login');
   exit();
}

// Authorization
$authorize = (strtolower($On_Session['position']) !== "admin") ? "hidden" : "";



            


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS System</title>
  <link href="../src/output.css" rel="stylesheet">
  <link href="../src/alertifyconfig.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">



   <!-- Select2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <!-- jQuery (Required by Select2) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Select2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


</head>
<body class="bg-gray-50 min-h-screen flex">

  <!-- Sidebar (hidden on small screens, visible on md+) -->
<aside class="hidden md:flex w-20 bg-gray-100 border-r flex-col justify-between">

  <!-- Top Section (Logo + Menu) -->
  <div>
    <!-- Logo Top -->
    <div class="bg-black flex justify-center items-center py-4">
      <a href="dashboard" class="flex justify-center">
        <img src="../static/images/login_logo.png" alt="Logo" class="h-11 w-auto object-contain">
      </a>
    </div>

    <!-- Menu Items (ilagay agad sa ilalim ng logo) -->
    <div class="flex flex-col space-y-2">
      <a href="sales" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200">
        <img src="../static/images/menus.png" alt="Sales">
      </a>
      <a href="transaction" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200">
        <img src="../static/images/transaction.png" alt="Transaction">
      </a>
      <a href="inventory" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200">
        <img src="../static/images/inventory.png" alt="Inventory">
      </a>
      <a href="analytics" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200">
        <img src="../static/images/analytics.png" alt="Analytics">
      </a>
      <a href="team" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200 <?= $authorize ?>">
        <img src="../static/images/team_management.png" alt="Team">
      </a>
    </div>
  </div>

  <!-- Bottom Items -->
  <div class="space-y-6 mb-4">
    <a href="logout.php" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-100 hover:scale-105 transition duration-200">
      <img src="../static/images/logout.png" alt="Logout">
    </a>
    <a href="settings" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition duration-200 <?= $authorize ?>">
      <img src="../static/images/settings.png" alt="Settings">
    </a>
  </div>
</aside>


  <!-- Bottom Navigation (mobile only) -->
  <nav class="fixed bottom-0 left-0 right-0 bg-gray-100 border-t flex justify-around py-2 md:hidden">
    <a href="sales" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition duration-200">
      <img src="../static/images/menus.png" alt="Sales" class="h-6">
    </a>
    <a href="transaction" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition duration-200">
      <img src="../static/images/transaction.png" alt="Transaction" class="h-6">
    </a>
    <a href="inventory" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition duration-200">
      <img src="../static/images/inventory.png" alt="Inventory" class="h-6">
    </a>
    <a href="analytics" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition duration-200">
      <img src="../static/images/analytics.png" alt="Analytics" class="h-6">
    </a>
    <a href="team" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition duration-200 <?= $authorize ?>">
      <img src="../static/images/team_management.png" alt="Team" class="h-6">
    </a>
  </nav>

</body>
</html>
