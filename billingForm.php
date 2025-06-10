<?php
include('dbconnection.php');

// Fetch all accounts
$accounts = [];
$result = mysqli_query($conn, "SELECT * FROM accounts");
while ($row = mysqli_fetch_assoc($result)) {
    $accounts[] = $row;
}

$tableExists = false;
$tableColumns = [];
$selectedAccount = '';
$sanitizedAccount = '';
$msg = '';

if (isset($_POST['save_structure'])) {
    $selectedAccount = $_POST['account_name'];
    $columns = $_POST['columns'];
    $sanitizedAccount = preg_replace('/[^a-zA-Z0-9_]/', '', $selectedAccount);
    $tableName = "billing_" . $sanitizedAccount;

    // Create folder if not exists
    $folderPath = __DIR__ . "/billing_folders_accounts/" . $sanitizedAccount;
    if (!is_dir($folderPath)) {
        mkdir($folderPath, 0755, true);
    }

    // Create billing table
    $query = "CREATE TABLE IF NOT EXISTS `$tableName` (
        billing_id INT AUTO_INCREMENT PRIMARY KEY,
        account_id INT,
        otrp_id INT,
        origin VARCHAR(255),
        destination VARCHAR(255),
        plate_number INT,
        FOREIGN KEY (account_id) REFERENCES accounts(id),
        FOREIGN KEY (otrp_id) REFERENCES otrp(id),
        FOREIGN KEY (plate_number) REFERENCES fleet(id)";

    foreach ($columns as $col) {
        $colName = preg_replace('/[^a-zA-Z0-9_]/', '', $col['name']);
        $colType = strtoupper($col['type']);
        if (in_array($colType, ['VARCHAR(255)', 'INT', 'DATE', 'DECIMAL(10,2)', 'TEXT'])) {
            $query .= ", `$colName` $colType";
        }
    }

    $query .= ")";
    mysqli_query($conn, $query);
    $msg = "Table created successfully!";
}

// Check if account selected via GET or POST
if (!empty($_POST['account_name']) || !empty($_GET['account'])) {
    $selectedAccount = $_POST['account_name'] ?? $_GET['account'];
    $sanitizedAccount = preg_replace('/[^a-zA-Z0-9_]/', '', $selectedAccount);
    $tableName = "billing_" . $sanitizedAccount;

    // Check if table exists
    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    $tableExistsResult = mysqli_query($conn, $checkTableQuery);

    if ($tableExistsResult && mysqli_num_rows($tableExistsResult) > 0) {
        $tableExists = true;

        // Get existing columns
        $columnResult = mysqli_query($conn, "SHOW COLUMNS FROM `$tableName`");
        while ($col = mysqli_fetch_assoc($columnResult)) {
            $tableColumns[] = $col['Field'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Billing Table Setup</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center"
    x-data="{ selectedName: '<?= $selectedAccount ?>', showModal: '<?= $selectedAccount ? 'false' : 'true' ?>' == 'true' }">

    <!-- Account Selector Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-60 z-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-xl font-bold text-center mb-4">Select an Account</h2>
            <ul class="divide-y">
                <?php foreach ($accounts as $acc): ?>
                <li>
                    <form method="GET">
                        <input type="hidden" name="account" value="<?= htmlspecialchars($acc['name']) ?>">
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-200 rounded transition">
                            <?= htmlspecialchars($acc['name']) ?>
                        </button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-2xl z-10">
        <h2 class="text-2xl font-bold mb-4">Billing Table Setup</h2>

        <?php if ($selectedAccount): ?>
        <p class="mb-4">Selected Account: <strong><?= htmlspecialchars($selectedAccount) ?></strong></p>

        <?php if ($msg): ?>
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= $msg ?></div>
        <?php endif; ?>

        <?php if ($tableExists): ?>
        <h3 class="text-lg font-semibold mb-4">Billing Table Preview</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-200 text-left">
                        <?php foreach ($tableColumns as $col): ?>
                        <th class="p-2 border"><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach ($tableColumns as $col): ?>
                        <td class="p-2 border text-gray-600 italic">Sample <?= htmlspecialchars($col) ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php else: ?>

        <form method="POST" x-data="{ columns: [{ name: '', type: 'VARCHAR(255)' }] }">
            <input type="hidden" name="account_name" value="<?= htmlspecialchars($selectedAccount) ?>">

            <template x-for="(col, index) in columns" :key="index">
                <div class="flex gap-4 mb-2">
                    <input type="text" :name="'columns['+index+'][name]'" x-model="col.name"
                        class="flex-1 p-2 border rounded" placeholder="Column Name" required>
                    <select :name="'columns['+index+'][type]'" x-model="col.type" class="p-2 border rounded">
                        <option value="VARCHAR(255)">Text</option>
                        <option value="INT">Number</option>
                        <option value="DECIMAL(10,2)">Decimal</option>
                        <option value="DATE">Date</option>
                        <option value="TEXT">Long Text</option>
                    </select>
                </div>
            </template>

            <button type="button" @click="columns.push({ name: '', type: 'VARCHAR(255)' })"
                class="mb-4 px-3 py-1 bg-green-500 text-white rounded">
                + Add Column
            </button>

            <button type="submit" name="save_structure"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Create Billing Table
            </button>
        </form>
        <?php endif; ?>
        <?php endif; ?>
    </div>

</body>

</html>