<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

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
  $province_stmt = $conn->prepare("SELECT province_id, province_name FROM province");
  $province_stmt->execute();
  $provinces_result = $province_stmt->get_result();

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

  // Fetch date range
  $date_range_stmt = $conn->prepare("SELECT MIN(DATE(created_at)) as min_date, MAX(DATE(created_at)) as max_date FROM p_info");
  $date_range_stmt->execute();
  $date_range_result = $date_range_stmt->get_result()->fetch_assoc();

  $minDate = $date_range_result['min_date'] ?? date('Y-m-d');
  $maxDate = $date_range_result['max_date'] ?? date('Y-m-d');

  // Encode districts & barangays for JS
  $districts_json = json_encode($districts);
  $barangays_json = json_encode($barangays);
  ?>
</head>

<body>
  <!-- Header -->
  <?php include 'header.php'; ?>

  <div class="container my-5">
    <div class="card shadow-lg border-0 rounded-3 mx-auto" style="max-width: 1200px;">
      <div class="card-body p-5">

        <!-- Back + Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <a href="Home.php" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
          </a>
          <h4 class="fw-bold text-primary text-center flex-grow-1 mb-0">
            PROPERTY BY CLASSIFICATION AGRICULTURAL (AG)
          </h4>
          <div style="width: 70px;"></div>
        </div>

        <!-- Filters -->
        <form class="border rounded p-4 bg-light d-flex flex-column justify-content-center" style="height: 500px;">
          <div class="row g-5">

            <!-- LEFT: Location -->
            <div class="col-md-6">
              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="locationCheck">
                <label class="form-check-label fw-bold" for="locationCheck">Filter by: Location</label>
              </div>

              <div class="mb-3">
                <label for="provinceSelect" class="form-label fw-bold">Province</label>
                <select class="form-select" id="provinceSelect" disabled>
                  <option value="" disabled selected>Select Province</option>
                  <?php
                  if ($provinces_result->num_rows > 0) {
                    while ($row = $provinces_result->fetch_assoc()) {
                      echo "<option value='" . htmlspecialchars($row['province_id'], ENT_QUOTES) . "'>"
                        . htmlspecialchars($row['province_name'], ENT_QUOTES) . "</option>";
                    }
                  } else {
                    echo "<option disabled>No provinces found</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="citySelect" class="form-label fw-bold">Municipality</label>
                <select class="form-select" id="citySelect" disabled>
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

              <div class="mb-3">
                <label for="districtSelect" class="form-label fw-bold">District</label>
                <select class="form-select" id="districtSelect" disabled>
                  <option value="" disabled selected>Select District</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="barangaySelect" class="form-label fw-bold">Barangay</label>
                <select class="form-select" id="barangaySelect" disabled>
                  <option value="" disabled selected>Select Barangay</option>
                </select>
              </div>
            </div>

            <!-- RIGHT: Classification + Date -->
            <div class="col-md-6">
              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="classificationCheck">
                <label class="form-check-label fw-bold" for="classificationCheck">Filter by: Classification</label>
              </div>

              <div class="mb-3">
                <label for="classificationSelect" class="form-label fw-bold">Classification</label>
                <select class="form-select w-75" id="classificationSelect" disabled>
                  <option value="" disabled selected>Select Classification</option>
                  <?php
                  if ($classification_result->num_rows > 0) {
                    while ($row = $classification_result->fetch_assoc()) {
                      echo "<option value='" . htmlspecialchars($row['c_id'], ENT_QUOTES) . "'>"
                        . htmlspecialchars($row['c_description'], ENT_QUOTES) . "</option>";
                    }
                  } else {
                    echo "<option disabled>No classifications found</option>";
                  }
                  ?>
                </select>
              </div>

              <hr>

              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="dateCheck">
                <label class="form-check-label fw-bold" for="dateCheck">Filter by: Date Created</label>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="fromDate" class="form-label fw-bold">From:</label>
                  <input type="date" class="form-control" id="fromDate" disabled min="<?= $minDate ?>" max="<?= $maxDate ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="toDate" class="form-label fw-bold">To:</label>
                  <input type="date" class="form-control" id="toDate" disabled min="<?= $minDate ?>" max="<?= $maxDate ?>">
                </div>
              </div>

              <hr>

              <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="printAllCheck">
                <label class="form-check-label fw-bold" for="printAllCheck">Print ALL (No Filtering)</label>
              </div>

              <div class="text-end">
                <a href="#" id="printBtn" class="btn btn-primary px-4" target="_blank">PRINT</a>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <footer class="text-center p-3" style="background-color: rgba(0,0,0,0.05);">
    © 2024 Electronic Real Property Tax System. All Rights Reserved.
  </footer>

  <!-- JS -->
  <script>
    const districts = <?= $districts_json ?>;
    const barangays = <?= $barangays_json ?>;

    document.addEventListener("DOMContentLoaded", () => {
      const printAll = document.getElementById("printAllCheck");
      const classificationCheck = document.getElementById("classificationCheck");
      const locationCheck = document.getElementById("locationCheck");
      const dateCheck = document.getElementById("dateCheck");

      const classificationSelect = document.getElementById("classificationSelect");
      const provinceSelect = document.getElementById("provinceSelect");
      const citySelect = document.getElementById("citySelect");
      const districtSelect = document.getElementById("districtSelect");
      const barangaySelect = document.getElementById("barangaySelect");
      const fromDate = document.getElementById("fromDate");
      const toDate = document.getElementById("toDate");

      const printBtn = document.getElementById("printBtn");

      // ✅ Update enabled/disabled states
      function updateStates() {
        const all = printAll.checked;
        classificationSelect.disabled = all || !classificationCheck.checked;
        provinceSelect.disabled = all || !locationCheck.checked;
        citySelect.disabled = all || !locationCheck.checked;
        districtSelect.disabled = all || !locationCheck.checked;
        barangaySelect.disabled = all || !locationCheck.checked;
        fromDate.disabled = all || !dateCheck.checked;
        toDate.disabled = all || !dateCheck.checked;
      }

      // ✅ Municipality change → auto-fill district + barangays
      citySelect.addEventListener("change", () => {
        const m_id = citySelect.value;

        // Reset
        districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';
        barangaySelect.disabled = true;

        // District auto-fill (first match)
        const d = districts.find(d => d.m_id == m_id);
        if (d) {
          const opt = document.createElement("option");
          opt.value = d.district_id;
          opt.textContent = d.description;
          opt.selected = true;
          districtSelect.appendChild(opt);
          districtSelect.disabled = true;
        }

        // Barangays under this municipality
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

      // ✅ Date validation
      toDate.addEventListener("change", () => {
        toDate.classList.remove("is-invalid");
        if (fromDate.value && toDate.value < fromDate.value) {
          toDate.classList.add("is-invalid");
        }
      });
      fromDate.addEventListener("change", () => {
        fromDate.classList.remove("is-invalid");
        if (toDate.value && fromDate.value > toDate.value) {
          fromDate.classList.add("is-invalid");
        }
      });

      // ✅ Print button
      printBtn.addEventListener("click", e => {
        e.preventDefault();

        if (fromDate.classList.contains("is-invalid") || toDate.classList.contains("is-invalid")) {
          alert("Please fix the date range.");
          return;
        }

        let params = new URLSearchParams();
        if (printAll.checked) {
          params.append("print_all", "1");
        } else {
          if (classificationCheck.checked && classificationSelect.value)
            params.append("classification", classificationSelect.value);
          if (locationCheck.checked) {
            if (provinceSelect.value) params.append("province", provinceSelect.value);
            if (citySelect.value) params.append("municipality", citySelect.value);
            if (districtSelect.value) params.append("district", districtSelect.value);
            if (barangaySelect.value) params.append("barangay", barangaySelect.value);
          }
          if (dateCheck.checked) {
            if (fromDate.value) params.append("from_date", fromDate.value);
            if (toDate.value) params.append("to_date", toDate.value);
          }
        }

        if (!printAll.checked && !params.toString()) {
          alert("Please select at least one filter or Print All.");
          return;
        }

        window.open("report-print.php?" + params.toString(), "_blank");
      });

      // Watch checkboxes
      printAll.addEventListener("change", updateStates);
      classificationCheck.addEventListener("change", updateStates);
      locationCheck.addEventListener("change", updateStates);
      dateCheck.addEventListener("change", updateStates);

      updateStates();
    });
  </script>
</body>

</html>