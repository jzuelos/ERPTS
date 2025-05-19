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
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
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
    $limit = 10; // Number of rows per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Fetch paginated data
    $municipalityQuery = "SELECT m.m_id, m.m_code, m.m_description, m.m_status, r.r_no 
                          FROM municipality m
                          JOIN region r ON m.r_id = r.r_id 
                          LIMIT $limit OFFSET $offset";
    $municipalityResult = mysqli_query($conn, $municipalityQuery);

    // Get total rows count for pagination
    $countQuery = "SELECT COUNT(*) AS total FROM municipality";
    $countResult = mysqli_fetch_assoc(mysqli_query($conn, $countQuery));
    $totalRows = $countResult['total'];
    $totalPages = ceil($totalRows / $limit);

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
              data-toggle="dropdown" aria-expanded="false">
              Municipality
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="locationTypeDropdown">
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('Municipality')">Municipality</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('District')">District</a></li>
              <li><a class="dropdown-item" href="#" onclick="changeLocationType('Barangay')">Barangay</a></li>
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
                <th style="width: 35%">Municipality</th>
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
                  <td><?= $row['m_description']; ?></td>
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
      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-3">
        <nav>
          <ul class="pagination">
            <?php if ($page > 1): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
              </li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
              <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
              </li>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    </div>

    <!-- Add Table -->
    <div class="py-4"></div>
    <div class="text-center mb-5">
      <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Add Location</h2>
    </div>

    <!-- Location Selection Options -->
    <div class="row justify-content-center">
      <!-- Municipality -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Municipality">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-city icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Municipality</h5>
          </div>
        </a>
      </div>

      <!-- District -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="District">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-map-marked-alt icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">District</h5>
          </div>
        </a>
      </div>

      <!-- Barangay -->
      <div class="col-md-4 col-sm-6 mb-4 d-flex justify-content-center">
        <a href="#" class="card border-0 shadow-lg p-5 text-center location-card h-100" data-toggle="modal" data-target="#confirmationModal" data-name="Barangay">
          <div class="d-flex flex-column align-items-center">
            <i class="fas fa-home icon-style mb-3" style="font-size: 3rem;"></i>
            <h5 class="font-weight-bold" style="font-size: 1.5rem;">Barangay</h5>
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
          <h5 class="modal-title" id="confirmationModalLabel">Confirm Location</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="confirmationQuestion">Will you encode the [Location Name] details?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Barangay Form Modal -->
  <div class="modal fade" id="barangayModal" tabindex="-1" role="dialog" aria-labelledby="barangayModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="barangayModalLabel">Enter Barangay Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Form to enter Barangay details -->
          <form id="barangayForm">
            <div class="form-group">
              <label for="locationDropdown">District/Municipality/City</label>
              <select class="form-control" id="locationDropdown" required>
                <option value="" selected disabled>Fetching Data...</option>
              </select>
            </div>

            <div class="form-group">
              <label for="barangayCode">Barangay Code</label>
              <input type="text" class="form-control" id="barangayCode" placeholder="Enter Barangay Code" required>
            </div>

            <div class="form-group">
              <label for="barangayName">Name of Barangay</label>
              <input type="text" class="form-control" id="barangayName" placeholder="Enter Name of Barangay" required>
            </div>

            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="statusActive" value="Active" required>
                <label class="form-check-label" for="statusActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="statusInactive" value="Inactive">
                <label class="form-check-label" for="statusInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="reset" class="btn btn-warning" id="resetFormBtn">Reset</button>
          <button type="submit" class="btn btn-primary" id="submitBarangayFormBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Municipality Modal -->
  <div class="modal fade" id="municipalityModal" tabindex="-1" role="dialog" aria-labelledby="municipalityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="municipalityModalLabel">Municipality Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="municipalityForm">
            <div class="form-group">
              <label for="region">Region</label>
              <select class="form-control" id="region">
                <option value="" selected disabled>Fetching Data...</option>
              </select>
            </div>
            <div class="form-group">
              <label for="municipalityCode">Code</label>
              <input type="text" class="form-control" id="municipalityCode" placeholder="Enter Code" value="">
            </div>
            <div class="form-group">
              <label for="municipalityDescription">Description</label>
              <input type="text" class="form-control" id="municipalityDescription" placeholder="Enter Description" value="">
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="municipalityActive" value="active" required>
                <label class="form-check-label" for="municipalityActive">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="municipalityInactive" value="inactive">
                <label class="form-check-label" for="municipalityInactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="reset" class="btn btn-warning" id="resetMunicipalityFormBtn">Reset</button>
          <button type="submit" class="btn btn-primary" id="submitMunicipalityFormBtn">Submit</button>
        </div>
      </div>
    </div>
  </div>


  <!--District Modal -->
  <div class="modal fade" id="districtModal" tabindex="-1" role="dialog" aria-labelledby="districtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="districtModalLabel">District Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="districtForm">
            <div class="form-group">
              <label for="municipality">Municipality / City</label>
              <select class="form-control" id="municipality">
                <option value="" selected disabled>Loading...</option>
              </select>
            </div>
            <div class="form-group">
              <label for="code">Code</label>
              <input type="text" class="form-control" id="districtCode" placeholder="Enter Code" value="">
            </div>
            <div class="form-group">
              <label for="description">Description</label>
              <input type="text" class="form-control" id="districtDescription" placeholder="Enter Description" value="">
            </div>
            <div class="form-group">
              <label>Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="active" value="active" required>
                <label class="form-check-label" for="active">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="inactive" value="inactive">
                <label class="form-check-label" for="inactive">Inactive</label>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="reset" class="btn btn-warning" id="resetFormBtn">Reset</button>
          <button type="submit" class="btn btn-primary" id="submitDistrictFormBtn">Submit</button>
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

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script src="Location.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>            
</body>

</html>