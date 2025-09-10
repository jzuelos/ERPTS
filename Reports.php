<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap & Font Awesome CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Reports.css">
  <title>Electronic Real Property Tax System</title>

  <style>
    .is-invalid {
      border-color: red !important;
      box-shadow: 0 0 5px red !important;
    }
  </style>

  <?php
  include 'database.php';
  $conn = Database::getInstance();

  // Fetch classifications
  $classification_stmt = $conn->prepare("SELECT c_id, c_description FROM classification");
  $classification_stmt->execute();
  $classification_result = $classification_stmt->get_result();

  // Fetch provinces
  $stmt = $conn->prepare("SELECT province_id, province_name FROM province");
  $stmt->execute();
  $regions_result = $stmt->get_result();

  // Fetch municipalities
  $municipalities_stmt = $conn->prepare("SELECT m_id, m_description FROM municipality");
  $municipalities_stmt->execute();
  $municipalities_result = $municipalities_stmt->get_result();

  // Fetch districts
  $districts_stmt = $conn->prepare("SELECT district_id, description, m_id FROM district");
  $districts_stmt->execute();
  $districts_result = $districts_stmt->get_result();

  $districts = [];
  while ($row = $districts_result->fetch_assoc()) {
    $districts[] = $row;
  }

  // Fetch barangays
  $barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name, m_id FROM brgy");
  $barangays_stmt->execute();
  $barangays_result = $barangays_stmt->get_result();

  $barangays = [];
  while ($row = $barangays_result->fetch_assoc()) {
    $barangays[] = $row;
  }

  // Fetch min and max created_at
  $date_range_stmt = $conn->prepare("SELECT MIN(DATE(created_at)) as min_date, MAX(DATE(created_at)) as max_date FROM p_info");
  $date_range_stmt->execute();
  $date_range_result = $date_range_stmt->get_result()->fetch_assoc();

  $minDate = $date_range_result['min_date'] ?? date('Y-m-d');
  $maxDate = $date_range_result['max_date'] ?? date('Y-m-d');

  // Encode for JS
  $districts_json = json_encode($districts);
  $barangays_json = json_encode($barangays);
  ?>
</head>

