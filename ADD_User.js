document.addEventListener("DOMContentLoaded", function () {
    const barangaySelect = document.getElementById("barangay");
    const districtSelect = document.getElementById("district");
    const municipalitySelect = document.getElementById("municipality");

    // Disable barangay until municipality is selected
    barangaySelect.disabled = true;

    // Lock district dropdown (always auto-filled)
    districtSelect.disabled = true;

    // Load municipalities only
    fetch("ADD_user_getLocation.php?type=municipalities")
        .then(res => res.json())
        .then(data => {
            municipalitySelect.innerHTML = '<option value="" disabled selected>Select Municipality</option>';
            data.forEach(m => {
                let option = document.createElement("option");
                option.value = m.m_id;
                option.textContent = m.m_description;
                municipalitySelect.appendChild(option);
            });
        })
        .catch(err => console.error("Error fetching municipalities:", err));

    // When municipality changes
    municipalitySelect.addEventListener("change", function () {
        const m_id = this.value;

        // Load barangays for the selected municipality
        fetch("ADD_user_getLocation.php?type=barangays&m_id=" + m_id)
            .then(res => res.json())
            .then(data => {
                barangaySelect.disabled = false;
                barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
                data.forEach(b => {
                    let option = document.createElement("option");
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
                if (data) {
                    let option = document.createElement("option");
                    option.value = data.district_id;
                    option.textContent = data.description;
                    option.selected = true;
                    districtSelect.appendChild(option);
                }
            })
            .catch(err => console.error("Error fetching district:", err));
    });
});

// Capitalize words for name inputs
document.addEventListener("DOMContentLoaded", function () {
    function capitalizeWords(input) {
        input.value = input.value.replace(/\b\w/g, char => char.toUpperCase());
    }

    ["lastname", "firstname", "middlename"].forEach(id => {
        let input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", function () {
                capitalizeWords(this);
            });
        }
    });
});
