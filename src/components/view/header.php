<?php 
session_start();

include "auth.php";

$db = new auth_class();

if (isset($_SESSION['user_id'])) {
    $id = intval($_SESSION['user_id']);
    $On_Session = $db->check_account($id);

    // echo "<pre>";
    // print_r($On_Session);
    // echo "</pre>";

    if (empty($On_Session)) {
        header('location: ../login');
    }
} else {
   header('location: ../login');
}


if (strtolower($On_Session['position']) !== "admin") {
    $authorize = "hidden";
} else {
    $authorize = "";
}




?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS System</title>
  <link href="../src/output.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/css/alertify.css" integrity="sha512-MpdEaY2YQ3EokN6lCD6bnWMl5Gwk7RjBbpKLovlrH6X+DRokrPRAF3zQJl1hZUiLXfo2e9MrOt+udOnHCAmi5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js" integrity="sha512-JnjG+Wt53GspUQXQhc+c4j8SBERsgJAoHeehagKHlxQN+MtCCmFDghX9/AcbkkNRZptyZU4zC8utK59M5L45Iw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


</head>
<body class="bg-gray-50 min-h-screen flex">

  <!-- Sidebar (hidden on small screens, visible on md+) -->
<aside class="hidden md:flex w-16 bg-gray-100 border-r flex-col justify-between py-6">
  <div class="space-y-6">
    <a href="sales" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/menus.png" alt="Sales">
    </a>
    <a href="pos" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/transaction.png" alt="Transaction">
    </a>
    <a href="inventory" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/inventory.png" alt="Inventory">
    </a>
    <a href="analytics.php" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/analytics.png" alt="Analytics">
    </a>
    <a href="team.php" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/team_management.png" alt="Team">
    </a>
  </div>
  <div class="space-y-6">
    <a href="logout.php" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-100 hover:scale-105 transition transform duration-200">
      <img src="../static/images/logout.png" alt="Logout">
    </a>
    <a href="settings.php" class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-200 hover:scale-105 transition transform duration-200">
      <img src="../static/images/settings.png" alt="Settings">
    </a>
  </div>
</aside>

<!-- Bottom Navigation (mobile only) -->
<nav class="fixed bottom-0 left-0 right-0 bg-gray-100 border-t flex justify-around py-2 md:hidden">
  <a href="sales" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition transform duration-200">
    <img src="../static/images/menus.png" alt="Sales" class="h-6">
  </a>
  <a href="transaction.php" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition transform duration-200">
    <img src="../static/images/transaction.png" alt="Transaction" class="h-6">
  </a>
  <a href="inventory" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition transform duration-200">
    <img src="../static/images/inventory.png" alt="Inventory" class="h-6">
  </a>
  <a href="analytics.php" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition transform duration-200">
    <img src="../static/images/analytics.png" alt="Analytics" class="h-6">
  </a>
  <a href="team.php" class="p-2 rounded-lg hover:bg-gray-200 hover:scale-110 transition transform duration-200">
    <img src="../static/images/team_management.png" alt="Team" class="h-6">
  </a>
</nav>

