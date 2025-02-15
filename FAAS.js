// Function to show the modal for editing Owner's Information
function showOISModal() {
  // Populate modal fields with current values if necessary
  document.getElementById('ownerNameModal').value = document.getElementById('ownerName').value;
  document.getElementById('firstNameModal').value = document.getElementById('firstName').value;
  document.getElementById('middleNameModal').value = document.getElementById('middleName').value;
  document.getElementById('lastNameModal').value = document.getElementById('lastName').value;

  // Enable the form fields inside the modal
  document.getElementById('ownerNameModal').disabled = false;
  document.getElementById('firstNameModal').disabled = false;
  document.getElementById('middleNameModal').disabled = false;
  document.getElementById('lastNameModal').disabled = false;

  // Show the modal
  var myModal = new bootstrap.Modal(document.getElementById('editOwnerModal'), {
    keyboard: false
  });
  myModal.show();
}

function saveOwnerData() {
  // Retrieve data from the modal form
  var ownerName = document.getElementById('ownerNameModal').value;
  var firstName = document.getElementById('firstNameModal').value;
  var middleName = document.getElementById('middleNameModal').value;
  var lastName = document.getElementById('lastNameModal').value;

  // Process the data (e.g., send it via AJAX or form submission)
  var ownerData = {
      owner_name: ownerName,
      first_name: firstName,
      middle_name: middleName,
      last_name: lastName
  };

  // For demonstration, log the data
  console.log(ownerData);
  // You would send this data to the server (e.g., via AJAX)
}

function addOwnerData() {
    // Get the data from the form fields
    var ownerName = document.getElementById('ownerNameModal').value;
    var firstName = document.getElementById('firstNameModal').value;
    var middleName = document.getElementById('middleNameModal').value;
    var lastName = document.getElementById('lastNameModal').value;

    // Create a new row in the table
    var table = document.querySelector('#editOwnerModal table tbody');
    var newRow = table.insertRow();
    
    // Insert the new owner's data into the new row
    newRow.innerHTML = `
        <td class="text-center">New ID</td>
        <td class="text-center"><input type="checkbox" name="owner_selection[]" value="New ID"></td>
        <td class="text-center">${ownerName}</td>
        <td class="text-center">${firstName} ${middleName} ${lastName}</td>
    `;

    // Optionally, reset the form fields after adding the owner
    document.getElementById('editOwnerForm').reset();
}

// Function to show the modal for editing Property Information
function showEditPropertyModal() {
  // Populate modal fields with current values if necessary
  document.getElementById('streetModal').value = document.getElementById('street').value;
  document.getElementById('barangayModal').value = document.getElementById('barangay').value;
  document.getElementById('municipalityModal').value = document.getElementById('municipality').value;
  document.getElementById('provinceModal').value = document.getElementById('province').value;
  document.getElementById('houseNumberModal').value = document.getElementById('houseNumber').value;
  document.getElementById('landAreaModal').value = document.getElementById('landArea').value;
  document.getElementById('zoneNumberModal').value = document.getElementById('zoneNumber').value;

  // Show the modal
  var myModal = new bootstrap.Modal(document.getElementById('editPropertyModal'), {
    keyboard: false
  });
  myModal.show();
}

// Function to save the Property Information data from the modal
function savePropertyData() {
  // Get data from modal fields
  var propertyId = document.getElementById('propertyIdModal').value;
  var street = document.getElementById('streetModal').value;
  var barangay = document.getElementById('barangayModal').value;
  var municipality = document.getElementById('municipalityModal').value;
  var province = document.getElementById('provinceModal').value;
  var houseNumber = document.getElementById('houseNumberModal').value;
  var landArea = document.getElementById('landAreaModal').value;
  var zoneNumber = document.getElementById('zoneNumberModal').value;

  // Log to check if values are being captured
  console.log("Saving property with ID:", propertyId);

  // Create an AJAX request
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "FAASupdate_property.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Handle response
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      console.log("Response:", xhr.responseText); // Debugging
      alert("Property information updated successfully!");
      var myModal = bootstrap.Modal.getInstance(document.getElementById('editPropertyModal'));
      myModal.hide();
    }
  };

  // Send data to PHP script
  xhr.send("property_id=" + encodeURIComponent(propertyId) +
           "&street=" + encodeURIComponent(street) +
           "&barangay=" + encodeURIComponent(barangay) +
           "&municipality=" + encodeURIComponent(municipality) +
           "&province=" + encodeURIComponent(province) +
           "&houseNumber=" + encodeURIComponent(houseNumber) +
           "&landArea=" + encodeURIComponent(landArea) +
           "&zoneNumber=" + encodeURIComponent(zoneNumber));
}

