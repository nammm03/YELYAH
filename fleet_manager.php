<?php
require 'dbconnection.php';

// Add entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add entry
    if (isset($_POST['add'])) {
        $plate = $_POST['plate'];
        $type = $_POST['type'];
        $owner = $_POST['ownership'];
        $stmt = $conn->prepare("INSERT INTO fleet (plate_number, type, ownership) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $plate, $type, $owner);
        $stmt->execute();
        echo "<script>alert('Vehicle added successfully!'); window.location.href=window.location.href;</script>";
    }

    // Delete entry
    if (isset($_POST['delete'])) {
        $id = $_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM fleet WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo "<script>alert('Vehicle deleted successfully!'); window.location.href=window.location.href;</script>";
    }
}

// Search
$search = $_GET['search'] ?? '';
$result = $conn->query("SELECT * FROM fleet WHERE plate_number LIKE '%$search%'");
?>

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
                <h1 class="text-2xl font-bold mb-4">Fleet Manager</h1>
                <!-- Search bar -->
                <input type="text" id="searchInput" placeholder="Search..." class="mb-4 px-2 py-2 w-1/4 rounded border">


                <!-- Table -->
                <form method="post">
                    <table class="w-full table-auto bg-white shadow rounded">
                        <thead>
                            <tr class="bg-gray-200 text-center">
                                <th class="p-2">Plate Number</th>
                                <th class="p-2">Type</th>
                                <th class="p-2">Ownership</th>
                                <th class="p-2">Action</th>
                            </tr>
                        </thead>

                        <tbody id="fleetBody">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="p-2 text-center"><?= htmlspecialchars($row['plate_number']) ?></td>
                                    <td class="p-2 text-center"><?= htmlspecialchars($row['type']) ?></td>
                                    <td class="p-2 text-center"><?= htmlspecialchars($row['ownership']) ?></td>
                                    <td class="p-2 text-center">
                                        <button type="submit" name="delete" value="1" class="bg-red-500 text-white px-3 py-1 rounded"
                                            onclick="return confirm('Are you sure you want to delete this vehicle?');">
                                            Delete
                                        </button>
                                        <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </form>

                <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                    class="bg-green-600 text-white px-4 py-2 rounded mb-4">
                    + Add
                </button>

                <!-- Enhanced Add Vehicle Modal -->
                <div id="addModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm hidden">
                    <div class="bg-white p-6 rounded-2xl w-[90%] max-w-md shadow-2xl border-t-4 border-blue-600 relative animate-fadeInUp">
                        <h2 class="text-xl font-bold mb-4 text-center">Add New Vehicle</h2>

                        <form method="post">
                            <!-- Plate Number Input -->
                            <label class="block mb-1 font-medium">Plate Number</label>
                            <input type="text" name="plate" placeholder="Plate Number" class="w-full p-2 border mb-3 rounded" required>

                            <!-- Type Dropdown -->
                            <label class="block mb-1 font-medium">Type (Number of Wheels)</label>
                            <select name="type" required class="w-full p-2 border mb-3 rounded">
                                <option value="" disabled selected>Select Type</option>
                                <option value="4W">4W</option>
                                <option value="6W">6W</option>
                                <option value="10W">10W</option>
                                <option value="12W">12W</option>
                                <option value="18W">18W</option>
                            </select>

                            <!-- Ownership Dropdown -->
                            <label class="block mb-1 font-medium">Ownership</label>
                            <select name="ownership" required class="w-full p-2 border mb-4 rounded">
                                <option value="" disabled selected>Select Ownership</option>
                                <option value="Owned">Owned</option>
                                <option value="Rented">Rented</option>
                            </select>

                            <!-- Buttons -->
                            <div class="flex justify-end space-x-2">
                                <button type="submit" name="add"
                                    class="bg-blue-600 text-white px-4 py-2 rounded">
                                    Save
                                </button>
                                <button type="button"
                                    onclick="document.getElementById('addModal').classList.add('hidden')"
                                    class="bg-gray-300 px-4 py-2 rounded">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


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
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#fleetBody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let match = false;

                cells.forEach(cell => {
                    if (cell.innerText.toLowerCase().includes(query)) {
                        match = true;
                    }
                });

                row.style.display = match ? '' : 'none';
            });
        });
    </script>

</body>