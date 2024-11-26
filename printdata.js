function populateAndPrint() {
    // Retrieve and populate owner information
    const fullName = `${localStorage.getItem('firstName') || ''} ${localStorage.getItem('middleName') || ''} ${localStorage.getItem('lastName') || ''}`.trim();
    document.getElementById('print-ownerName').textContent = fullName;

    // Retrieve and populate property information
    const fields = [
        'propertyId', 'street', 'barangay', 'municipality', 'province', 
        'houseNumber', 'landArea', 'zoneNumber', 'ardNumber', 
        'taxability', 'effectivity', 'octTctNumber', 'surveyNumber',
        'northBoundary', 'southBoundary', 'eastBoundary', 'westBoundary', 
        'boundaryDescription', 'adminLastName', 'adminFirstName', 
        'adminMiddleName', 'adminContact', 'adminEmail', 
        'adminAddressNumber', 'adminAddressStreet', 'adminAddressBarangay', 
        'adminAddressDistrict', 'adminAddressMunicipality', 
        'adminAddressProvince', 'description', 'classification', 
        'subClass', 'area', 'actualUse', 'unitValue', 'marketValue', 
        'adjustmentFactor', 'percentageAdjustment', 'valueAdjustment', 
        'adjustedMarketValue', 'assessmentLevel', 'assessedValue', 
        'verifiedBy', 'verifiedDate', 'plottingBy', 'plottingDate', 
        'notedBy', 'notedDate', 'appraisedBy', 'appraisedDate', 
        'recommendingApproval', 'recommendingDate', 'approvedBy', 
        'approvedDate', 'idle', 'contested', 'landMarketValue', 
        'landAssessedValue', 'plantsMarketValue', 'plantsAssessedValue', 
        'totalMarketValue', 'totalAssessedValue', 'previousOwner', 
        'previousAssessedValue', 'previousPin', 'presentPin', 'postingPin', 
        'previousArpNo', 'presentArpNo', 'postingArpNo', 
        'previousRollPageNo', 'presentRollPageNo', 'postingRollPageNo'
    ];

    fields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate value adjustment factors
    const adjustmentFields = [
        'marketValue', 'adjustmentFactors', 'percentageAdjustment', 'valueAdjustment', 'adjustedMarketValue'
    ];
    adjustmentFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate value adjustment summary
    const adjustmentSummaryFields = [
        'kind', 'actualUseSummary', 'adjustedMarketValueSummary', 'assessmentLevel', 'assessedValue'
    ];
    adjustmentSummaryFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate land appraisal summary
    const appraisalFields = [
        'classification', 'subClass', 'area', 'actualUse', 'unitValue', 'marketValue', 'totalMarketValue'
    ];
    appraisalFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate plants and trees appraisal summary
    const plantsFields = [
        'productClass', 'areaPlanted', 'totalNumber', 'nonFruitBearing', 
        'fruitBearing', 'age', 'unitPrice', 'marketValue', 'totalMarketValue'
    ];
    plantsFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate property location
    const locationFields = [
        'street', 'barangay', 'municipality', 'province', 
        'north', 'south', 'east', 'west', 'boundaryDescription'
    ];
    locationFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        if (element) {
            element.textContent = localStorage.getItem(field) || '';
        }
    });

    // Retrieve and populate reference and posting summary
    const referenceFields = [
        'previousPin', 'presentPin', 'postingPin',
        'previousArpNo', 'presentArpNo', 'postingArpNo',
        'previousRollPageNo', 'presentRollPageNo', 'postingRollPageNo'
    ];
    referenceFields.forEach(field => {
        const element = document.getElementById(`print-${field}`);
        const value = (localStorage.getItem(field) || '').trim();
        if (element) {
            element.textContent = value;
        }
    });

    // Automatically print after populating the fields
    setTimeout(() => {
        window.print();
    }, 500); // Adjust delay if needed
}

// Call the function once the document is ready
document.addEventListener('DOMContentLoaded', populateAndPrint);
