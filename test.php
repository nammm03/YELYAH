<?php
include('dbconnection.php');
// Fetch all accounts
$accountsQuery = "SELECT * FROM accounts";
$accountsResult = mysqli_query($conn, $accountsQuery);

// Prepare parent-child map
$accounts = [];
$childrenMap = [];

while ($row = mysqli_fetch_assoc($accountsResult)) {
  $accounts[] = $row;
  if (!empty($row['parent_id'])) {
    $childrenMap[$row['parent_id']][] = $row;
  }
}
$accountsPerPage = 5; // You can change this to show more/less
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $accountsPerPage;

// Get total parent accounts count
$totalParentsResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM accounts WHERE parent_id IS NULL");
$totalParentsRow = mysqli_fetch_assoc($totalParentsResult);
$totalParents = $totalParentsRow['count'];
$totalPages = ceil($totalParents / $accountsPerPage);

// Get parent accounts for current page
$parentsQuery = "SELECT * FROM accounts WHERE parent_id IS NULL LIMIT $offset, $accountsPerPage";
$parentsResult = mysqli_query($conn, $parentsQuery);
$parents = [];
while ($row = mysqli_fetch_assoc($parentsResult)) {
  $parents[] = $row;
}

// Get all children for these parent accounts
$parentIds = array_column($parents, 'id');
$childrenMap = [];

if (!empty($parentIds)) {
  $in = implode(',', array_map('intval', $parentIds));
  $childrenQuery = "SELECT * FROM accounts WHERE parent_id IN ($in)";
  $childrenResult = mysqli_query($conn, $childrenQuery);
  while ($child = mysqli_fetch_assoc($childrenResult)) {
    $childrenMap[$child['parent_id']][] = $child;
  }
}
?>

<body class="bg-gray-100 min-h-screen p-6">
  <div class="max-w-5xl mx-auto space-y-10">

    <!-- Header & Add Button -->
    <div class="flex justify-between items-center">
      <button onclick="document.getElementById('accountModal').classList.remove('hidden')"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        + Add Account
      </button>
    </div>

    <div id="accounts" class="bg-white p-6 rounded-lg shadow-lg w-full">

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-200 text-xs uppercase">
            <tr>
              <th class="px-4 py-3">Account Name / Sub-accounts</th>
            </tr>
          </thead>
          <!-- Back Button -->
          <div class="mb-4">
            <a href="accounts_manager.php?openModal=true"
              class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-800 transition">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
              </svg>
              Back
            </a>
          </div>
          <tbody>
            <?php foreach ($parents as $account): ?>

              <tr class="border-b group hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-sm font-medium text-gray-800">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                      <span class="text-lg font-semibold"><?= htmlspecialchars($account['name']); ?></span>
                      <?php if (isset($childrenMap[$account['id']])): ?>
                        <details class="group/details ml-2" id="details-<?= $account['id']; ?>" ontoggle="toggleDelete(<?= $account['id']; ?>)">
                          <summary class="cursor-pointer flex items-center text-blue-600 hover:text-blue-800 text-sm transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.584l3.71-4.354a.75.75 0 011.14.976l-4.25 5a.75.75 0 01-1.14 0l-4.25-5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                            <?= count($childrenMap[$account['id']]); ?> Sub-account(s)
                          </summary>
                          <ul class="ml-6 mt-2 space-y-1 text-gray-700 text-sm">
                            <?php foreach ($childrenMap[$account['id']] as $sub): ?>
                              <li class="flex justify-between items-center">
                                <span class="text-sm"><?= htmlspecialchars($sub['name']); ?></span>
                                <form action="delete_account.php" method="POST" onsubmit="return confirm('Delete this sub-account?');">
                                  <input type="hidden" name="account_id" value="<?= $sub['id']; ?>">
                                  <button type="submit" class="text-red-500 hover:text-red-700 text-sm mt-[16px] ml-[5px]">
                                    Delete
                                  </button>
                                </form>
                              </li>
                            <?php endforeach; ?>
                          </ul>

                        </details>
                      <?php endif; ?>
                    </div>
                    <!-- Delete button for parent -->
                    <form action="delete_account.php" method="POST" class="inline" onsubmit="return confirm('Delete this account?');">
                      <input type="hidden" name="account_id" value="<?= $account['id']; ?>">
                      <button type="submit" id="deleteBtn-<?= $account['id']; ?>" class="text-red-500 hover:text-red-700 text-sm">
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

            <?php endforeach; ?>
          </tbody>

        </table>

<!-- Simple Pagination -->
<div class="mt-6 flex justify-center items-center space-x-4 text-sm">
  <a href="?page=<?= max(1, $page - 1); ?>#accounts"
     class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 <?= $page <= 1 ? 'opacity-50 pointer-events-none' : '' ?>">
    Previous
  </a>

  <span class="text-gray-800 font-medium">
    Page <?= $page; ?> of <?= $totalPages; ?>
  </span>

  <a href="?page=<?= min($totalPages, $page + 1); ?>#accounts"
     class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 <?= $page >= $totalPages ? 'opacity-50 pointer-events-none' : '' ?>">
    Next
  </a>
</div>


      </div>
    </div>

  </div>

  <!-- Modal for Add Account -->
  <div id="accountModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative animate-fadeInUp">
      <button onclick="document.getElementById('accountModal').classList.add('hidden')"
        class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>

      <h2 class="text-xl font-bold mb-4 text-gray-800">Add Account</h2>

      <form action="add_account_process.php" method="POST" class="space-y-4">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">Account Name</label>
          <input type="text" name="name" id="name" required
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" />
        </div>

        <div>
          <label class="inline-flex items-center">
            <input type="checkbox" id="hasSub" name="hasSub" class="form-checkbox h-5 w-5 text-blue-600">
            <span class="ml-2 text-gray-700">Assign as Sub-account?</span>
          </label>
        </div>

        <div id="parentAccountDiv" class="hidden">
          <label for="parent_id" class="block text-sm font-medium text-gray-700">Select Parent Account</label>
          <select name="parent_id" id="parent_id"
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">-- Select Parent Account --</option>
            <?php foreach ($accounts as $account): ?>
              <?php if (empty($account['parent_id'])): ?>
                <option value="<?= $account['id']; ?>"><?= htmlspecialchars($account['name']); ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="pt-4">
          <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Create Account</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    document.getElementById('hasSub').addEventListener('change', function() {
      document.getElementById('parentAccountDiv').classList.toggle('hidden', !this.checked);
    });

    function toggleSubaccounts(id) {
      const row = document.getElementById(id);
      if (row) {
        row.classList.toggle('hidden');
      }
    }

    function toggleDelete(accountId) {
      const details = document.getElementById('details-' + accountId);
      const deleteBtn = document.getElementById('deleteBtn-' + accountId);
      if (details.open) {
        deleteBtn.classList.add('hidden');
      } else {
        deleteBtn.classList.remove('hidden');
      }
    }
  </script>
</body>

</html>