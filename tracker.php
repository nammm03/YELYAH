<body class="h-screen overflow-hidden">

  <div class="flex h-full">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- content wrapper -->
    <div class="flex-1 flex flex-col overflow-auto">

      <!-- Top Navbar -->
      <?php include 'topnav.php'; ?>

      <!-- Main content -->
      <main class="p-6 flex-1 bg-gray-100 overflow-auto">
        <h1 class="text-2xl font-bold mb-4">Financial Traker</h1>
        <!-- Your dashboard content here -->
      </main>

    </div>
  </div>

  <!-- Dropdown toggle script -->
  <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    }

    // Optional: close dropdown if clicked outside
    document.addEventListener('click', function(e) {
        const button = document.querySelector('button[onclick="toggleDropdown()"]');
        const dropdown = document.getElementById('dropdownMenu');
        if (!button.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });
  </script>
</body>