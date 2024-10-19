document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.clear-button').addEventListener('click', function (event) {
        event.preventDefault(); // Prevent default anchor click behavior
        document.getElementById('house_number').value = ''; // Clear input 1
        document.getElementById('block_number').value = ''; // Clear input 2
        document.getElementById('province').selectedIndex = 0;
        document.getElementById('city').selectedIndex = 0;
        document.getElementById('district').selectedIndex = 0;
        document.getElementById('barangay').selectedIndex = 0;
        document.getElementById('cb_affidavit').checked = false;
        document.getElementById('cb_barangay').checked = false;
        document.getElementById('cb_tag').checked = false;
        document.getElementById('lot_no').value = '';
        document.getElementById('zone_no').value = '';
        document.getElementById('block_no').value = '';
        document.getElementById('psd').value = '';
    });
});