//Function to show Plant and Trees modal
function showPnTModal() {
  console.log("Edit button clicked!"); // Debugging message

  // Populate modal fields with current values from the main form
  document.getElementById('marketValueModal').value = document.getElementById('marketValue').value;
  document.getElementById('assessedValueModal').value = document.getElementById('assessedValue').value;

  // Show the modal
  var myModal = new bootstrap.Modal(document.getElementById('editPlantsTreesModal'), {
    keyboard: false
  });
  myModal.show();
}

function savePlantsTreesData() {
  console.log("Saving data!"); // Debugging message

  // Get data from modal fields and save back to the main form
  document.getElementById('marketValue').value = document.getElementById('marketValueModal').value;
  document.getElementById('assessedValue').value = document.getElementById('assessedValueModal').value;

  // Hide the modal after saving
  var myModalElement = document.getElementById('editPlantsTreesModal');
  var myModal = bootstrap.Modal.getInstance(myModalElement);  // Get the modal instance
  myModal.hide();  // Close the modal
}

function showEditValuationModal() {
  console.log("Edit button clicked!"); // Debugging message

  // Populate modal fields with current values from the main form
  document.getElementById('landMarketValueModal').value = document.getElementById('landMarketValue').value;
  document.getElementById('landAssessedValueModal').value = document.getElementById('landAssessedValue').value;
  document.getElementById('plantsMarketValueModal').value = document.getElementById('plantsMarketValue').value;
  document.getElementById('plantsAssessedValueModal').value = document.getElementById('plantsAssessedValue').value;

  // Show the modal
  var myModal = new bootstrap.Modal(document.getElementById('editValuationModal'), {
    keyboard: false
  });
  myModal.show();
}

function saveValuationData() {
  console.log("Saving data!"); // Debugging message

  // Get data from modal fields and save back to the main form
  document.getElementById('landMarketValue').value = document.getElementById('landMarketValueModal').value;
  document.getElementById('landAssessedValue').value = document.getElementById('landAssessedValueModal').value;
  document.getElementById('plantsMarketValue').value = document.getElementById('plantsMarketValueModal').value;
  document.getElementById('plantsAssessedValue').value = document.getElementById('plantsAssessedValueModal').value;

  // Calculate the Total Values (for Market Value and Assessed Value)
  let landMarket = parseFloat(document.getElementById('landMarketValue').value.replace(/,/g, '')) || 0;
  let plantsMarket = parseFloat(document.getElementById('plantsMarketValue').value.replace(/,/g, '')) || 0;
  let totalMarketValue = landMarket + plantsMarket;
  document.getElementById('totalMarketValue').value = totalMarketValue.toLocaleString();

  let landAssessed = parseFloat(document.getElementById('landAssessedValue').value.replace(/,/g, '')) || 0;
  let plantsAssessed = parseFloat(document.getElementById('plantsAssessedValue').value.replace(/,/g, '')) || 0;
  let totalAssessedValue = landAssessed + plantsAssessed;
  document.getElementById('totalAssessedValue').value = totalAssessedValue.toLocaleString();

  // Hide the modal after saving
  var myModalElement = document.getElementById('editValuationModal');
  var myModal = bootstrap.Modal.getInstance(myModalElement);  // Get the modal instance
  myModal.hide();  // Close the modal
}


function handleLandModal() {
  // Get references to modal elements
  const editLandModal = new bootstrap.Modal(document.getElementById('editLandModal'));

  // Get references to form fields
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

  // Get references to modal fields
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

  // Function to populate the modal with current data from the form
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

  // Function to save changes from the modal back to the main form
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

    // Close the modal after saving changes
    editLandModal.hide();
  }

  // Attach the populateModal function to the edit button (before opening modal)
  document.querySelector('button[data-bs-target="#editLandModal"]').addEventListener('click', populateModal);

  // Attach the saveChanges function to the Save button inside the modal
  const saveButton = document.querySelector('.modal-footer .btn-primary');
  saveButton.addEventListener('click', saveChanges);
}

// Initialize the handleLandModal function
handleLandModal();