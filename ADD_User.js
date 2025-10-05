document.addEventListener("DOMContentLoaded", function () {
    const barangaySelect = document.getElementById("barangay");
    const districtSelect = document.getElementById("district");
    const municipalitySelect = document.getElementById("municipality");
    const hiddenDistrictInput = document.getElementById("hidden_district"); // hidden input for form submission

    // Disable dropdowns initially
    barangaySelect.disabled = true;
    districtSelect.disabled = true;

    // Load municipalities
    fetch("ADD_user_getLocation.php?type=municipalities")
        .then(res => res.json())
        .then(data => {
            municipalitySelect.innerHTML = '<option value="" disabled selected>Select Municipality</option>';
            data.forEach(m => {
                const option = document.createElement("option");
                option.value = m.m_id;
                option.textContent = m.m_description;
                municipalitySelect.appendChild(option);
            });
        })
        .catch(err => console.error("Error fetching municipalities:", err));

    // When municipality changes
    municipalitySelect.addEventListener("change", function () {
        const m_id = this.value;

        // Reset dependent dropdowns
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        barangaySelect.disabled = true;
        districtSelect.innerHTML = '<option value="" disabled selected>District</option>';
        districtSelect.disabled = true; // keep district always disabled
        hiddenDistrictInput.value = ""; // clear hidden field

        if (!m_id) return;

        // Load barangays for the selected municipality
        fetch("ADD_user_getLocation.php?type=barangays&m_id=" + m_id)
            .then(res => res.json())
            .then(data => {
                barangaySelect.disabled = false; // enable barangay when municipality selected
                data.forEach(b => {
                    const option = document.createElement("option");
                    option.value = b.brgy_id;
                    option.textContent = b.brgy_name;
                    barangaySelect.appendChild(option);
                });
            })
            .catch(err => console.error("Error fetching barangays:", err));

        // Load district for the selected municipality
        fetch("ADD_user_getLocation.php?type=district&m_id=" + m_id)
            .then(res => res.json())
            .then(data => {
                districtSelect.innerHTML = "";
                districtSelect.disabled = true; // keep it non-selectable
                if (data && data.district_id) {
                    const option = document.createElement("option");
                    option.value = data.district_id;
                    option.textContent = data.description;
                    option.selected = true;
                    districtSelect.appendChild(option);
                    hiddenDistrictInput.value = data.district_id; // ✅ Update hidden field
                } else {
                    const option = document.createElement("option");
                    option.textContent = "No District Found";
                    option.disabled = true;
                    option.selected = true;
                    districtSelect.appendChild(option);
                    hiddenDistrictInput.value = "";
                }
            })
            .catch(err => console.error("Error fetching district:", err));
    });

    // Capitalize name fields
    function capitalizeWords(input) {
        input.value = input.value.replace(/\b\w/g, char => char.toUpperCase());
    }

    ["lastname", "firstname", "middlename"].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", function () {
                capitalizeWords(this);
            });
        }
    });
});
