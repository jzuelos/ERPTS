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
  $districts_stmt = $conn->prepare("SELECT district_id, description FROM district");
  $districts_stmt->execute();
  $districts_result = $districts_stmt->get_result();

  // Fetch barangays
  $barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name FROM brgy");
  $barangays_stmt->execute();
  $barangays_result = $barangays_stmt->get_result();

  // Fetch min and max created_at from your table (example: land table)
  $date_range_stmt = $conn->prepare("SELECT MIN(DATE(created_at)) as min_date, MAX(DATE(created_at)) as max_date FROM land");
  $date_range_stmt->execute();
  $date_range_result = $date_range_stmt->get_result()->fetch_assoc();

  $minDate = $date_range_result['min_date'] ?? date('Y-m-d');
  $maxDate = $date_range_result['max_date'] ?? date('Y-m-d');
  ?>
</head>

<body>
  <!-- Header -->
  <?php include 'header.php'; ?>

  <div class="form-center-wrapper">
    <div class="center-form-wrapper">
      <div class="card p-4">
        <form>
          <!-- Back button -->
          <div class="mb-4">
            <a href="Home.php" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-arrow-left"></i> Back
            </a>
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
                  echo "<option value='" . htmlspecialchars($row['c_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['c_description'], ENT_QUOTES) . "</option>";
                }
              } else {
                echo "<option disabled>No active classifications</option>";
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
            <div class="form-group col-md-6">
              <label for="provinceSelect">Province</label>
              <select class="form-control" id="provinceSelect" disabled>
                <option value="" disabled selected>Select Province</option>
                <?php while ($row = $regions_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['province_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['province_name'], ENT_QUOTES) . "</option>";
                } ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="citySelect">Municipality/City</label>
              <select class="form-control" id="citySelect" disabled>
                <option value="" disabled selected>Select Municipality</option>
                <?php while ($row = $municipalities_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['m_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['m_description'], ENT_QUOTES) . "</option>";
                } ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="districtSelect">District</label>
              <select class="form-control" id="districtSelect" disabled>
                <option value="" disabled selected>Select District</option>
                <?php
                if ($districts_result && $districts_result->num_rows > 0) {
                  while ($row = $districts_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['district_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['description'], ENT_QUOTES) . "</option>";
                  }
                } else {
                  echo "<option disabled>No active districts</option>";
                }
                ?>
              </select>
            </div>

            <div class="form-group col-md-6">
              <label for="barangaySelect">Barangay</label>
              <select class="form-control" id="barangaySelect" disabled>
                <option value="" disabled selected>Select Barangay</option>
                <?php
                if ($barangays_result && $barangays_result->num_rows > 0) {
                  while ($row = $barangays_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['brgy_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['brgy_name'], ENT_QUOTES) . "</option>";
                  }
                } else {
                  echo "<option disabled>No active barangays</option>";
                }
                ?>
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
              <input type="date"
                class="form-control"
                id="fromDate"
                disabled
                min="<?php echo $minDate; ?>"
                max="<?php echo $maxDate; ?>">
            </div>
            <div class="form-group col-md-6">
              <label for="toDate">To:</label>
              <input type="date"
                class="form-control"
                id="toDate"
                disabled
                min="<?php echo $minDate; ?>"
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
            <a href="#" class="btn btn-primary" target="_blank">PRINT</a>
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
      const printAllCheckbox = document.getElementById("printAllCheck");
      const classificationCheckbox = document.getElementById("classificationCheck");
      const locationCheckbox = document.getElementById("locationCheck");
      const dateCheckbox = document.getElementById("dateCheck");

      const classificationSelect = document.getElementById("classificationSelect");
      const provinceSelect = document.getElementById("provinceSelect");
      const citySelect = document.getElementById("citySelect");
      const districtSelect = document.getElementById("districtSelect");
      const barangaySelect = document.getElementById("barangaySelect");
      const fromDate = document.getElementById("fromDate");
      const toDate = document.getElementById("toDate");

      const printBtn = document.querySelector(".btn.btn-primary");

      function updateStates() {
        if (printAllCheckbox.checked) {
          classificationCheckbox.disabled = true;
          locationCheckbox.disabled = true;
          dateCheckbox.disabled = true;
          classificationSelect.disabled = true;
          provinceSelect.disabled = true;
          citySelect.disabled = true;
          districtSelect.disabled = true;
          barangaySelect.disabled = true;
          fromDate.disabled = true;
          toDate.disabled = true;
        } else {
          classificationCheckbox.disabled = false;
          locationCheckbox.disabled = false;
          dateCheckbox.disabled = false;

          classificationSelect.disabled = !classificationCheckbox.checked;

          const loc = locationCheckbox.checked;
          provinceSelect.disabled = !loc;
          citySelect.disabled = !loc;
          districtSelect.disabled = !loc;
          barangaySelect.disabled = !loc;

          const date = dateCheckbox.checked;
          fromDate.disabled = !date;
          toDate.disabled = !date;
        }
      }

      // Validate date ranges visually
      toDate.addEventListener("change", function() {
        toDate.classList.remove("is-invalid");
        if (fromDate.value && toDate.value < fromDate.value) {
          toDate.classList.add("is-invalid");
          toDate.value = "";
        }
      });

      fromDate.addEventListener("change", function() {
        fromDate.classList.remove("is-invalid");
        if (toDate.value && fromDate.value > toDate.value) {
          fromDate.classList.add("is-invalid");
          fromDate.value = "";
        }
      });

      // Collect parameters on PRINT button click
      printBtn.addEventListener("click", function(e) {
        e.preventDefault(); // stop actual print for now

        let params = {};

        if (printAllCheckbox.checked) {
          params["print_all"] = true;
        } else {
          if (classificationCheckbox.checked && classificationSelect.value) {
            params["classification_id"] = classificationSelect.value;
          }
          if (locationCheckbox.checked) {
            if (provinceSelect.value) params["province_id"] = provinceSelect.value;
            if (citySelect.value) params["municipality_id"] = citySelect.value;
            if (districtSelect.value) params["district_id"] = districtSelect.value;
            if (barangaySelect.value) params["barangay_id"] = barangaySelect.value;
          }
          if (dateCheckbox.checked) {
            if (fromDate.value) params["from_date"] = fromDate.value;
            if (toDate.value) params["to_date"] = toDate.value;
          }
        }

        console.log("Collected Parameters:", params); // For now, just show them
      });

      printAllCheckbox.addEventListener("change", updateStates);
      classificationCheckbox.addEventListener("change", updateStates);
      locationCheckbox.addEventListener("change", updateStates);
      dateCheckbox.addEventListener("change", updateStates);
    });
  </script>
</body>

</html>