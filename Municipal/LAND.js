document.addEventListener('DOMContentLoaded', function () {
    // === Fetch dropdown data ===
    fetch('LAND.php?fetch=1')
        .then(res => res.json())
        .then(data => {
            populateSelect('classification', data.classifications, window.landData?.classification);
            populateSelect('subClass', data.subclasses, window.landData?.subClass);
            populateSelect('actualUse', data.land_uses, window.landData?.actualUse);
            
            // Store the data globally for later use
            window.dropdownData = data;
        })
        .catch(error => console.error('Error fetching land data:', error));

    function populateSelect(selectId, items, selectedValue) {
        const select = document.getElementById(selectId);
        if (!select) return;

        select.innerHTML = '<option value="">Select an option</option>';
        items.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.text;
            
            // Store additional data as data attributes
            if (item.uv) option.setAttribute('data-uv', item.uv);
            if (item.al) option.setAttribute('data-al', item.al);

            if (selectedValue && selectedValue == item.id) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    // === Populate recommended unit value when sub-class changes ===
    const subClassSelect = document.getElementById('subClass');
    const recommendedUnitValueInput = document.getElementById('recommendedUnitValue');
    
    if (subClassSelect && recommendedUnitValueInput) {
        subClassSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const unitValue = selectedOption.getAttribute('data-uv') || '';
            recommendedUnitValueInput.value = unitValue;
        });
    }

    // === Populate recommended assessment level when actual use changes ===
    const actualUseSelect = document.getElementById('actualUse');
    const recommendedAssessmentLevelInput = document.getElementById('recommendedAssessmentLevel');
    
    if (actualUseSelect && recommendedAssessmentLevelInput) {
        actualUseSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const assessmentLevel = selectedOption.getAttribute('data-al') || '';
            recommendedAssessmentLevelInput.value = assessmentLevel;
        });
    }

    // === Auto-calculation elements ===
    const areaInput = document.getElementById("area");
    const sqmRadio = document.querySelector("input[name='areaUnit'][value='sqm']");
    const hectareRadio = document.querySelector("input[name='areaUnit'][value='hectare']");
    const unitValueInput = document.getElementById("unitValue");
    const marketValueInput = document.getElementById("marketValue");
    const valueAdjustmentInput = document.getElementById("valueAdjustment");
    const adjustedMarketValueInput = document.getElementById("adjustedMarketValue");
    const percentAdjustmentInput = document.getElementById("percentAdjustment");
    const assessmentLevelInput = document.getElementById("assessmentLevel");
    const assessedValueInput = document.getElementById("assessedValue");

    // === Helper: debounce ===
    function debounce(func, wait) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(func, wait);
        };
    }

    // === sqm/hectare conversion ===
    function convertArea() {
        let value = parseFloat(areaInput.value) || 0;
        if (sqmRadio && sqmRadio.checked) {
            areaInput.value = value.toFixed(2);
        } else if (hectareRadio && hectareRadio.checked) {
            areaInput.value = (value / 10000).toFixed(4);
        }
        calculateMarketValue();
    }

    // === Market value = area × unit value ===
    function calculateMarketValue() {
        const area = parseFloat(areaInput.value.replace(/,/g, "")) || 0;
        const unitValue = parseFloat(unitValueInput.value.replace(/,/g, "")) || 0;
        const areaSqm = hectareRadio && hectareRadio.checked ? area * 10000 : area;

        if (areaSqm > 0 && unitValue > 0) {
            const marketValue = areaSqm * unitValue;
            marketValueInput.value = marketValue.toFixed(2);
            calculateValueAdjustment(marketValue);
        } else {
            marketValueInput.value = "";
            valueAdjustmentInput.value = "";
            adjustedMarketValueInput.value = "";
            assessedValueInput.value = "";
        }
    }

    // === Value adjustment ===
    function calculateValueAdjustment(marketValue) {
        const percentAdjustment = parseFloat(percentAdjustmentInput.value) || 0;
        const valueAdjustment = marketValue * (percentAdjustment / 100 - 1);
        valueAdjustmentInput.value = valueAdjustment.toFixed(2);
        calculateAdjustedMarketValue(marketValue, valueAdjustment);
    }

    // === Adjusted market value ===
    function calculateAdjustedMarketValue(marketValue, valueAdjustment) {
        const adjustedMarketValue = marketValue + valueAdjustment;
        adjustedMarketValueInput.value = adjustedMarketValue.toFixed(2);
        calculateAssessedValue();
    }

    // === Assessed value ===
    function calculateAssessedValue() {
        const adjustedMarketValue = parseFloat(adjustedMarketValueInput.value.replace(/,/g, "")) || 0;
        const assessmentLevel = parseFloat(assessmentLevelInput.value) || 0;
        if (adjustedMarketValue > 0 && assessmentLevel > 0) {
            assessedValueInput.value = (adjustedMarketValue * (assessmentLevel / 100)).toFixed(2);
        } else {
            assessedValueInput.value = "";
        }
    }

    // === Event listeners ===
    if (sqmRadio) sqmRadio.addEventListener("change", convertArea);
    if (hectareRadio) hectareRadio.addEventListener("change", convertArea);
    if (areaInput) areaInput.addEventListener("input", debounce(calculateMarketValue, 300));
    if (unitValueInput) unitValueInput.addEventListener("input", debounce(calculateMarketValue, 300));
    if (percentAdjustmentInput) percentAdjustmentInput.addEventListener("input", () => {
        const mv = parseFloat(marketValueInput.value.replace(/,/g, "")) || 0;
        calculateValueAdjustment(mv);
    });
    if (assessmentLevelInput) assessmentLevelInput.addEventListener("input", calculateAssessedValue);

    // Run initial calculation if values exist
    calculateMarketValue();
});