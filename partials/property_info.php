<section class="container my-5" id="property-info-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="section-title">Property Information</h4>
        <button type="button" class="btn btn-outline-primary btn-sm" 
                onclick="showEditPropertyModal()" <?= $disableButton ?>>
            Edit
        </button>
    </div>
    
    <div class="card border-0 shadow p-4 rounded-3">
        <form id="property-info">
            <div class="row">
                <input type="hidden" id="propertyIdModal" 
                       value="<?= isset($property['p_id']) ? htmlspecialchars($property['p_id']) : '' ?>">
                
                <div class="col-md-6 mb-4">
                    <label for="street" class="form-label">Street</label>
                    <input type="text" class="form-control" id="street" 
                           value="<?= isset($property['street']) ? htmlspecialchars($property['street']) : '' ?>" 
                           placeholder="Enter Street" disabled>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label for="barangay" class="form-label">Barangay</label>
                    <input type="text" class="form-control" id="barangay" 
                           value="<?= isset($property['barangay']) ? htmlspecialchars($property['barangay']) : '' ?>" 
                           placeholder="Enter Barangay" disabled>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label for="municipality" class="form-label">Municipality</label>
                    <input type="text" class="form-control" id="municipality" 
                           value="<?= isset($property['city']) ? htmlspecialchars($property['city']) : '' ?>" 
                           placeholder="Enter Municipality" disabled>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label for="province" class="form-label">Province</label>
                    <input type="text" class="form-control" id="province" 
                           value="<?= isset($property['province']) ? htmlspecialchars($property['province']) : '' ?>" 
                           placeholder="Enter Province" disabled>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label for="houseNumber" class="form-label">House Number</label>
                    <input type="text" class="form-control" id="houseNumber" 
                           value="<?= isset($property['house_no']) ? htmlspecialchars($property['house_no']) : '' ?>" 
                           placeholder="Enter House Number" disabled>
                </div>
                
                <div class="col-md-6 mb-4">
                    <label for="landArea" class="form-label">Land Area</label>
                    <input type="text" class="form-control" id="landArea" 
                           value="<?= isset($property['land_area']) ? htmlspecialchars($property['land_area']) : '' ?>" 
                           placeholder="Enter Land Area" disabled>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Modal for Property Information -->
<div class="modal fade" id="editPropertyModal" tabindex="-1" aria-labelledby="editPropertyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPropertyModalLabel">Edit Property Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPropertyForm">
                    <input type="hidden" id="propertyIdModal">
                    
                    <div class="mb-3">
                        <label for="streetModal" class="form-label">Street</label>
                        <input type="text" class="form-control" id="streetModal" 
                               placeholder="Enter Street" maxlength="30">
                    </div>
                    
                    <div class="mb-3">
                        <label for="barangayModal" class="form-label">Barangay</label>
                        <input type="text" class="form-control" id="barangayModal" 
                               placeholder="Enter Barangay" maxlength="20">
                    </div>
                    
                    <div class="mb-3">
                        <label for="municipalityModal" class="form-label">Municipality</label>
                        <input type="text" class="form-control" id="municipalityModal" 
                               placeholder="Enter Municipality" maxlength="20">
                    </div>
                    
                    <div class="mb-3">
                        <label for="provinceModal" class="form-label">Province</label>
                        <input type="text" class="form-control" id="provinceModal" 
                               placeholder="Enter Province" maxlength="20">
                    </div>
                    
                    <div class="mb-3">
                        <label for="houseNumberModal" class="form-label">House Number</label>
                        <input type="text" class="form-control" id="houseNumberModal" 
                               placeholder="Enter House Number" maxlength="10">
                    </div>
                    
                    <div class="mb-3">
                        <label for="landAreaModal" class="form-label">Land Area</label>
                        <input type="text" class="form-control" id="landAreaModal" 
                               placeholder="Enter Land Area" maxlength="20">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="reset" class="btn btn-warning" onclick="resetForm()">Reset</button>
                <button type="button" class="btn btn-primary" onclick="savePropertyData()">Save changes</button>
            </div>
        </div>
    </div>
</div>