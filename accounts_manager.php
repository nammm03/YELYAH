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
                <div id="accountsContent" class="hidden">
                    <?php include 'test.php'; ?>
                </div>

                <div id="billingContent" class="hidden">
                    <?php include 'billingForm.php'; ?>
                </div>

            </main>

        </div>
    </div>
    <!-- Accounts Manager Modal -->
    <div id="accountsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center animate-fadeInUp">
            <!-- Header -->
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Accounts Manager</h2>
            <p class="text-gray-500 mb-6">Choose what you'd like to manage:</p>

            <!-- Buttons -->
            <div class="flex justify-center gap-4">
                <button onclick="showSection('accounts'); closeAccountsModal();" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition">Accounts</button>
                <button onclick="showSection('billing'); closeAccountsModal();" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition">Billing Form</button>
            </div>
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

        function closeAccountsModal() {
            document.getElementById('accountsModal').classList.add('hidden');
        }

        function showSection(section) {
            const accounts = document.getElementById('accountsContent');
            const billing = document.getElementById('billingContent');

            accounts.classList.add('hidden');
            billing.classList.add('hidden');

            if (section === 'accounts') {
                accounts.classList.remove('hidden');
            } else if (section === 'billing') {
                billing.classList.remove('hidden');
            }

            // Store selected tab in URL hash
            window.location.hash = section;
        }


        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const shouldOpenModal = urlParams.get('openModal') === 'true';

            if (shouldOpenModal) {
                document.getElementById('accountsModal').classList.remove('hidden');

                // Remove ?openModal=true from URL without reloading
                const url = new URL(window.location);
                url.searchParams.delete('openModal');
                window.history.replaceState({}, document.title, url.toString());
            }

            if (window.location.hash) {
                const section = window.location.hash.replace('#', '');
                showSection(section);
            }
        };
    </script>
</body>