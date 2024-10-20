document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.clear-button').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default anchor click behavior
        document.getElementById('house_number').value = ''; // Clear input 1
        document.getElementById('block_number').value = ''; // Clear input 2
        document.getElementById('province').selectedIndex = 0;
        document.getElementById('city').selectedIndex = 0;
        document.getElementById('district').selectedIndex = 0;
        document.getElementById('barangay').selectedIndex = 0;
        document.getElementById('house_tag_number').value = '';
        document.getElementById('land_area').value = '';
        document.getElementById('lot_no').value = '';
        document.getElementById('zone_no').value = '';
        document.getElementById('block_no').value = '';
        document.getElementById('psd').value = '';
        document.getElementById('cb_affidavit').checked = false;
        document.getElementById('cb_barangay').checked = false;
        document.getElementById('cb_tag').checked = false;
    });
});

function validateForm() {
    const checkboxes = document.querySelectorAll('input[name="documents[]"]');
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

    if (!isChecked) {
      alert('Please select at least one document.');
      return false; // Prevent form submission
    }
    return true; // Allow form submission
  }