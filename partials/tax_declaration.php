<section class="container mt-5" id="declaration-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tax Declaration of Property</h4>
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
            data-bs-target="#editDeclarationProperty" <?= $disableButton ?>>
            Edit
        </button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
        <form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="taxDeclarationNumber" class="form-label">
                        Identification Numbers (Tax Declaration Number)
                    </label>
                    <input type="text" class="form-control" id="taxDeclarationNumber"
                        placeholder="Enter Tax Declaration Number" maxlength="25"
                        value="<?= htmlspecialchars($rpu_declaration['arp_no'] ?? '') ?>" disabled>
                </div>

                <div class="col-12 mb-3">
                    <h6 class="mt-4 mb-3">Approval</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="provincialAssessor" class="form-label">Provincial Assessor</label>
                    <input type="text" class="form-control" id="provincialAssessor"
                        placeholder="Enter Provincial Assessor"
                        value="<?= htmlspecialchars($rpu_declaration['pro_assess'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="provincialDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="provincialDate"
                        value="<?= htmlspecialchars($rpu_declaration['pro_date'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="municipalAssessor" class="form-label">City/Municipal Assessor</label>
                    <input type="text" class="form-control" id="municipalAssessor"
                        placeholder="Enter City/Municipal Assessor"
                        value="<?= htmlspecialchars($rpu_declaration['mun_assess'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="municipalDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="municipalDate"
                        value="<?= htmlspecialchars($rpu_declaration['mun_date'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="cancelsTD" class="form-label">Cancels TD Number</label>
                    <input type="text" class="form-control" id="cancelsTD" placeholder="Enter Cancels TD Number"
                        value="<?= htmlspecialchars($rpu_declaration['td_cancel'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="previousPin" class="form-label">Previous Pin</label>
                    <input type="text" class="form-control" id="previousPin" placeholder="Enter Previous Pin"
                        value="<?= htmlspecialchars($rpu_declaration['previous_pin'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="taxYear" class="form-label">Tax Begin With Year</label>
                    <input type="date" class="form-control" id="taxYear"
                        value="<?= htmlspecialchars($rpu_declaration['tax_year'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="enteredInRPAREForBy" class="form-label">Entered in RPARE For By</label>
                    <input type="text" class="form-control" id="enteredInRPAREForBy" placeholder="Enter Value"
                        value="<?= htmlspecialchars($rpu_declaration['entered_by'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="enteredInRPAREForYear" class="form-label">Entered in RPARE For Year</label>
                    <input type="date" class="form-control" id="enteredInRPAREForYear"
                        value="<?= htmlspecialchars($rpu_declaration['entered_year'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="previousOwner" class="form-label">Previous Owner</label>
                    <input type="text" class="form-control" id="previousOwner" placeholder="Enter Previous Owner"
                        value="<?= htmlspecialchars($rpu_declaration['prev_own'] ?? '') ?>" disabled>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="previousAssessedValue" class="form-label">Previous Assessed Value</label>
                    <input type="text" class="form-control" id="previousAssessedValue"
                        placeholder="Enter Assessed Value"
                        value="₱<?= isset($rpu_declaration['prev_assess']) ? number_format($rpu_declaration['prev_assess'], 2) : '' ?>"
                        disabled>
                </div>
            </div>

            <div class="text-end mt-4">
                <?php
                $faas_id_for_print = $rpu_declaration['faas_id'] ?? null;
                $land_data = $faas_id_for_print ? fetchLandRecords($conn, $faas_id_for_print) : [];
                $noLand = empty($land_data);
                $printDisabled = $noLand ? 'disabled title="No land record found for this FAAS."' : '';
                ?>

                <!-- PRINT BUTTON -->
                <button type="button" class="btn btn-outline-primary btn-sm"
                    onclick="<?php if (!$noLand && isset($property_id)): ?>openPrintCertificationModal(<?= $property_id ?>)<?php endif; ?>"
                    <?= $printDisabled ?>>
                    <i class="bi bi-printer"></i> Print
                </button>

                <?php if ($noLand): ?>
                    <p class="text-danger small mt-2">
                        ⚠️ No land record linked to this FAAS.
                    </p>
                <?php endif; ?>
            </div>
        </form>
    </div>
</section>

