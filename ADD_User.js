// Fetch data and populate dropdowns
document.addEventListener("DOMContentLoaded", function() {
    fetch('ADD_user_getLocation.php')
        .then(response => response.json())
        .then(data => {
            const barangaySelect = document.getElementById('barangay');
            const districtSelect = document.getElementById('district');
            const municipalitySelect = document.getElementById('municipality');

            // Populate Barangay dropdown
            data.barangays.forEach(brgy => {
                let option = document.createElement('option');
                option.value = brgy.brgy_id;
                option.textContent = brgy.brgy_name;
                barangaySelect.appendChild(option);
            });

            // Populate District dropdown
            data.districts.forEach(district => {
                let option = document.createElement('option');
                option.value = district.district_id;
                option.textContent = district.description;
                districtSelect.appendChild(option);
            });

            // Populate Municipality dropdown
            data.municipalities.forEach(municipality => {
                let option = document.createElement('option');
                option.value = municipality.m_id;
                option.textContent = municipality.m_description;
                municipalitySelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching location data:', error));
});

document.addEventListener("DOMContentLoaded", function () {
    function capitalizeWords(input) {
        input.value = input.value.replace(/\b\w/g, char => char.toUpperCase());
    }

    document.getElementById("lastname").addEventListener("input", function () {
        capitalizeWords(this);
    });

    document.getElementById("firstname").addEventListener("input", function () {
        capitalizeWords(this);
    });

    document.getElementById("middlename").addEventListener("input", function () {
        capitalizeWords(this);
    });
});