document.addEventListener('DOMContentLoaded', function () {
    fetch('LAND.php?fetch=1')
        .then(res => res.json())
        .then(data => {
            populateSelect('classificationModal', data.classifications);
            populateSelect('subClassModal', data.subclasses);
            populateSelect('actualUseModal', data.land_uses);
        })
        .catch(error => console.error('Error fetching land data:', error));

    function populateSelect(selectId, items) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = '<option value="">Select an option</option>';
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.text;
            select.appendChild(option);
        });
    }
});