<!-- Modal for Declaration of Property -->
<div class="modal fade" id="editDeclarationProperty" tabindex="-1" aria-labelledby="editDeclarationPropertyLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDeclarationPropertyLabel">Edit Declaration of Property</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="declarationForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="taxDeclarationNumberModal" class="form-label">
                                Identification Numbers (Tax Declaration Number)
                            </label>
                            <input type="text" class="form-control" id="taxDeclarationNumberModal" name="arp_no"
                                maxlength="25" placeholder="Enter Tax Declaration Number"
                                value="<?= htmlspecialchars($rpu_declaration['arp_no'] ?? '') ?>">
                        </div>

                        <div class="col-12 mb-3">
                            <h6 class="mt-4 mb-3">Approval</h6>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="provincialAssessorModal" class="form-label">Provincial Assessor</label>
                            <input type="text" class="form-control" id="provincialAssessorModal" name="pro_assess"
                                maxlength="20" placeholder="Enter Provincial Assessor"
                                value="<?= htmlspecialchars($rpu_declaration['pro_assess'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="provincialDateModal" class="form-label">Date</label>
                            <input type="date" class="form-control" id="provincialDateModal" name="pro_date"
                                value="<?= htmlspecialchars($rpu_declaration['pro_date'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="municipalAssessorModal" class="form-label">City/Municipal Assessor</label>
                            <input type="text" class="form-control" id="municipalAssessorModal" name="mun_assess"
                                maxlength="20" placeholder="Enter City/Municipal Assessor"
                                value="<?= htmlspecialchars($rpu_declaration['mun_assess'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="municipalDateModal" class="form-label">Date</label>
                            <input type="date" class="form-control" id="municipalDateModal" name="mun_date"
                                value="<?= htmlspecialchars($rpu_declaration['mun_date'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cancelsTDModal" class="form-label">Cancels TD Number</label>
                            <input type="text" class="form-control" id="cancelsTDModal" name="td_cancel" maxlength="20"
                                placeholder="Enter Cancels TD Number"
                                value="<?= htmlspecialchars($rpu_declaration['td_cancel'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="previousPinModal" class="form-label">Previous Pin</label>
                            <input type="text" class="form-control" id="previousPinModal" name="previous_pin"
                                maxlength="20" placeholder="Enter Previous Pin"
                                value="<?= htmlspecialchars($rpu_declaration['previous_pin'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="taxYearModal" class="form-label">Tax Begin With Year</label>
                            <input type="date" class="form-control" id="taxYearModal" name="tax_year"
                                value="<?= htmlspecialchars($rpu_declaration['tax_year'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="enteredInRPAREForByModal" class="form-label">Entered in RPARE For By</label>
                            <input type="text" class="form-control" id="enteredInRPAREForByModal" name="entered_by"
                                placeholder="Enter Value"
                                value="<?= htmlspecialchars($rpu_declaration['entered_by'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="enteredInRPAREForYearModal" class="form-label">Entered in RPARE For Year</label>
                            <input type="date" class="form-control" id="enteredInRPAREForYearModal" name="entered_year"
                                value="<?= htmlspecialchars($rpu_declaration['entered_year'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="previousOwnerModal" class="form-label">Previous Owner</label>
                            <input type="text" class="form-control" id="previousOwnerModal" name="prev_own"
                                maxlength="50" placeholder="Enter Previous Owner"
                                value="<?= htmlspecialchars($rpu_declaration['prev_own'] ?? '') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="previousAssessedValueModal" class="form-label">Previous Assessed Value</label>
                            <input type="text" class="form-control" id="previousAssessedValueModal" name="prev_assess"
                                maxlength="20" placeholder="Enter Assessed Value"
                                value="<?= htmlspecialchars($rpu_declaration['prev_assess'] ?? '') ?>">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
                <button type="submit" form="declarationForm" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Print Certification Modal -->
