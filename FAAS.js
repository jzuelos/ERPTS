
// Utility Functions

// Capitalize first letter of each word in a field
function capitalizeFirstLetter(element) {
  element.value = element.value.replace(/\b\w/g, char => char.toUpperCase());
}

// Restrict field to numeric input
function restrictToNumbers(element) {
  element.value = element.value.replace(/[^0-9]/g, '');
}

// Reset all forms inside modals
function resetForm() {
  const modals = document.querySelectorAll('.modal');
  modals.forEach(modal => {
    const forms = modal.querySelectorAll('form');
    forms.forEach(form => {
      form.reset();
      form.querySelectorAll("input, select, textarea").forEach(field => {
        if (["text", "textarea", "email", "date"].includes(field.type)) {
          field.value = "";
        } else if (["checkbox", "radio"].includes(field.type)) {
          field.checked = field.defaultChecked;
        } else if (field.tagName === "SELECT") {
          field.selectedIndex = 0;
        }
      });
    });
  });
}


// RPU Identification Section
let arpData = {};

function toggleEdit() {
  const editButton = document.getElementById('editRPUButton');
  const inputs = document.querySelectorAll('#rpu-identification-section input, #rpu-identification-section select');
  const isEditMode = editButton.textContent === 'Edit';

  if (isEditMode) {
    editButton.textContent = 'Save';
    inputs.forEach(input => input.disabled = false);
  } else {
    saveRPUData();
    editButton.textContent = 'Edit';
    inputs.forEach(input => input.disabled = true);
  }
}

function saveRPUData() {
  const propertyId = new URLSearchParams(window.location.search).get('id');
  const faasIdText = document.body.innerHTML.match(/Faas ID:\s*(\d+)/);
  const faasId = faasIdText ? faasIdText[1] : null;

  if (!faasId) {
    alert("Error: FAAS ID not found on the page.");
    return;
  }

  const arpNumber = document.getElementById('arpNumber').value;
  const propertyNumber = getPropertyNumberDigits();
  const taxability = document.getElementById('taxability').value;
  const effectivity = document.getElementById('effectivity').value;

  arpData = { faasId, arpNumber, propertyNumber, taxability, effectivity };

  console.log("Sending ARP Data:", arpData);

  fetch('FAASrpuID.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(arpData)
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Success');
      } else {
        alert(data.error || 'Failed to insert data.');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while inserting the data.');
    });
}

// Property Information Section
function showEditPropertyModal() {
  document.getElementById('streetModal').value = document.getElementById('street').value;
  document.getElementById('barangayModal').value = document.getElementById('barangay').value;
  document.getElementById('municipalityModal').value = document.getElementById('municipality').value;
  document.getElementById('provinceModal').value = document.getElementById('province').value;
  document.getElementById('houseNumberModal').value = document.getElementById('houseNumber').value;
  document.getElementById('landAreaModal').value = document.getElementById('landArea').value;

  const myModal = new bootstrap.Modal(document.getElementById('editPropertyModal'), { keyboard: false });
  myModal.show();
}

function savePropertyData() {
  const propertyId = document.getElementById('propertyIdModal').value;
  const street = document.getElementById('streetModal').value;
  const barangay = document.getElementById('barangayModal').value;
  const municipality = document.getElementById('municipalityModal').value;
  const province = document.getElementById('provinceModal').value;
  const houseNumber = document.getElementById('houseNumberModal').value;
  const landArea = document.getElementById('landAreaModal').value;

  console.log("Saving property with ID:", propertyId);

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "FAASupdate_property.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      console.log("Response:", xhr.responseText);
      alert("Property information updated successfully!");
      const myModal = bootstrap.Modal.getInstance(document.getElementById('editPropertyModal'));
      myModal.hide();
    }
  };

  xhr.send(
    "property_id=" + encodeURIComponent(propertyId) +
    "&street=" + encodeURIComponent(street) +
    "&barangay=" + encodeURIComponent(barangay) +
    "&municipality=" + encodeURIComponent(municipality) +
    "&province=" + encodeURIComponent(province) +
    "&houseNumber=" + encodeURIComponent(houseNumber) +
    "&landArea=" + encodeURIComponent(landArea)
  );
}

