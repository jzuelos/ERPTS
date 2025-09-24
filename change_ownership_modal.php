<div class="modal fade" id="changeOwnershipModal" tabindex="-1" aria-labelledby="changeOwnershipModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="changeOwnershipForm" method="post" action="process_change_ownership.php">
                <!-- Hidden property id -->
                <input type="hidden" name="property_id" value="<?= (int)($property_id ?? 0) ?>">

                <!-- Step 1: Select Owners -->
                <div class="modal-step" id="step1">
                    <div class="modal-header">
                        <h5 class="modal-title">Step 1: Select Owner(s) to Replace</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered table-striped" id="step1Table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Owner ID</th>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($owners_details as $owner): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($owner['own_id']) ?></td>
                                        <td><?= htmlspecialchars(trim(($owner['first_name'] ?? '') . ' ' . ($owner['middle_name'] ?? '') . ' ' . ($owner['last_name'] ?? ''))) ?></td>
                                        <td>
                                            <?php
                                            $addressParts = [$owner['street'] ?? '', $owner['barangay'] ?? '', $owner['city'] ?? '', $owner['province'] ?? ''];
                                            $addressParts = array_filter($addressParts, fn($part) => trim($part) !== '');
                                            echo htmlspecialchars(implode(', ', $addressParts));
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input owner-checkbox"
                                                type="checkbox"
                                                name="owners_to_remove[]"
                                                value="<?= htmlspecialchars($owner['own_id']) ?>">

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    </div>
                </div>

                <!-- Step 2: Add New Owner Table -->
                <div class="modal-step d-none" id="step2">
                    <div class="modal-header">
                        <h5 class="modal-title">Step 2: Add New Owner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="searchStep2" class="form-control mb-2" placeholder="Search owner...">
                        <table class="table table-bordered table-striped" id="step2Table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Owner ID</th>
                                    <th>Full Name</th>
                                    <th>Address</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($all_owners as $owner): ?>
                                    <tr data-owner-id="<?= $owner['own_id'] ?>">
                                        <td><?= htmlspecialchars($owner['own_id']) ?></td>
                                        <td><?= htmlspecialchars(trim($owner['own_fname'] . ' ' . $owner['own_mname'] . ' ' . $owner['own_surname'])) ?></td>
                                        <td>
                                            <?php
                                            $addressParts = [
                                                $owner['street'] ?? '',
                                                $owner['barangay'] ?? '',
                                                $owner['city'] ?? '',
                                                $owner['province'] ?? ''
                                            ];
                                            $addressParts = array_filter($addressParts, fn($part) => trim($part) !== '');
                                            echo htmlspecialchars(implode(', ', $addressParts));
                                            ?>
                                        </td>
                                        <td style="display: flex; justify-content: center; align-items: center;">
                                            <input class="form-check-input owner-checkbox"
                                                type="checkbox"
                                                name="owners_to_add[]"
                                                value="<?= htmlspecialchars($owner['own_id']) ?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-between mt-2">
                            <button type="button" class="btn btn-secondary" id="prevPage">Previous</button>
                            <span id="pageInfo"></span>
                            <button type="button" class="btn btn-secondary" id="nextPage">Next</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="backBtn">Back</button>
                        <button type="submit" class="btn btn-success">Finish</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");
        const nextBtn = document.getElementById("nextBtn");
        const backBtn = document.getElementById("backBtn");

        const step2Table = document.getElementById("step2Table");
        const searchInput = document.getElementById("searchStep2");
        const prevPageBtn = document.getElementById("prevPage");
        const nextPageBtn = document.getElementById("nextPage");
        const pageInfo = document.getElementById("pageInfo");

        const rowsPerPage = 10;
        let currentPage = 1;
        let allRows = Array.from(step2Table.tBodies[0].rows);
        let excludedIds = [];

        function renderTable() {
            // Filter rows based on search query and excluded IDs
            const query = searchInput.value.toLowerCase();
            const filteredRows = allRows.filter(row => {
                const matchQuery = row.textContent.toLowerCase().includes(query);
                const notExcluded = !excludedIds.includes(row.dataset.ownerId);
                return matchQuery && notExcluded;
            });

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            allRows.forEach(row => row.style.display = 'none'); // hide all
            filteredRows.slice(start, end).forEach(row => row.style.display = ''); // show current page

            pageInfo.textContent = `Page ${currentPage} of ${Math.ceil(filteredRows.length / rowsPerPage) || 1}`;
            prevPageBtn.disabled = currentPage === 1;
            nextPageBtn.disabled = currentPage >= Math.ceil(filteredRows.length / rowsPerPage);
        }

        searchInput.addEventListener("input", () => {
            currentPage = 1;
            renderTable();
        });

        prevPageBtn.addEventListener("click", () => {
            currentPage--;
            renderTable();
        });
        nextPageBtn.addEventListener("click", () => {
            currentPage++;
            renderTable();
        });

        nextBtn.addEventListener("click", function() {
            // Collect selected owner IDs
            const selectedIds = Array.from(document.querySelectorAll('.owner-checkbox:checked'))
                .map(cb => cb.value);
            if (selectedIds.length === 0) {
                alert("Please select at least one owner to replace.");
                return;
            }
            if (!confirm("Do you want to proceed to Step 2?")) return;

            step1.classList.add("d-none");
            step2.classList.remove("d-none");

            // Store excluded IDs and reset to page 1
            excludedIds = selectedIds;
            currentPage = 1;
            renderTable();
        });

        backBtn.addEventListener("click", function() {
            step2.classList.add("d-none");
            step1.classList.remove("d-none");
            searchInput.value = '';
            currentPage = 1;
            renderTable();
        });

        renderTable();
    });
</script>