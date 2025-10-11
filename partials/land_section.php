<section class="container my-5" id="land-section">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="section-title">LAND</h4>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
        <!-- Quick Actions -->
        <div class="row mb-4">
            <?php $p_id = isset($_GET['id']) ? htmlspecialchars($_GET['id']) : null; ?>
            <div class="col-md-6 mb-3">
                <a href="<?= ($is_active == 1) ? "Land.php?p_id=$p_id" : '#' ?>"
                   class="btn w-100 py-2 text-white text-decoration-none <?= ($is_active == 0) ? 'disabled' : '' ?>"
                   style="background-color: #379777; border-color: #2e8266; pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                    <i class="fas fa-plus-circle me-2"></i>Add Land
                </a>
            </div>
        </div>

        <!-- Toggle Section -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-light rounded">
            <span class="fw-bold me-3">Show/Hide</span>
            <div class="form-check form-switch m-0">
                <input class="form-check-input" type="checkbox" id="showToggle" checked style="margin-left: 0;">
            </div>
        </div>

        <!-- Land Records Table -->
        <div class="table-responsive" id="landTableContainer">
            <table class="table table-borderless text-center">
                <thead class="border-bottom border-2">
                    <tr>
                        <th class="bold" style="width: 10%;">OCT/TCT Number</th>
                        <th class="bold">Area (sq m)</th>
                        <th class="bold">Market Value</th>
                        <th class="bold">Assessed Value</th>
                        <th class="bold" style="width: 10%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($landRecords)): ?>
                        <?php foreach ($landRecords as $record): ?>
                            <tr class="border-bottom border-3">
                                <td><?= htmlspecialchars($record['oct_no']) ?></td>
                                <td><?= htmlspecialchars($record['area']) ?></td>
                                <td>₱<?= isset($record['market_value']) ? number_format($record['market_value'], 2) : '0.00' ?></td>
                                <td>₱<?= isset($record['assess_value']) ? number_format($record['assess_value'], 2) : '0.00' ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Edit -->
                                        <a href="LAND_Edit.php?p_id=<?= urlencode($p_id) ?>&land_id=<?= urlencode($record['land_id']) ?>"
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <!-- Print -->
                                        <a href="<?= ($is_active == 1) 
                                                ? 'print-layout.php?p_id=' . urlencode($p_id) . '&land_id=' . urlencode($record['land_id']) 
                                                : '#' ?>"
                                           class="btn btn-sm btn-secondary <?= ($is_active == 0) ? 'disabled' : '' ?>"
                                           title="Print" target="_blank"
                                           style="pointer-events: <?= ($is_active == 0) ? 'none' : 'auto' ?>;">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                        
                                        <!-- View All -->
                                        <a href="ViewAll.php?p_id=<?= urlencode($p_id) ?>"
                                           class="btn btn-sm btn-info" title="View All">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <!-- Delete -->
                                        <a href="#" class="btn btn-sm btn-danger" title="Delete"
                                           data-bs-toggle="modal" data-bs-target="#deleteModal-<?= $record['land_id'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal for each record -->
                            <div class="modal fade" id="deleteModal-<?= $record['land_id'] ?>" 
                                 tabindex="-1" aria-labelledby="deleteModalLabel-<?= $record['land_id'] ?>" 
                                 aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title" id="deleteModalLabel-<?= $record['land_id'] ?>">
                                                Confirm Deletion
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" 
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p>Are you sure you want to delete this land record?</p>
                                            <p class="fw-bold mb-0"><?= htmlspecialchars($record['oct_no']) ?></p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary" 
                                                    data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="delete_land_id" 
                                                       value="<?= htmlspecialchars($record['land_id']) ?>">
                                                <input type="hidden" name="p_id" 
                                                       value="<?= htmlspecialchars($p_id) ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No land records found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>