<body>
  <!-- Header -->
  <?php include 'header.php'; ?>

  <div class="form-center-wrapper">
    <div class="center-form-wrapper">
      <div class="card p-4">

        <!-- Back button -->
        <div class="mb-3">
          <a href="Home.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
          </a>
        </div>

        <form>
          <!-- Header Section -->
          <div class="form-header d-flex align-items-center mb-4">
            <h4 class="fw-bold mb-3">PROPERTY BY CLASSIFICATION AGRICULTURAL (AG)</h4>
          </div>

          <!-- Filter by Classification -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="classificationCheck">
            <label class="form-check-label fw-bold" for="classificationCheck">Filter by: Classification</label>
          </div>
          <div class="form-group">
            <label for="classificationSelect">Classification</label>
            <select class="form-control w-25" id="classificationSelect" disabled>
              <option value="" disabled selected>Select Classification</option>
              <?php
              if ($classification_result && $classification_result->num_rows > 0) {
                while ($row = $classification_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['c_description'], ENT_QUOTES) . "'>"
                    . htmlspecialchars($row['c_description'], ENT_QUOTES) . "</option>";
                }
              } else {
                echo "<option disabled>No classifications found</option>";
              }
              ?>
            </select>
          </div>
          <hr>

          <!-- Filter by Location -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="locationCheck">
            <label class="form-check-label fw-bold" for="locationCheck">Filter by: Location</label>
          </div>
          <div class="form-row">
            <!-- Province -->
            <div class="form-group col-md-6">
              <label for="provinceSelect">Province</label>
              <select class="form-control" id="provinceSelect" disabled>
                <option value="" disabled selected>Select Province</option>
                <?php
                if ($regions_result->num_rows === 0) {
                  echo "<option disabled>No provinces found</option>";
                } else {
                  while ($row = $regions_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['province_name'], ENT_QUOTES) . "'>"
                      . htmlspecialchars($row['province_name'], ENT_QUOTES) . "</option>";
                  }
                }
                ?>
              </select>
            </div>

            <!-- Municipality -->
            <div class="form-group col-md-6">
              <label for="citySelect" class="form-label">Municipality</label>
              <select class="form-control" id="citySelect" disabled>
                <option value="" disabled selected>Select Municipality</option>
                <?php
                if ($municipalities_result->num_rows > 0) {
                  while ($row = $municipalities_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['m_id'], ENT_QUOTES) . "'>"
                      . htmlspecialchars($row['m_description'], ENT_QUOTES) . "</option>";
                  }
                } else {
                  echo "<option disabled>No municipalities found</option>";
                }
                ?>
              </select>
            </div>

            <!-- District -->
            <div class="form-group col-md-6">
              <label for="districtSelect" class="form-label">District</label>
              <select class="form-control" id="districtSelect" disabled>
                <option value="" disabled selected>Select District</option>
              </select>
            </div>

            <!-- Barangay -->
            <div class="form-group col-md-6">
              <label for="barangaySelect" class="form-label">Barangay</label>
              <select class="form-control" id="barangaySelect" disabled>
                <option value="" disabled selected>Select Barangay</option>
              </select>
            </div>
          </div>
          <hr>

          <!-- Filter by Date -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="dateCheck">
            <label class="form-check-label fw-bold" for="dateCheck">Filter by: Date Created</label>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="fromDate">From:</label>
              <input type="date" class="form-control" id="fromDate" disabled min="<?php echo $minDate; ?>"
                max="<?php echo $maxDate; ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="toDate">To:</label>
              <input type="date" class="form-control" id="toDate" disabled min="<?php echo $minDate; ?>"
                max="<?php echo $maxDate; ?>">
            </div>
          </div>
          <hr>

          <!-- Print All -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="printAllCheck">
            <label class="form-check-label fw-bold" for="printAllCheck">Print ALL (No Filtering)</label>
          </div>

          <!-- Submit -->
          <div class="text-right">
            <a href="#" id="printBtn" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer>
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2024 Electronic Real Property Tax System. All Rights Reserved.
    </div>
  </footer>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Data from PHP
      const districts = <?php echo $districts_json; ?>;
      const barangays = <?php echo $barangays_json; ?>;

      // Checkboxes
      const printAllCheckbox = document.getElementById("printAllCheck");
      const classificationCheckbox = document.getElementById("classificationCheck");
      const locationCheckbox = document.getElementById("locationCheck");
      const dateCheckbox = document.getElementById("dateCheck");

      // Filters
      const classificationSelect = document.getElementById("classificationSelect");
      const provinceSelect = document.getElementById("provinceSelect");
      const citySelect = document.getElementById("citySelect");
      const districtSelect = document.getElementById("districtSelect");
      const barangaySelect = document.getElementById("barangaySelect");
      const fromDate = document.getElementById("fromDate");
      const toDate = document.getElementById("toDate");

      // Buttons
      const printBtn = document.querySelector(".btn.btn-primary");

      // ✅ When Municipality changes → auto-fill district + load barangays
      citySelect.addEventListener("change", function() {
        const m_id = this.value;

        // Reset
        districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        districtSelect.disabled = true;
        barangaySelect.disabled = true;

        // Auto-fill the FIRST district for this municipality
        const filteredDistricts = districts.filter(d => d.m_id == m_id);
        if (filteredDistricts.length > 0) {
          const d = filteredDistricts[0]; // first district
          const opt = document.createElement("option");
          opt.value = d.district_id;
          opt.textContent = d.description;
          opt.selected = true;
          districtSelect.appendChild(opt);
          districtSelect.disabled = true; // locked (cannot change)
        }

        // Load barangays for this municipality
        const filteredBarangays = barangays.filter(b => b.m_id == m_id);
        if (filteredBarangays.length > 0) {
          filteredBarangays.forEach(b => {
            const opt = document.createElement("option");
            opt.value = b.brgy_id;
            opt.textContent = b.brgy_name;
            barangaySelect.appendChild(opt);
          });
          barangaySelect.disabled = false;
        }
      });

      // ✅ Enable/disable filters based on checkboxes
      function updateStates() {
        const allDisabled = printAllCheckbox.checked;

        classificationSelect.disabled = allDisabled || !classificationCheckbox.checked;
        provinceSelect.disabled = allDisabled || !locationCheckbox.checked;
        citySelect.disabled = allDisabled || !locationCheckbox.checked;
        districtSelect.disabled = allDisabled || !locationCheckbox.checked; // will still be auto-filled
        barangaySelect.disabled = allDisabled || !locationCheckbox.checked;
        fromDate.disabled = allDisabled || !dateCheckbox.checked;
        toDate.disabled = allDisabled || !dateCheckbox.checked;
      }

      printAllCheckbox.addEventListener("change", updateStates);
      classificationCheckbox.addEventListener("change", updateStates);
      locationCheckbox.addEventListener("change", updateStates);
      dateCheckbox.addEventListener("change", updateStates);

      // ✅ Date validation
      toDate.addEventListener("change", function() {
        if (fromDate.value && toDate.value < fromDate.value) {
          alert("The 'To Date' cannot be earlier than 'From Date'.");
          this.value = "";
        }
      });

      // ✅ Print button
      printBtn.addEventListener("click", function(e) {
        e.preventDefault();

        if (!printAllCheckbox.checked) {
          if (
            (classificationCheckbox.checked && !classificationSelect.value) ||
            (locationCheckbox.checked && (!provinceSelect.value || !citySelect.value)) ||
            (dateCheckbox.checked && (!fromDate.value || !toDate.value))
          ) {
            alert("Please fill all selected filters before printing.");
            return;
          }
        }

        alert("Printing report...");
        // TODO: replace with actual print function
      });

      // Init
      updateStates();
    });
  </script>
</body>

</html>