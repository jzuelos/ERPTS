<section class="container mt-4" id="owner-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <a href="Real-Property-Unit-List.php" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <h4 class="ms-3 mb-0">Owner's Information</h4>
        </div>
    </div>

    <?php
    if (!empty($property_id)) {
        $no_declaration = empty($rpu_declaration);
        
        if ($is_active == 0): ?>
            <span class="btn btn-outline-secondary disabled" title="This property is already inactive.">
                <i class="fas fa-ban"></i> RPU Cancelled
            </span>
        <?php elseif ($no_declaration): ?>
            <form method="post" onsubmit="return confirm('Disable this property? This will mark the property inactive.');" class="d-inline">
                <input type="hidden" name="action" value="disable_property">
                <input type="hidden" name="p_id" value="<?= htmlspecialchars($property_id) ?>">
                <input type="hidden" name="return_p_id" value="<?= htmlspecialchars($property_id) ?>">
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-ban"></i> Cancel RPU (Disable Property)
                </button>
            </form>
        <?php else: ?>
            <span class="btn btn-secondary disabled" title="Cannot disable: tax declaration exists for this property">
                <i class="fas fa-ban"></i> Cannot cancel RPU with TD encoded
            </span>
        <?php endif;
    }
    ?>

    <div class="card border-0 shadow p-4 rounded-3 mt-3">
        <div id="owner-info" class="row">
            <?php if (empty($owners_details)): ?>
                <div class="col-md-12 mb-4">
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No owner assigned to this property
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-12 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <h6 class="mb-0">Property Owners (<?= count($owners_details) ?>)</h6>
                        <div class="d-flex align-items-center ms-auto gap-2">
                            <?php $hasRPUWithTax = !empty($rpu_declaration); ?>
                            <div class="text-end">
                                <?php if (!$hasRPUWithTax): ?>
                                    <small class="text-muted d-block mb-1">
                                        No tax declaration; ownership change disabled.
                                    </small>
                                <?php endif; ?>
                                <button type="button" class="btn btn-dark btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#changeOwnershipModal" 
                                        data-property-id="<?= htmlspecialchars($property_id) ?>"
                                        <?= $hasRPUWithTax ? '' : 'disabled' ?>>
                                    Change Ownership
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php include __DIR__ . '/../change_ownership_modal.php'; ?>
                </div>

                <?php foreach ($owners_details as $owner): ?>
                    <div class="owner-item mb-3 p-3 bg-light rounded" 
                         data-owner-id="<?= (int) $owner['own_id'] ?>"
                         data-owner-type="<?= htmlspecialchars($owner['owner_type'] ?? 'individual', ENT_QUOTES) ?>"
                         data-company="<?= htmlspecialchars($owner['company_name'] ?? '', ENT_QUOTES) ?>"
                         data-first="<?= htmlspecialchars($owner['first_name'] ?? '', ENT_QUOTES) ?>"
                         data-middle="<?= htmlspecialchars($owner['middle_name'] ?? '', ENT_QUOTES) ?>"
                         data-last="<?= htmlspecialchars($owner['last_name'] ?? '', ENT_QUOTES) ?>">

                        <div class="d-flex justify-content-between align-items-start">
                            <div class="owner-details flex-grow-1">
                                <?php if (($owner['owner_type'] ?? 'individual') === 'company'): ?>
                                    <div class="mb-2">
                                        <span class="badge bg-primary me-2">Company</span>
                                        <strong><?= htmlspecialchars($owner['display_name']) ?></strong>
                                    </div>
                                    <?php if (!empty($owner['first_name']) || !empty($owner['last_name'])): ?>
                                        <div class="text-muted small">
                                            Contact: <?= htmlspecialchars(trim(($owner['first_name'] ?? '') . ' ' . ($owner['middle_name'] ?? '') . ' ' . ($owner['last_name'] ?? ''))) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="mb-2">
                                        <span class="badge bg-info me-2">Individual</span>
                                        <strong><?= htmlspecialchars($owner['display_name']) ?></strong>
                                    </div>
                                    <div class="row text-muted small">
                                        <div class="col-md-4">First: <?= htmlspecialchars($owner['first_name'] ?? '') ?></div>
                                        <div class="col-md-4">Middle: <?= htmlspecialchars($owner['middle_name'] ?? '') ?></div>
                                        <div class="col-md-4">Last: <?= htmlspecialchars($owner['last_name'] ?? '') ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="ms-3 position-relative">
                                <i class="bi bi-eye text-secondary" style="cursor: pointer; font-size: 1.3rem;"
                                   onmouseover="this.nextElementSibling.style.opacity='1'; this.nextElementSibling.style.visibility='visible';"
                                   onmouseout="this.nextElementSibling.style.opacity='0'; this.nextElementSibling.style.visibility='hidden';"></i>
                                
                                <div style="position: absolute; top: -10px; left: 130%; transform: translateY(-50%);
                                            background-color: #fff; border: 1px solid #ddd; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                            padding: 10px 12px; border-radius: 8px; font-size: 0.85rem; color: #333;
                                            width: 220px; z-index: 10; opacity: 0; visibility: hidden;
                                            transition: opacity 0.2s ease-in-out;">
                                    <div><strong>Province:</strong> <?= htmlspecialchars($owner['province'] ?? 'N/A') ?></div>
                                    <div><strong>City:</strong> <?= htmlspecialchars($owner['city'] ?? 'N/A') ?></div>
                                    <div><strong>Barangay:</strong> <?= htmlspecialchars($owner['barangay'] ?? 'N/A') ?></div>
                                    <div><strong>Street:</strong> <?= htmlspecialchars($owner['street'] ?? 'N/A') ?></div>
                                    <div><strong>Info:</strong> <?= htmlspecialchars($owner['own_info'] ?? 'N/A') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>