<div class="modal fade" id="printCertificationModal" tabindex="-1" aria-labelledby="printCertificationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="printCertificationModalLabel">
                    <i class="bi bi-printer"></i> Print Tax Declaration
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="printCertificationForm">
                <div class="modal-body">
                    <input type="hidden" name="property_id" id="printPropertyId">
                    <input type="hidden" name="faas_id" id="printFaasId" value="<?= $faas_id_for_print ?>">

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> You can optionally fill in certification details, or skip directly to printing.
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="ownerAdmin" class="form-label">
                                Owner/Administration <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" class="form-control" id="ownerAdmin" name="owner_admin"
                                placeholder="Enter owner or administrator name">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="certificationDate" class="form-label">
                                Date <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="date" class="form-control" id="certificationDate" name="certification_date"
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="certificationFee" class="form-label">
                                Certification Fee <span class="text-muted">(Optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" id="certificationFee" name="certification_fee"
                                    placeholder="0.00" step="0.01" min="0">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="orNumber" class="form-label">
                                OR Number <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="text" class="form-control" id="orNumber" name="or_number"
                                placeholder="Enter Official Receipt Number"
                                maxlength="7" style="text-transform: uppercase;">
                            <small id="orFeedback" class="text-danger"></small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="datePaid" class="form-label">
                                Date Paid <span class="text-muted">(Optional)</span>
                            </label>
                            <input type="date" class="form-control" id="datePaid" name="date_paid"
                                value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Note:</strong> If you fill in any certification details, they will be saved and printed with the tax declaration.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary" onclick="printDirectly()">
                        <i class="bi bi-printer"></i> Print Without Details
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save & Print
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ================================
    // Tax Declaration Number Formatter (GR-2023-II-03-015-03799)
    // ================================
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = ['taxDeclarationNumber', 'taxDeclarationNumberModal'];

        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (!input) return;

            const PATTERN = [2, 4, 2, 2, 3, 5];
            const MAX_LEN = PATTERN.reduce((a, b) => a + b, 0);

            function cleanValue(v) {
                return v.replace(/[^A-Za-z0-9]/g, '').toUpperCase().slice(0, MAX_LEN);
            }

            function formatTD(v) {
                const clean = cleanValue(v);
                const parts = [];
                let i = 0;
                for (const len of PATTERN) {
                    if (i >= clean.length) break;
                    parts.push(clean.substr(i, len));
                    i += len;
                }
                return parts.join('-');
            }

            input.value = formatTD(input.value);

            input.addEventListener('input', function() {
                const oldPos = input.selectionStart;
                const formatted = formatTD(input.value);
                if (formatted !== input.value) {
                    const before = input.value.slice(0, oldPos);
                    const beforeClean = before.replace(/[^A-Za-z0-9]/g, '');
                    const newPos = formatTD(beforeClean).length;
                    input.value = formatted;
                    input.setSelectionRange(newPos, newPos);
                }
            });

            input.addEventListener('paste', e => {
                e.preventDefault();
                const text = (e.clipboardData || window.clipboardData).getData('text');
                input.value = formatTD(text);
            });
        });

        // ================================
        // OR Number Live Validation (Uppercase, 7 chars, Duplicate Check)
        // ================================
        const orInput = document.getElementById('orNumber');
        const feedback = document.getElementById('orFeedback');
        const submitBtn = document.querySelector('#printCertificationForm button[type="submit"]');

        if (orInput) {
            orInput.addEventListener('input', function() {
                // Uppercase and restrict to A-Z and 0-9 only
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                // Limit to 7 characters
                if (this.value.length > 7) {
                    this.value = this.value.slice(0, 7);
                }

                // Only check for duplicates if OR number is provided
                if (this.value.length === 0) {
                    feedback.textContent = '';
                    this.classList.remove('is-invalid');
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }

                if (this.value.length < 3) {
                    feedback.textContent = '';
                    this.classList.remove('is-invalid');
                    if (submitBtn) submitBtn.disabled = false;
                    return;
                }

                // AJAX duplicate check
                fetch('FAAS.php?ajax=check_or_number&or=' + encodeURIComponent(this.value))
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            feedback.textContent = '⚠️ OR Number already exists!';
                            this.classList.add('is-invalid');
                            if (submitBtn) submitBtn.disabled = true;
                        } else {
                            feedback.textContent = '';
                            this.classList.remove('is-invalid');
                            if (submitBtn) submitBtn.disabled = false;
                        }
                    })
                    .catch(err => console.error('Error checking OR number:', err));
            });
        }
    });

    // ================================
    // Open Print Certification Modal
    // ================================
    function openPrintCertificationModal(propertyId) {
        document.getElementById('printPropertyId').value = propertyId;
        const modal = new bootstrap.Modal(document.getElementById('printCertificationModal'));
        modal.show();
    }

    // ================================
    // Print Directly Without Saving Certification Details
    // ================================
    function printDirectly() {
        const propertyId = document.getElementById('printPropertyId').value;
        const faasId = document.getElementById('printFaasId').value;
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('printCertificationModal'));
        modal.hide();
        
        // Open DRP without certification details
        window.open('DRP.php?p_id=' + propertyId, '_blank');
    }

    // ================================
    // Handle Form Submission (Save & Print)
    // ================================
    document.getElementById('printCertificationForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        // Check if any certification field is filled
        const hasData = formData.get('owner_admin') || 
                       formData.get('certification_fee') || 
                       formData.get('or_number');

        if (!hasData) {
            // No data provided, just print directly
            printDirectly();
            return;
        }

        // Save certification details and then print
        fetch('save_print_certification.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('printCertificationModal'));
                    modal.hide();

                    // Open DRP with OR number included if provided
                    let printUrl = 'DRP.php?p_id=' + data.property_id;
                    if (data.cert_id) {
                        printUrl += '&cert_id=' + data.cert_id;
                    }
                    if (data.or_number) {
                        printUrl += '&or_number=' + encodeURIComponent(data.or_number);
                    }
                    
                    window.open(printUrl, '_blank');

                    // Show success message
                    alert('Certification details saved successfully!');
                    
                    // Reset form
                    this.reset();
                    document.getElementById('certificationDate').value = '<?= date('Y-m-d') ?>';
                    document.getElementById('datePaid').value = '<?= date('Y-m-d') ?>';
                } else {
                    alert('Error: ' + (data.message || 'Failed to save certification details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving certification details');
            });
    });

    // ================================
    // Format Currency Input
    // ================================
    document.getElementById('certificationFee')?.addEventListener('blur', function() {
        if (this.value) {
            this.value = parseFloat(this.value).toFixed(2);
        }
    });
</script>