document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('propertyForm');

    // Main form submission
    mainForm.addEventListener('submit', function (event) {
        if (!validateDocumentsForm()) {
            event.preventDefault(); // Prevent form submission if validation fails
        }
    });
});

// Function to clear the owner search form
function clearOwnerSearchForm() {
    document.getElementById('owner_search').value = ''; // Clear the owner search input
}

// Function to clear the main property addition form
function clearMainForm() {
    // Clear input fields for the main form
    document.getElementById('house_number').value = '';
    document.getElementById('block_number').value = '';
    document.getElementById('house_tag_number').value = '';
    document.getElementById('land_area').value = '';
    document.getElementById('lot_no').value = '';
    document.getElementById('zone_no').value = '';

    // Reset select elements for the main form
    document.getElementById('province').selectedIndex = 0;
    document.getElementById('city').selectedIndex = 0;
    document.getElementById('district').selectedIndex = 0;
    document.getElementById('barangay').selectedIndex = 0;

    // Uncheck all checkboxes for the documents in the main form
    document.querySelectorAll('input[name="documents[]"]').forEach(checkbox => checkbox.checked = false);
}

// Form validation function for the documents form
function validateDocumentsForm() {
    const checkboxes = document.querySelectorAll('input[name="documents[]"]');
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked); // Check if any checkbox is checked

    if (!isChecked) {
        alert('Please select at least one document.'); // Alert user if no checkbox is checked
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
