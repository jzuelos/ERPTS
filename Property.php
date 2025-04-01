<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Location.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top" alt="">
      Electronic Real Property Tax System
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <!-- Dropdown menu -->
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="Real-Property-Unit-List.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration.php">Tax Declaration</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="Track.php">Track Paper</a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Transaction.php">Transaction</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Reports.php">Reports</a>
        </li>
        <li class="nav-item ml-3">
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
  <main class="container my-5">
    <!-- Back Button (Positioned to the top left) -->
    <div class="mb-4 d-flex justify-content-start">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>

    <!-- Location Title -->
    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Location</h2>
    </div>

    <?php
    // Fetch Municipality Data
    $municipalityQuery = "SELECT m.m_id, m.m_code, m.m_description, m.m_status, r.r_no 
                      FROM municipality m
                      JOIN region r ON m.r_id = r.r_id";
    $municipalityResult = mysqli_query($conn, $municipalityQuery);

    // Fetch District Data
    $districtQuery = "SELECT d.district_id, d.district_code, d.description, d.status, m.m_description 
                  FROM district d
                  JOIN municipality m ON d.m_id = m.m_id";
    $districtResult = mysqli_query($conn, $districtQuery);

    // Fetch Barangay Data
    $barangayQuery = "SELECT b.brgy_id, b.brgy_code, b.brgy_name, b.status, m.m_description 
                  FROM brgy b
                  JOIN municipality m ON b.m_id = m.m_id";
    $barangayResult = mysqli_query($conn, $barangayQuery);
    ?>

    <!-- Location Table Section -->
    <div class="card border-0 shadow p-4 rounded-3 mb-4">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="section-title mb-0">Location Information</h5>
        <div class="d-flex align-items-center">
          <!-- Search Bar -->
          <div class="input-group me-4" style="width: 250px;">
            <input type="text" class="form-control border-start-0" id="tableSearch" placeholder="Search...">
            <span class="input-group-text bg-transparent border-end-0">
              <i class="fas fa-search"></i>
            </span>
          </div>

          <!-- Dropdown -->
          <div class="dropdown ml-5">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="locationTypeDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Classification
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="locationTypeDropdown">
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('Classification')">Classification</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('Actual Uses')">Actual Uses</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('Sub-Classes')">Sub-Classes</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Municipality Table -->
      <div class="px-3">
        <div class="table-responsive rounded">
          <table class="table table-hover align-middle mb-0" id="municipalityTable">
            <thead class="table-light">
              <tr>
                <th style="width: 20%">Region</th>
                <th style="width: 15%">Code</th>
                <th style="width: 35%">Description</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($municipalityResult)) { ?>
                <tr>
                  <td><?= $row['r_no']; ?></td>
                  <td><?= $row['m_code']; ?></td>
                  <td><?= $row['m_description']; ?></td>
                  <td>
                    <span class="badge <?= $row['m_status'] == 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?>">
                      <?= ucfirst($row['m_status']); ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <!-- District Table -->
          <table class="table table-hover align-middle mb-0 d-none" id="districtTable">
            <thead class="table-light">
              <tr>
                <th style="width: 20%">Municipality/City</th>
                <th style="width: 15%">Code</th>
                <th style="width: 35%">Description</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($districtResult)) { ?>
                <tr>
                  <td><?= $row['m_description']; ?></td>
                  <td><?= $row['district_code']; ?></td>
                  <td><?= $row['description']; ?></td>
                  <td>
                    <span class="badge <?= $row['status'] == 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?>">
                      <?= ucfirst($row['status']); ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>

          <!-- Barangay Table -->
          <table class="table table-hover align-middle mb-0 d-none" id="barangayTable">
            <thead class="table-light">
              <tr>
                <th style="width: 20%">District/Municipality/City</th>
                <th style="width: 15%">Barangay Code</th>
                <th style="width: 35%">Name of Barangay</th>
                <th style="width: 15%">Status</th>
                <th style="width: 15%">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = mysqli_fetch_assoc($barangayResult)) { ?>
                <tr>
                  <td><?= $row['brgy_code']; ?></td>
                  <td><?= $row['brgy_name']; ?></td>
                  <td>
                    <span class="badge <?= $row['status'] == 'Active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'; ?>">
                      <?= ucfirst($row['status']); ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-outline-primary me-1" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add Table -->
    <div class="py-4"></div>
    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Add Location</h2>
    </div>

   <!-- Location Selection Options -->
    <div class="row justify-content-center">
      <!-- Classification -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Classification" data-form="classificationModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-layer-group icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Classification</h5>
          </div>
        </a>
      </div>

      <!-- Actual Uses -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Actual Uses" data-form="actUsesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-tag icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Actual Uses</h5>
          </div>
        </a>
      </div>

      <!-- Sub-Classes -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Sub-Classes" data-form="subClassesModal">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-sitemap icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Sub-Classes</h5>
          </div>
        </a>
      </div>
    </div>
  </main>



  <!--Modal Section-->
<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Confirm Property Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="confirmationQuestion">Will you encode the <span id="categoryName"></span> details?</p>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
      </div>
    </div>
  </div>
</div>

 <!-- Classification Form Modal -->
<div class="modal fade" id="classificationModal" tabindex="-1" role="dialog" aria-labelledby="classificationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="classificationModalLabel">Enter Classification Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Form to enter Classification details -->
        <form id="classificationForm">
          <div class="form-group">
            <label for="classificationCode">Code</label>
            <input type="text" class="form-control" id="classificationCode" placeholder="Enter Classification Code" required>
          </div>

          <div class="form-group">
            <label for="classificationDescription">Description</label>
            <select class="form-control" id="classificationDescription" required>
              <option value="" selected disabled>Select Classification</option>
              <option value="Residential">Residential</option>
              <option value="Agricultural">Agricultural</option>
              <option value="Commercial">Commercial</option>
              <option value="Industrial">Industrial</option>
              <option value="Mineral">Mineral</option>
              <option value="Special">Special</option>
            </select>
          </div>

          <div class="form-group">
            <label for="assessmentLevel">Assessment Level (%)</label>
            <input type="number" class="form-control" id="assessmentLevel" 
                   placeholder="Enter Assessment Level" min="0" max="100" step="0.01" required>
          </div>

          <div class="form-group">
            <label>Status</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="classificationActive" value="Active" required checked>
              <label class="form-check-label" for="classificationActive">Active</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="status" id="classificationInactive" value="Inactive">
              <label class="form-check-label" for="classificationInactive">Inactive</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-warning reset-btn">Reset</button>
<button type="button" class="btn btn-primary submit-btn">Submit</button>

      </div>
    </div>
  </div>
</div>

<!-- Actual Uses Form Modal -->
<div class="modal fade" id="actUsesModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reportModalLabel">Actual Uses</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="reportForm">
          <div class="form-group">
            <label for="reportCode">Report Code</label>
            <select class="form-control" id="reportCode" required>
              <option value="" selected disabled>Select Report Code</option>
              <option value="SC">Scientific (SC)</option>
              <!-- Add more report codes here if needed -->
            </select>
          </div>
          <div class="form-group">
            <label for="reportCodeValue">Code</label>
            <input type="text" class="form-control" id="reportCodeValue" placeholder="Enter Code" required>
          </div>
          <div class="form-group">
            <label for="reportDescription">Description</label>
            <input type="text" class="form-control" id="reportDescription" placeholder="Enter Description" required>
          </div>
          <div class="form-group">
            <label for="reportAssessmentLevel">Assessment Level (%)</label>
            <input type="number" class="form-control" id="reportAssessmentLevel" 
                   placeholder="Enter Assessment Level" min="0" max="100" step="0.01" required>
          </div>
          <div class="form-group">
            <label>Status</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="reportStatus" id="reportActive" value="Active" required checked>
              <label class="form-check-label" for="reportActive">Active</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="reportStatus" id="reportInactive" value="Inactive">
              <label class="form-check-label" for="reportInactive">Inactive</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-warning reset-btn">Reset</button>
      <button type="button" class="btn btn-primary submit-btn">Submit</button>
      </div>
    </div>
  </div>
</div>


  <!-- Sub-Classes Modal -->
<div class="modal fade" id="subClassesModal" tabindex="-1" role="dialog" aria-labelledby="subClassesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subClassesModalLabel">Sub-Classes Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="subClassesForm">
          <div class="form-group">
            <label for="subClassesCode">Code</label>
            <input type="text" class="form-control" id="subClassesCode" placeholder="Enter Code" required>
          </div>
          <div class="form-group">
            <label for="subClassesDescription">Description</label>
            <input type="text" class="form-control" id="subClassesDescription" placeholder="Enter Description" required>
          </div>
          <div class="form-group">
            <label for="unitValue">Unit Value</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">₱</span>
              </div>
              <input type="number" class="form-control" id="unitValue" 
                     placeholder="Enter Unit Value" min="0" step="0.01" required>
            </div>
          </div>
          <div class="form-group">
            <label>Status</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="subClassesStatus" id="subClassesActive" value="Active" required checked>
              <label class="form-check-label" for="subClassesActive">Active</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="subClassesStatus" id="subClassesInactive" value="Inactive">
              <label class="form-check-label" for="subClassesInactive">Inactive</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning reset-btn">Reset</button>
        <button type="button" class="btn btn-primary submit-btn">Submit</button>
      </div>
    </div>
  </div>
</div>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>


  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="Property.js"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function () {
    let selectedForm = "";

   
    document.querySelectorAll(".location-card").forEach((card) => {
      card.addEventListener("click", function (event) {
        event.preventDefault();

        // Get the modal data from the clicked card
        const categoryName = this.getAttribute("data-name");
        selectedForm = this.getAttribute("data-form");

        // Update modal content
        document.getElementById("categoryName").textContent = categoryName;

        // Show the confirmation modal
        $("#confirmationModal").modal("show");
      });
    });

    // When confirm is clicked, open the specific modal
    document.getElementById("confirmBtn").addEventListener("click", function () {
      $("#confirmationModal").modal("hide"); // Hide confirmation modal
      setTimeout(() => {
        $("#" + selectedForm).modal("show"); // Show specific modal
      }, 500); // Small delay for smooth transition
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("cancelBtn").addEventListener("click", function () {
  $("#confirmationModal").modal("hide"); // Force-close modal
});
  
    // Handle Reset Button Click (Resets Form Only)
    document.querySelectorAll(".reset-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const modal = this.closest(".modal");
        const form = modal.querySelector("form");
        if (form) form.reset();
      });
    });
  
    // Handle Submit Button Click (Validates & Closes Modal)
    document.querySelectorAll(".submit-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const modal = this.closest(".modal");
        const form = modal.querySelector("form");
  
        if (form && form.checkValidity()) {
          alert("Form submitted: " + form.id);
          $(modal).modal("hide"); // Close the modal
        } else {
          form.reportValidity(); // Show validation errors
        }
      });
    });

    document.querySelectorAll(".close").forEach((button) => {
  button.addEventListener("click", function () {
    const modal = this.closest(".modal");
    $(modal).modal("hide"); // Close the modal manually
  });
});
  });

</script>
<script>
  function changeLocationType(type) {
      document.getElementById("municipalityTable").classList.add("d-none");
      document.getElementById("districtTable").classList.add("d-none");
      document.getElementById("barangayTable").classList.add("d-none");

      if (type === "Municipality") {
        document.getElementById("municipalityTable").classList.remove("d-none");
      } else if (type === "District") {
        document.getElementById("districtTable").classList.remove("d-none");
      } else if (type === "Barangay") {
        document.getElementById("barangayTable").classList.remove("d-none");
      }
    }  
</script>
</body>

</html>