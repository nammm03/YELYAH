<?php
include 'dbconnection.php';

$result = mysqli_query($conn, "
    SELECT otrp.*, accounts.name 
    FROM otrp 
    LEFT JOIN accounts ON otrp.account = accounts.id 
    ORDER BY accounts.name ASC
");

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
?>

<body class="h-screen overflow-hidden">

    <div class="flex h-full">

        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>

        <!-- Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-auto">

            <!-- Top Navbar -->
            <?php include 'topnav.php'; ?>

            <!-- Main Content -->
            <main class="p-6 flex-1 bg-gray-100 overflow-auto" x-data="tripTable()">
                <h1 class="text-2xl font-bold mb-4">Trip Manager</h1>

                <input type="text" placeholder="Search..." class="mb-4 px-2 py-2 w-1/4 rounded border" x-model="search">

                <form action="save_trip.php" method="POST">
                    <table class="table-auto border-collapse border border-black w-full text-sm text-center">
                        <thead>
                            <tr class="bg-gray-100 font-semibold">
                                <th colspan="5" class="border border-black px-2 py-1">Trip Database</th>
                                <th colspan="4" class="border border-black px-2 py-1">Payroll Database</th>
                            </tr>
                            <tr class="bg-gray-200">
                                <th class="border border-black px-2 py-1">Account</th>
                                <th class="border border-black px-2 py-1">Origin</th>
                                <th class="border border-black px-2 py-1">Destination</th>
                                <th class="border border-black px-2 py-1">Truck Size</th>
                                <th class="border border-black px-2 py-1">Rate</th>
                                <th class="border border-black px-2 py-1">Driver</th>
                                <th class="border border-black px-2 py-1">Helper 1</th>
                                <th class="border border-black px-2 py-1">Helper 2</th>
                                <th class="border border-black px-2 py-1">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tripBody">
                            <template x-for="(row, index) in paginatedRows()" :key="row.id">
                                <tr>
                                    <td class="border border-black p-2" x-text="row.name" :data-id="row.id" data-column="account" ondblclick="makeEditableAccountSelect(this)"></td>
                                    <td class="border border-black p-2" x-text="row.origin" :data-id="row.id" data-column="origin" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2" x-text="row.destination" :data-id="row.id" data-column="destination" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2" :data-id="row.id" data-column="truck_size" ondblclick="makeEditableSelect(this, ['4w', '6w', '8w', '10w', '12w'])" x-text="row.truck_size"></td>
                                    <td class="border border-black p-2" x-text="row.rate" :data-id="row.id" data-column="rate" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2" x-text="row.driver" :data-id="row.id" data-column="driver" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2" x-text="row.helper1" :data-id="row.id" data-column="helper1" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2" x-text="row.helper2" :data-id="row.id" data-column="helper2" ondblclick="makeEditable(this)"></td>
                                    <td class="border border-black p-2">
                                        <div x-data="{ show: false }" class="relative inline-block text-center"
                                        :class="{ 'ring-2 ring-red-400 rounded': show }">
                                            <button @click="show = !show" type="button" class="text-red-600 hover:underline">Delete</button>
                                            <div x-show="show" 
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-x-2"
                                            x-transition:enter-end="opacity-100 translate-x-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-x-0"
                                            x-transition:leave-end="opacity-0 -translate-x-2"
                                            @click.outside="show = false"
                                            class="absolute top-1/2 -translate-y-1/2 right-full mr-3 top-0 z-10 mt-2 w-48 bg-white border border-gray-300 shadow-lg rounded p-2">
                                            <div class="absolute top-1/2 left-full -translate-y-1/2">
                                                <div class="w-3 h-3 bg-white border-t border-l border-gray-300 rotate-45 shadow-sm "></div>
                                            </div>
                                            <p class="mb-2 text-sm">Are you sure?</p>
                                            <button type="button"
                                            @click="deleteTrip(row.id)"
                                            class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">Confirm
                                        </button>
                                    </div>
                                </div>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center mt-4">
                        <div>
                            <button type="button" onclick="addBlankRow()" class="bg-green-600 text-white px-4 py-2 rounded">
                                + Add Row
                            </button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2">
                                Save All
                            </button>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="flex items-center space-x-2">
                            <button class="px-3 py-1 bg-gray-300 rounded" @click="prevPage" :disabled="currentPage === 1">Previous</button>
                            <span>Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span></span>
                            <button class="px-3 py-1 bg-gray-300 rounded" @click="nextPage" :disabled="currentPage === totalPages">Next</button>
                        </div>
                    </div>
                </form>
            </main>

        </div>
    </div>

    <script>
        let accountSelectHTML = '';

        // Fetch account options from external PHP file
        fetch('get_account.php')
            .then(response => response.text())
            .then(data => {
                accountSelectHTML = `<select name="account[]" class="w-full text-center border-none">${data}</select>`;
            })
            .catch(error => {
                console.error('Failed to fetch account options:', error);
            });

        function tripTable() {
            return {
                rows: <?php echo json_encode($rows); ?>,
                search: '',
                perPage: 10,
                currentPage: 1,

                get filteredRows() {
                    if (!this.search.trim()) return this.rows;
                    const term = this.search.toLowerCase();
                    return this.rows.filter(row =>
                        Object.values(row).some(value =>
                            String(value).toLowerCase().includes(term)
                        )
                    );
                },

                get totalPages() {
                    return Math.ceil(this.filteredRows.length / this.perPage) || 1;
                },

                paginatedRows() {
                    const start = (this.currentPage - 1) * this.perPage;
                    return this.filteredRows.slice(start, start + this.perPage);
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },

                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

        deleteTrip(id) {
      // Find the index in the array
      const index = this.rows.findIndex(r => r.id === id);
      if (index !== -1) {
        // Remove from frontend data
        this.rows.splice(index, 1);
      }

      // Optional: delete from backend via API call
      fetch('delete_trip.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id })
      }).then(res => res.json())
        .then(response => {
          if (!response.success) {
            alert("Backend delete failed!");
            // You could re-fetch the data if necessary
          }
        });
}

    };
}


        function addBlankRow() {
            if (!accountSelectHTML) {
                alert("Please wait... Account options are loading.");
                return;
            }

            const tbody = document.getElementById("tripBody");
            const row = document.createElement("tr");
            row.innerHTML = `
            <td class="border border-black p-2">
                <input type="hidden" name="id[]" value="">
                ${accountSelectHTML}
            </td>
            <td class="border border-black p-2"><input type="text" name="origin[]" class="w-full text-center border-none"></td>
            <td class="border border-black p-2"><textarea name="destination[]" class="w-full text-center border-none resize-none"></textarea></td>
            <td class="border border-black p-2">
                <select name="truck_size[]" class="w-full text-center border-none">
                    <option value="4w">4w</option>
                    <option value="6w">6w</option>
                    <option value="10W">10W</option>
                    <option value="12w">12w</option>
                </select>
            </td>
            <td class="border border-black p-2"><input type="number" name="rate[]" class="w-full text-center border-none" min="0"></td>
            <td class="border border-black p-2"><input type="number" name="driver[]" class="w-full text-center border-none" min="0"></td>
            <td class="border border-black p-2"><input type="number" name="helper1[]" class="w-full text-center border-none" min="0"></td>
            <td class="border border-black p-2"><input type="number" name="helper2[]" class="w-full text-center border-none" min="0"></td>
        `;
            tbody.appendChild(row);
        }

        // function makeEditableAccountSelect(cell) {
        //     const currentValue = cell.innerText.trim();
        //     const rowId = cell.dataset.id;
        //     const column = cell.dataset.column;

        //     // Store original HTML in case user cancels or we need to restore
        //     const originalContent = cell.innerHTML;

        //     // Create a temporary container to insert the fetched select
        //     const tempDiv = document.createElement("div");

        //     fetch('get_account.php')
        //         .then(response => response.text())
        //         .then(options => {
        //             tempDiv.innerHTML = `<select class="w-full text-center border-none">${options}</select>`;
        //             const select = tempDiv.firstChild;

        //             // Set current value as selected if it matches
        //             for (let opt of select.options) {
        //                 if (opt.textContent.trim() === currentValue) {
        //                     opt.selected = true;
        //                     break;
        //                 }
        //             }

        //             select.addEventListener('change', () => {
        //                 const newValue = select.options[select.selectedIndex].textContent;

        //                 // Update in database
        //                 fetch('update_trip.php', {
        //                     method: 'POST',
        //                     headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        //                     body: `id=${encodeURIComponent(rowId)}&column=${encodeURIComponent(column)}&value=${encodeURIComponent(newValue)}`
        //                 });

        //                 cell.textContent = newValue;
        //             });

        //             select.addEventListener('blur', () => {
        //                 const newValue = select.options[select.selectedIndex].textContent;
        //                 cell.textContent = newValue;
        //             });

        //             cell.innerHTML = '';
        //             cell.appendChild(select);
        //             select.focus();
        //         })
        //         .catch(err => {
        //             console.error('Error loading account options:', err);
        //             cell.innerHTML = originalContent;
        //         });
        // }
        function makeEditable(cell) {
            if (cell.querySelector('input')) return;

            const oldValue = cell.innerText.trim();
            const id = cell.getAttribute('data-id');
            const column = cell.getAttribute('data-column');

            const input = document.createElement('input');
            input.type = 'text';
            input.value = oldValue;
            input.className = "w-full text-center";

            cell.innerHTML = '';
            cell.appendChild(input);
            input.focus();

            input.addEventListener('blur', () => saveChange(cell, input.value, oldValue, id, column));
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    input.blur();
                }
            });
        }

        function saveChange(cell, newValue, oldValue, id, column) {
            if (newValue === oldValue) {
                cell.innerText = oldValue;
                return;
            }

            fetch('update_trip.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${encodeURIComponent(id)}&column=${encodeURIComponent(column)}&value=${encodeURIComponent(newValue)}`
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to update");
                    }
                    return response.text();
                })
                .then(data => {
                    console.log(data);
                    cell.innerText = newValue;
                })
                .catch(error => {
                    alert("Error updating value");
                    console.error(error);
                    cell.innerText = oldValue;
                });
        }

        function makeEditableSelect(td, options) {
            const id = td.getAttribute('data-id');
            const column = td.getAttribute('data-column');
            const currentValue = td.innerText;

            const select = document.createElement('select');
            select.className = 'border p-1';
            select.setAttribute('data-id', id);
            select.setAttribute('data-column', column);

            options.forEach(opt => {
                const option = document.createElement('option');
                option.value = opt;
                option.text = opt;
                if (opt === currentValue) option.selected = true;
                select.appendChild(option);
            });

            select.addEventListener('blur', () => {
                const newValue = select.value;

                // Save to database
                fetch('update_trip.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            id: id,
                            column: column,
                            value: newValue
                        })
                    })
                    .then(response => {
                        return response.text().then(text => {
                            if (!response.ok) {
                                throw new Error(text); // this lets you see the error content
                            }
                            console.log('Success:', text);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error updating value: ' + error.message);
                    });

            });


            td.innerText = '';
            td.appendChild(select);
            select.focus();
        }
    </script>
</body>