<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">

  <!-- Sidebar (hidden on small screens, visible on md+) -->
  <aside class="hidden md:flex w-16 bg-gray-100 border-r flex-col justify-between py-6">
    <div class="space-y-6">
      <button class="flex justify-center w-full">
        <img src="../static/images/menus.png" alt="Sales">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/transaction.png" alt="Transaction">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/inventory.png" alt="Inventory">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/analytics.png" alt="Analytics">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/team_management.png" alt="Team">
      </button>
    </div>
    <div class="space-y-6">
      <a href="logout.php" 
        class="flex justify-center w-full p-2 rounded-lg hover:bg-gray-100 transition">
        <img src="../static/images/logout.png" alt="Logout">
      </a>

      <button class="flex justify-center w-full">
        <img src="../static/images/settings.png" alt="Settings">
      </button>
    </div>
  </aside>

  <!-- Bottom Navigation (mobile only) -->
  <nav class="fixed bottom-0 left-0 right-0 bg-gray-100 border-t flex justify-around py-2 md:hidden">
    <button>
      <img src="../static/images/menus.png" alt="Sales" class="h-6">
    </button>
    <button>
      <img src="../static/images/transaction.png" alt="Transaction" class="h-6">
    </button>
    <button>
      <img src="../static/images/inventory.png" alt="Inventory" class="h-6">
    </button>
    <button>
      <img src="../static/images/analytics.png" alt="Analytics" class="h-6">
    </button>
    <button>
      <img src="../static/images/team_management.png" alt="Team" class="h-6">
    </button>
  </nav>