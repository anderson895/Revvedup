<?php 
include "../src/components/view/header.php";
?>
  
<!-- Main Content -->
<main class="flex-1 flex flex-col">

  <!-- Header -->
  <header class="bg-red-900 text-white flex flex-col px-6 py-6">
    <h1 class="text-lg font-semibold mb-4">Account Settings</h1>

    <!-- Tabs -->
    <nav class="flex space-x-6 text-sm font-medium border-b border-red-800">
      <button class="cursor-pointer tab-btn py-2 border-b-2 border-transparent text-gray-300 hover:text-white" data-tab="business" <?= $authorize ?>>Business</button>
      <button class="cursor-pointer tab-btn py-2 border-b-2 border-white text-white focus:outline-none" data-tab="basic">Basic Info</button>
      <button class="cursor-pointer tab-btn py-2 border-b-2 border-transparent text-gray-300 hover:text-white" data-tab="security" >Security</button>
    </nav>
  </header>

  <!-- Center Container -->
  <div class="flex flex-1 justify-center items-start px-4 py-8">
    <div class="bg-white shadow-md rounded-2xl p-8 w-full max-w-md">

        <!-- Business Tab -->
        <div class="tab-content hidden" id="business">
            <h1 class="text-2xl font-semibold mb-1">Business Preferences</h1>
            <p class="text-gray-500 mb-6 text-sm">Manage business-related settings here.</p>

            <form id="frmUpdateBusinessInfo" class="space-y-6">
                <!-- Business Name -->
                <div>
                    <label class="block text-gray-700 mb-1 text-sm">Business Name</label>
                    <input type="text" name="businessName" 
                           value="<?=htmlspecialchars($business['business_name'])?>" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Business Address -->
                <div>
                    <label class="block text-gray-700 mb-1 text-sm">Business Address</label>
                    <input type="text" name="businessAdd" 
                           value="<?=htmlspecialchars($business['business_address'])?>" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Contact Number -->
                <div>
                    <label class="block text-gray-700 mb-1 text-sm">Contact Number</label>
                    <input type="text" name="contactNum" 
                           value="<?=htmlspecialchars($business['business_contact_num'])?>" 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Save Button -->
                <button type="submit" 
                        class="cursor-pointer w-full bg-red-900 text-white py-2 rounded-lg hover:bg-red-800">
                    Save Business
                </button>
            </form>
        </div>

        <!-- Basic Info Tab -->
        <div class="tab-content" id="basic">
          <h1 class="text-2xl font-semibold mb-1">Basic Information</h1>
          <p class="text-gray-500 mb-6 text-sm">Manage your personal details.</p>

          <form id="frmUpdateBasicInfo" class="space-y-6">
              <!-- First Name -->
              <div>
                  <label for="firstname" class="block text-gray-700 mb-1 text-sm">First Name</label>
                  <input type="text" id="firstname" name="firstname" value="<?=$On_Session['firstname']?>" 
                         class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
              </div>

              <!-- Last Name -->
              <div>
                  <label for="lastname" class="block text-gray-700 mb-1 text-sm">Last Name</label>
                  <input type="text" id="lastname" name="lastname" value="<?=$On_Session['lastname']?>" 
                         class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
              </div>

              <!-- Username -->
              <div>
                  <label for="username" class="block text-gray-700 mb-1 text-sm">Username</label>
                  <input type="text" id="username" name="username" value="<?=$On_Session['username']?>" 
                         class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
              </div>

              <!-- Email -->
              <div>
                  <label for="email" class="block text-gray-700 mb-1 text-sm">Email</label>
                  <input type="email" id="email" name="email" value="<?=$On_Session['email']?>" 
                         class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
              </div>

              <button type="submit" class="cursor-pointer w-full bg-red-900 text-white py-2 rounded-lg hover:bg-red-800">
                  Save Basic Info
              </button>
          </form>
        </div>

        <!-- Security Tab -->
        <div class="tab-content hidden" id="security">
        <h1 class="text-2xl font-semibold mb-1">Security Settings</h1>
        <p class="text-gray-500 mb-6 text-sm">Manage your login and password.</p>

        <form id="frmUpdateSecurity" class="space-y-6">
            
            <?php if ($On_Session['position'] == 'admin'): ?>
                <!-- Old Password -->
                <div>
                    <label for="old_password" class="block text-gray-700 mb-1 text-sm">Current Password</label>
                    <input type="password" id="old_password" name="old_password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-gray-700 mb-1 text-sm">New Password</label>
                    <input type="password" id="password" name="password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label for="confirm_password" class="block text-gray-700 mb-1 text-sm">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>
            <?php elseif ($On_Session['position'] == 'employee'): ?>
                <!-- PIN for employees -->
                <div>
                    <label for="pin" class="block text-gray-700 mb-1 text-sm">New PIN</label>
                    <input type="number" id="pin" name="pin"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none">
                </div>
            <?php endif; ?>

            <button type="submit"
                    class="cursor-pointer w-full bg-red-900 text-white py-2 rounded-lg hover:bg-red-800">
                Save Security
            </button>
        </form>
        </div>



    </div>
  </div>
</main>

<?php 
include "../src/components/view/footer.php";
?>


<script src="../static/js/view/settings.js"></script>