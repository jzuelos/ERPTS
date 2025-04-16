<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Reports.css">
  <title>Electronic Real Property Tax System</title>
  <style>
    /* Additional styles for sticky header and footer */
    html,
    body {
      height: 100%;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    .content-wrapper {
      flex: 1 0 auto;
      padding-top: 70px;
      /* Adjust based on your header height */
    }

    .footer {
      flex-shrink: 0;
    }

    /* Ensure header is sticky */
    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }
  </style>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Content Wrapper -->
  <div class="content-wrapper">
    <section class="container">
      <div class="card p-4 mt-4">
        <form>
          <div class="mb-4 d-flex justify-content-start">
            <a href="Home.php" class="btn btn-outline-secondary btn-sm">
              <i class="fas fa-arrow-left"></i> Back
            </a>
          </div>
          <!-- Filter by Classification -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="classificationCheck">
            <label class="form-check-label font-weight-bold" for="classificationCheck">Filter by: Classification</label>
          </div>
          <div class="form-group">
            <label for="classificationSelect">Classification</label>
            <select class="form-control w-25" id="classificationSelect">
              <option>Residential</option>
              <option>Credential</option>
              <option>Industrial</option>
              <option>Agricultural</option>
              <option>Timberland</option>
              <option>Mineral Lands</option>
              <option>Special Property</option>
            </select>
          </div>
          <hr>

          <?php
          // Include the database connection
          include 'database.php';
          $conn = Database::getInstance();

          // Fetch active provinces
          $stmt = $conn->prepare("SELECT province_id, province_name FROM province");  
          $stmt->execute();
          $regions_result = $stmt->get_result();

          // Fetch active municipalities
          $municipalities_stmt = $conn->prepare("SELECT m_id, m_description FROM municipality WHERE m_status = 'Active'");
          $municipalities_stmt->execute();
          $municipalities_result = $municipalities_stmt->get_result();

          // Fetch active districts
          $districts_stmt = $conn->prepare("SELECT district_id, description FROM district WHERE status = 'Active'");
          $districts_stmt->execute();
          $districts_result = $districts_stmt->get_result();

          // Fetch active barangays
          $barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name FROM brgy WHERE status = 'Active'");
          $barangays_stmt->execute();
          $barangays_result = $barangays_stmt->get_result();
          ?>

          <!-- Filter by Location -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="locationCheck">
            <label class="form-check-label font-weight-bold" for="locationCheck">Filter by: Location</label>
          </div>
          <div class="form-row">
            <!-- Province Dropdown -->
            <div class="form-group col-md-6">
              <label for="provinceSelect">Province</label>
              <select class="form-control" id="provinceSelect">
                <option value="" disabled selected>Select Province</option>
                <?php
                while ($row = $regions_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['province_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['province_name'], ENT_QUOTES) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- Municipality/City Dropdown -->
            <div class="form-group col-md-6">
              <label for="citySelect">Municipality/City</label>
              <select class="form-control" id="citySelect">
                <option value="" disabled selected>Select Municipality</option>
                <?php
                while ($row = $municipalities_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['m_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['m_description'], ENT_QUOTES) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- District Dropdown -->
            <div class="form-group col-md-6">
              <label for="districtSelect">District</label>
              <select class="form-control" id="districtSelect">
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

            <!-- Barangay Dropdown -->
            <div class="form-group col-md-6">
              <label for="barangaySelect">Barangay</label>
              <select class="form-control" id="barangaySelect">
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
            <label class="form-check-label font-weight-bold" for="dateCheck">Filter by: Date Created</label>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label for="fromDate">From:</label>
              <input type="date" class="form-control" id="fromDate">
            </div>
            <div class="form-group col-md-6">
              <label for="toDate">To:</label>
              <input type="date" class="form-control" id="toDate">
            </div>
          </div>

          <!-- Print All Checkbox -->
          <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="printAllCheck">
            <label class="form-check-label font-weight-bold" for="printAllCheck">Print ALL (No Filtering)</label>
          </div>

          <!-- Submit Button -->
          <div class="text-right">
            <a href="#" class="btn btn-primary" target="_blank">PRINT</a>
          </div>
        </form>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="footer bg-light text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <scripts src="reports.js">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="Location.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
      integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
      crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
      crossorigin="anonymous"></script>

      <script>
  document.addEventListener("DOMContentLoaded", function () {
    const printAllCheckbox = document.getElementById("printAllCheck");
    const filterCheckboxes = [
      document.getElementById("classificationCheck"),
      document.getElementById("locationCheck"),
      document.getElementById("dateCheck")
    ];

    function updateCheckboxStates() {
      // If any filter checkbox is checked, disable the Print All checkbox
      if (filterCheckboxes.some(cb => cb.checked)) {
        printAllCheckbox.checked = false;
        printAllCheckbox.disabled = true;
      } else {
        printAllCheckbox.disabled = false;
      }
    }

    function handlePrintAllClick() {
      if (printAllCheckbox.checked) {
        // Uncheck and disable all filter checkboxes
        filterCheckboxes.forEach(cb => {
          cb.checked = false;
          cb.disabled = true;
        });
      } else {
        // Enable all filter checkboxes
        filterCheckboxes.forEach(cb => {
          cb.disabled = false;
        });
      }
    }

    // Attach event listeners
    filterCheckboxes.forEach(cb => cb.addEventListener("change", updateCheckboxStates));
    printAllCheckbox.addEventListener("change", handlePrintAllClick);

    // Initial check on page load
    updateCheckboxStates();
  });

  window.addEventListener("beforeunload", function (e) {
  e.preventDefault();
  e.returnValue = ""; // Show the default browser confirmation dialog
});

</script>

</body>

</html>