// Plants, Trees & Valuation
function showPnTModal() {
  document.getElementById('marketValueModal').value = document.getElementById('marketValue').value;
  document.getElementById('assessedValueModal').value = document.getElementById('assessedValue').value;
  const myModal = new bootstrap.Modal(document.getElementById('editPlantsTreesModal'), { keyboard: false });
  myModal.show();
}

function savePlantsTreesData() {
  document.getElementById('marketValue').value = document.getElementById('marketValueModal').value;
  document.getElementById('assessedValue').value = document.getElementById('assessedValueModal').value;
  bootstrap.Modal.getInstance(document.getElementById('editPlantsTreesModal')).hide();
}

function saveValuationData() {
  document.getElementById('landMarketValue').value = document.getElementById('landMarketValueModal').value;
  document.getElementById('landAssessedValue').value = document.getElementById('landAssessedValueModal').value;
  document.getElementById('plantsMarketValue').value = document.getElementById('plantsMarketValueModal').value;
  document.getElementById('plantsAssessedValue').value = document.getElementById('plantsAssessedValueModal').value;

  let landMarket = parseFloat(document.getElementById('landMarketValue').value.replace(/,/g, '')) || 0;
  let plantsMarket = parseFloat(document.getElementById('plantsMarketValue').value.replace(/,/g, '')) || 0;
  document.getElementById('totalMarketValue').value = (landMarket + plantsMarket).toLocaleString();

  let landAssessed = parseFloat(document.getElementById('landAssessedValue').value.replace(/,/g, '')) || 0;
  let plantsAssessed = parseFloat(document.getElementById('plantsAssessedValue').value.replace(/,/g, '')) || 0;
  document.getElementById('totalAssessedValue').value = (landAssessed + plantsAssessed).toLocaleString();

  bootstrap.Modal.getInstance(document.getElementById('editValuationModal')).hide();
}


