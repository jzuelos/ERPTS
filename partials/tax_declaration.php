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
                // Use $property_id instead of $p_id
                $faas_id_for_print = $rpu_declaration['faas_id'] ?? null;
                $land_data = $faas_id_for_print ? fetchLandRecords($conn, $faas_id_for_print) : [];
                $noLand = empty($land_data);
                $printDisabled = $noLand ? 'disabled title="No land record found for this FAAS."' : '';
                ?>

                <button type="button" class="btn btn-outline-primary btn-sm"
                    onclick="<?php if (!$noLand && isset($property_id)): ?>window.open('DRP.php?p_id=<?= urlencode($property_id) ?>', '_blank')<?php endif; ?>"
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

<script>
    // Tax Declaration Number Formatter (GR-2023-II-03-015-03799)
    document.addEventListener("DOMContentLoaded", function () {
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

            input.addEventListener('input', function () {
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
    });
</script>