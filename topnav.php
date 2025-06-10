<?php include 'header.php'; ?>

<!-- Top Navbar -->
<div class="w-full bg-white shadow-md px-6 py-3 flex items-center justify-end gap-6 z-10">

    <!-- Account Settings Dropdown -->
    <div class="relative inline-block text-left">
        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-blue-900 font-semibold focus:outline-none">
            <i class="fas fa-user-circle text-xl"></i>
            <span><?= htmlspecialchars($username) ?></span>
            <i class="fas fa-chevron-down text-sm"></i>
        </button>

        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
            <a href="change_name.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Account Name</a>
            <a href="change_password.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Change Password</a>
            <!-- With this -->
            <button onclick="openLogoutModal()" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
        </div>
    </div>
    <!-- Logo Image -->
    <img src="assets/TRULINK.png" alt="Logo" class="h-14 w-auto object-contain">
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 flex items-center justify-center bg-black/40 backdrop-blur-sm z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center animate-fadeInUp">
        <!-- Icon -->
        <div class="mb-4">
            <svg class="mx-auto h-12 w-12 text-red-500" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-10V5m0 10a7 7 0 10-14 0 7 7 0 0014 0z" />
            </svg>
        </div>

        <!-- Text -->
        <h2 class="text-xl font-semibold text-gray-800 mb-2">Confirm Logout</h2>
        <p class="text-gray-500 mb-6">Are you sure you want to logout from your account?</p>

        <!-- Buttons -->
        <div class="flex justify-center gap-4">
            <a href="logout.php"
                class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition">Logout</a>
            <button onclick="closeLogoutModal()"
                class="px-5 py-2.5 bg-gray-100 text-gray-800 font-medium rounded-xl hover:bg-gray-200 transition">Cancel</button>
        </div>
    </div>
</div>


<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    }

    function openLogoutModal() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }

    function closeLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

    // Optional: Close the dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdownButton = document.querySelector('[onclick="toggleDropdown()"]');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdownButton && dropdownMenu && !dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>