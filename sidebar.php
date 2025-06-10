<?php
session_start(); // this must be first

require 'dbconnection.php';
function isActive($pages)
{
    $current = basename($_SERVER['PHP_SELF']);
    if (is_array($pages)) {
        return in_array($current, $pages) ? 'bg-blue-800' : 'hover:bg-blue-700';
    } else {
        return $current === $pages ? 'bg-blue-800' : 'hover:bg-blue-700';
    }
}

$resourcePages = ['accounts_manager.php', 'fleet_manager.php', 'trip_manager.php', 'payroll_manager.php'];
$resourceActive = isActive($resourcePages);

// Default username
$username = 'Guest';

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($fetchedUsername);

    if ($stmt->fetch()) {
        $username = $fetchedUsername;
    }

    $stmt->close();
}
?>

<?php include 'header.php'; ?>

<div class="w-64 h-screen bg-[#24354a] text-white flex flex-col p-4">
    <!-- Logo / Title -->
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold">JRZ NEXUS</h1>
    </div>

    <!-- User Profile -->
    <div class="flex flex-col items-center mb-6">
        <div class="w-16 h-16 rounded-full bg-white text-blue-900 flex items-center justify-center mb-2">
            <i class="fas fa-user fa-lg"></i>
        </div>
        <div class="text-sm text-white"><?= htmlspecialchars($username) ?></div>
        <div class="text-xs text-gray-300">Lvl 5</div>
    </div>

    <!-- Section -->
    <div class="text-xs uppercase tracking-wide text-gray-300 border-b border-gray-500 pb-1 mb-3">General</div>

    <!-- Navigation -->
    <nav class="flex flex-col space-y-1 text-sm">
        <a href="dashboard.php" class="flex items-center space-x-2 px-3 py-2 rounded <?= isActive('dashboard.php') ?>">
            <i class="fas fa-chart-line w-5 text-white"></i>
            <span>Dashboard</span>
        </a>
        <a href="billing.php" class="flex items-center space-x-2 px-3 py-2 rounded <?= isActive('billing.php') ?>">
            <i class="fas fa-file-invoice-dollar w-5 text-white"></i>
            <span>Billing</span>
        </a>
        <a href="tracker.php" class="flex items-center space-x-2 px-3 py-2 rounded <?= isActive('tracker.php') ?>">
            <i class="fas fa-money-check-alt w-5 text-white"></i>
            <span>Financial Tracker</span>
        </a>
        <a href="payroll.php" class="flex items-center space-x-2 px-3 py-2 rounded <?= isActive('payroll.php') ?>">
            <i class="fas fa-wallet w-5 text-white"></i>
            <span>Payroll</span>
        </a>
        <!-- Resource Center Dropdown (Downward Toggle + isActive) -->
        <div class="relative list-none">
            <button onclick="toggleResourceDropdown()" class="w-full flex items-center space-x-2 px-3 py-2 rounded <?= $resourceActive ?: 'hover:bg-blue-700' ?> text-white focus:outline-none">
                <i class="fas fa-users w-5"></i>
                <span>Resource Center</span>
                <i class="fas fa-chevron-down ml-auto text-sm"></i>
            </button>

            <div id="resourceDropdown" class="hidden absolute left-0 mt-1 w-56 bg-[#3a4a5d] text-white border border-gray-600 rounded shadow-md z-50 list-none">

                <a href="accounts_manager.php?openModal=true" class="flex items-center space-x-2 px-4 py-2 hover:bg-blue-700 <?= isActive('accounts_manager.php') ?>">
                    <i class="fas fa-user-cog w-4"></i>
                    <span>Accounts Manager</span>
                </a>

                <a href="fleet_manager.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-blue-700 <?= isActive('fleet_manager.php') ?>">
                    <i class="fas fa-truck w-4"></i>
                    <span>Fleet Manager</span>
                </a>
                <a href="trip_manager.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-blue-700 <?= isActive('trip_manager.php')  ?>">
                    <i class="fas fa-route w-4"></i>
                    <span>Trip Manager</span>
                </a>
            </div>
        </div>

    </nav>
</div>

<script>
    function toggleResourceDropdown() {
        const dropdown = document.getElementById('resourceDropdown');
        dropdown.classList.toggle('hidden');
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function(e) {
        const button = document.querySelector('[onclick="toggleResourceDropdown()"]');
        const dropdown = document.getElementById('resourceDropdown');
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script>