// Land Modal
function handleLandModal() {
  const editLandModal = new bootstrap.Modal(document.getElementById('editLandModal'));

  const octTctNumber = document.getElementById('octTctNumber');
  const surveyNumber = document.getElementById('surveyNumber');
  const north = document.getElementById('north');
  const south = document.getElementById('south');
  const east = document.getElementById('east');
  const west = document.getElementById('west');
  const boundaryDescription = document.getElementById('boundaryDescriptionModal');
  const adminLastName = document.getElementById('adminLastName');
  const adminFirstName = document.getElementById('adminFirstName');
  const adminMiddleName = document.getElementById('adminMiddleName');
  const adminContact = document.getElementById('adminContact');
  const adminEmail = document.getElementById('adminEmail');
  const adminAddressNumber = document.getElementById('adminAddressNumber');
  const adminAddressStreet = document.getElementById('adminAddressStreet');
  const adminAddressBarangay = document.getElementById('adminAddressBarangay');
  const adminAddressDistrict = document.getElementById('adminAddressDistrict');
  const adminAddressMunicipality = document.getElementById('adminAddressMunicipality');
  const adminAddressProvince = document.getElementById('adminAddressProvince');

  const octTctNumberModal = document.getElementById('octTctNumberModal');
  const surveyNumberModal = document.getElementById('surveyNumberModal');
  const northModal = document.getElementById('northModal');
  const southModal = document.getElementById('southModal');
  const eastModal = document.getElementById('eastModal');
  const westModal = document.getElementById('westModal');
  const boundaryDescriptionModal = document.getElementById('boundaryDescriptionModal');
  const adminLastNameModal = document.getElementById('adminLastNameModal');
  const adminFirstNameModal = document.getElementById('adminFirstNameModal');
  const adminMiddleNameModal = document.getElementById('adminMiddleNameModal');
  const adminContactModal = document.getElementById('adminContactModal');
  const adminEmailModal = document.getElementById('adminEmailModal');
  const adminAddressNumberModal = document.getElementById('adminAddressNumberModal');
  const adminAddressStreetModal = document.getElementById('adminAddressStreetModal');
  const adminAddressBarangayModal = document.getElementById('adminAddressBarangayModal');
  const adminAddressDistrictModal = document.getElementById('adminAddressDistrictModal');
  const adminAddressMunicipalityModal = document.getElementById('adminAddressMunicipalityModal');
  const adminAddressProvinceModal = document.getElementById('adminAddressProvinceModal');

  function populateModal() {
    octTctNumberModal.value = octTctNumber.value;
    surveyNumberModal.value = surveyNumber.value;
    northModal.value = north.value;
    southModal.value = south.value;
    eastModal.value = east.value;
    westModal.value = west.value;
    boundaryDescriptionModal.value = boundaryDescription.value;
    adminLastNameModal.value = adminLastName.value;
    adminFirstNameModal.value = adminFirstName.value;
    adminMiddleNameModal.value = adminMiddleName.value;
    adminContactModal.value = adminContact.value;
    adminEmailModal.value = adminEmail.value;
    adminAddressNumberModal.value = adminAddressNumber.value;
    adminAddressStreetModal.value = adminAddressStreet.value;
    adminAddressBarangayModal.value = adminAddressBarangay.value;
    adminAddressDistrictModal.value = adminAddressDistrict.value;
    adminAddressMunicipalityModal.value = adminAddressMunicipality.value;
    adminAddressProvinceModal.value = adminAddressProvince.value;
  }

  function saveChanges() {
    octTctNumber.value = octTctNumberModal.value;
    surveyNumber.value = surveyNumberModal.value;
    north.value = northModal.value;
    south.value = southModal.value;
    east.value = eastModal.value;
    west.value = westModal.value;
    boundaryDescription.value = boundaryDescriptionModal.value;
    adminLastName.value = adminLastNameModal.value;
    adminFirstName.value = adminFirstNameModal.value;
    adminMiddleName.value = adminMiddleNameModal.value;
    adminContact.value = adminContactModal.value;
    adminEmail.value = adminEmailModal.value;
    adminAddressNumber.value = adminAddressNumberModal.value;
    adminAddressStreet.value = adminAddressStreetModal.value;
    adminAddressBarangay.value = adminAddressBarangayModal.value;
    adminAddressDistrict.value = adminAddressDistrictModal.value;
    adminAddressMunicipality.value = adminAddressMunicipalityModal.value;
    adminAddressProvince.value = adminAddressProvinceModal.value;
    editLandModal.hide();
  }

  document.querySelector('button[data-bs-target="#editLandModal"]').addEventListener('click', populateModal);
  document.querySelector('#editLandModal .modal-footer .btn-primary').addEventListener('click', saveChanges);
}

document.addEventListener("DOMContentLoaded", () => {
  // Input formatting listeners
  const fieldsToCapitalize = [
    'ownerName', 'firstName', 'middleName', 'lastName',
    'ownerNameModal', 'firstNameModal', 'middleNameModal', 'lastNameModal',
    'streetModal', 'barangayModal', 'municipalityModal', 'provinceModal'
  ];
  fieldsToCapitalize.forEach(fieldId => {
    const inputField = document.getElementById(fieldId);
    if (inputField) {
      inputField.addEventListener("input", () => capitalizeFirstLetter(inputField));
    }
  });

  const ardNumberField = document.getElementById("ardNumberModal");
  if (ardNumberField) {
    ardNumberField.addEventListener("input", () => restrictToNumbers(ardNumberField));
  }

  // Toggle land table display
  const toggle = document.getElementById("showToggle");
  const tableContainer = document.getElementById("landTableContainer");
  if (toggle && tableContainer) {
    toggle.addEventListener("change", () => {
      tableContainer.style.display = toggle.checked ? "block" : "none";
    });
  }

  handleLandModal();
});

//Eye Icon Hover
document.addEventListener("DOMContentLoaded", function () {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

