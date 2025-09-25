<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS System</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex">

  <!-- Sidebar -->
  <aside class="w-16 bg-gray-100 border-r flex flex-col justify-between py-6">
    <div class="space-y-6">
      <button class="flex justify-center w-full">
        <img src="../static/images/menus.png" alt="Sales">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/transaction.png" alt="Print">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/inventory.png" alt="Reports">
      </button>
      <button class="flex justify-center w-full">
        <img src="../static/images/analytics.png" alt="Users">
      </button>
       <button class="flex justify-center w-full">
        <img src="../static/images/team_management.png" alt="Users">
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

  <!-- Main Content -->
  <main class="flex-1 flex flex-col">

    <!-- Top Bar -->
    <header class="flex items-center gap-3 px-4 py-3 border-b bg-white">
      <input type="text" placeholder="Enter Item Code"
        class="flex-1 border rounded px-3 py-2 text-sm focus:outline-none focus:ring focus:border-red-400">
      <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">REFUND | EXCHANGE</button>
      <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">SERVICE</button>
      <button class="bg-red-600 text-white px-4 py-2 rounded text-sm">ITEM</button>
      <button>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 6a5 5 0 100 10 5 5 0 000-10z"/>
        </svg>
      </button>
    </header>

    <!-- Table -->
    <section class="flex-1 flex flex-col">
      <div class="grid grid-cols-5 gap-2 text-sm font-semibold border-b bg-gray-50 px-4 py-2">
        <span>Item ID</span>
        <span>Item Name</span>
        <span>Quantity</span>
        <span>Unit price</span>
        <span>Total</span>
      </div>
      <!-- Empty Cart -->
      <div class="flex-1 flex items-center justify-center">
        <div class="text-center text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto w-20 h-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h14l-1.35 6.45A2 2 0 0117.7 21H8.3a2 2 0 01-1.95-1.55L4 5H2"/>
          </svg>
          <p>No items in cart</p>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="flex justify-between items-center bg-white border-t px-4 py-3">
  <!-- Proceed Button -->
  <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition duration-200 ease-in-out">
    Proceed to Payment
  </button>

  <!-- Total Box -->
  <div class="bg-red-800 text-white px-6 py-2 rounded-lg font-semibold shadow">
    TOTAL
  </div>
</footer>

  </main>
</body>
</html>
