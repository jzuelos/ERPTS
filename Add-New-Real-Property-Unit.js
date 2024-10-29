alert("Loaded");

document.addEventListener('DOMContentLoaded', function () {
    // Attach the clearForm function to the clear button click event
    document.querySelector('.clear-button').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default anchor click behavior
        clearForm(); // Call the clearForm function
    });
});

// Function to clear all input fields and reset select elements
function clearForm() {
    // Clear input fields
    document.getElementById('house_number').value = '';
    document.getElementById('block_number').value = '';
    document.getElementById('house_tag_number').value = '';
    document.getElementById('land_area').value = '';
    document.getElementById('lot_no').value = '';
    document.getElementById('zone_no').value = '';
    document.getElementById('block_no').value = '';
    document.getElementById('psd').value = '';

    // Reset select elements
    document.getElementById('province').selectedIndex = 0;
    document.getElementById('city').selectedIndex = 0;
    document.getElementById('district').selectedIndex = 0;
    document.getElementById('barangay').selectedIndex = 0;

    // Uncheck all checkboxes
    document.querySelectorAll('input[name="documents[]"]').forEach(checkbox => checkbox.checked = false);
}

// Form validation function
function validateForm() {
    const checkboxes = document.querySelectorAll('input[name="documents[]"]');
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
        alert('Please select at least one document.');
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
