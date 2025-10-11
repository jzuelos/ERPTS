<section class="container mt-5" id="rpu-identification-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">RPU Identification Numbers</h4>
        <button type="button" class="btn btn-outline-primary btn-sm" 
                id="editRPUButton" onclick="toggleEdit()" <?= $disableButton ?>>
            Edit
        </button>
    </div>

    <div class="card border-0 shadow p-4 rounded-3">
        <form>
            <div class="row">
                <!-- ARP Number -->
                <div class="col-md-6 mb-3">
                    <label for="arpNumber" class="form-label">ARP Number</label>
                    <input type="text" class="form-control" id="arpNumber" 
                           placeholder="Enter ARP Number" maxlength="20"
                           value="<?= isset($rpu_details['arp']) ? htmlspecialchars($rpu_details['arp']) : '' ?>" 
                           disabled>
                </div>

                <!-- Property Number (PIN) -->
                <div class="col-md-6 mb-3">
                    <label for="propertyNumber" class="form-label">Property Number</label>
                    <input type="text" class="form-control" id="propertyNumber" 
                           placeholder="Enter Property Number" maxlength="17"
                           value="<?= isset($rpu_details['pin']) ? htmlspecialchars($rpu_details['pin']) : '' ?>" 
                           disabled>
                </div>

                <!-- Taxability -->
                <div class="col-md-6 mb-3">
                    <label for="taxability" class="form-label">Taxability</label>
                    <select class="form-control" id="taxability" disabled>
                        <option value="" disabled <?= empty($rpu_details['taxability']) ? 'selected' : '' ?>>
                            Select Taxability
                        </option>
                        <option value="taxable" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'taxable') ? 'selected' : '' ?>>
                            Taxable
                        </option>
                        <option value="exempt" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'exempt') ? 'selected' : '' ?>>
                            Exempt
                        </option>
                        <option value="special" <?= (isset($rpu_details['taxability']) && $rpu_details['taxability'] === 'special') ? 'selected' : '' ?>>
                            Special
                        </option>
                    </select>
                </div>

                <!-- Effectivity Year -->
                <div class="col-md-6 mb-3">
                    <label for="effectivity" class="form-label">Effectivity (Year)</label>
                    <input type="number" class="form-control" id="effectivity" 
                           min="1900" max="2100" step="1" placeholder="Enter Effectivity Year"
                           value="<?= isset($rpu_details['effectivity']) ? htmlspecialchars($rpu_details['effectivity']) : '' ?>" 
                           disabled>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
// ARP Number Formatter (1997-1004-1795-101)
(function() {
    const input = document.getElementById('arpNumber');
    if (!input) return;

    const MAX = 15;
    const PATTERN = [4, 4, 4, 3];

    function digitsOnly(s) {
        return (s || '').replace(/\D/g, '').slice(0, MAX);
    }

    function formatArp(digits) {
        let parts = [];
        let index = 0;
        for (let len of PATTERN) {
            const part = digits.slice(index, index + len);
            if (!part) break;
            parts.push(part);
            index += len;
        }
        return parts.join('-');
    }

    input.value = formatArp(digitsOnly(input.value));

    input.addEventListener('input', () => {
        const digits = digitsOnly(input.value);
        input.value = formatArp(digits);
        input.selectionStart = input.selectionEnd = input.value.length;
    });

    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
        const digits = digitsOnly(pasted);
        input.value = formatArp(digits);
    });

    window.toggleArpInput = function(enable) {
        input.disabled = !enable;
        if (enable) {
            input.focus();
            input.selectionStart = input.selectionEnd = input.value.length;
        }
    };

    window.getArpDigits = function() {
        return digitsOnly(input.value);
    };
})();

// Property Number (PIN) Formatter (123-45-678-90-123)
(function() {
    const input = document.getElementById('propertyNumber');
    if (!input) return;
    
    const MAX = 13;

    function formatPin(d) {
        d = d.slice(0, MAX);
        return [d.slice(0, 3), d.slice(3, 5), d.slice(5, 8), d.slice(8, 10), d.slice(10, 13)]
            .filter(Boolean).join('-');
    }

    function digitsOnly(s) {
        return (s || '').replace(/\D/g, '').slice(0, MAX);
    }

    input.value = formatPin(digitsOnly(input.value));

    input.addEventListener('input', () => {
        const digits = digitsOnly(input.value);
        input.value = formatPin(digits);
        input.selectionStart = input.selectionEnd = input.value.length;
    });

    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text') || '';
        const digits = digitsOnly(pasted);
        input.value = formatPin(digits);
    });

    window.togglePropertyNumberInput = function(enable) {
        input.disabled = !enable;
        if (enable) {
            input.focus();
            input.selectionStart = input.selectionEnd = input.value.length;
        }
    };

    window.getPropertyNumberDigits = function() {
        return digitsOnly(input.value);
    };
})();
</script>