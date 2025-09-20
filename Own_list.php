<?php
session_start();

//Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'database.php';

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch owners from the database
$sql = "SELECT own_id, own_fname, own_mname, own_surname, tin_no, house_no, street, barangay, district, city, province, own_info 
        FROM owners_tb";

$owners = [];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $owners[] = $row;
  }
}

// ✅ Fetch municipalities
$municipalities_stmt = $conn->prepare("SELECT m_id, m_description FROM municipality");
$municipalities_stmt->execute();
$municipalities_result = $municipalities_stmt->get_result();

// ✅ Fetch districts
$districts_stmt = $conn->prepare("SELECT district_id, description, m_id FROM district");
$districts_stmt->execute();
$districts_result = $districts_stmt->get_result();
$districts = [];
while ($row = $districts_result->fetch_assoc()) {
  $districts[] = $row;
}

// ✅ Fetch barangays
$barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name, m_id FROM brgy");
$barangays_stmt->execute();
$barangays_result = $barangays_stmt->get_result();
$barangays = [];
while ($row = $barangays_result->fetch_assoc()) {
  $barangays[] = $row;
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" 
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" 
      crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="Own_list.css">
  <link rel="stylesheet" href="Real-Property-Unit-List.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

<!-- Main Body -->
<section class="container mt-5">
  <div class="card p-4">
  <div class="mb-4 d-flex justify-content-start">
      <a href="Admin-Page-2.php" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
    <h3 class="mb-4">Owner's List</h3>
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <label for="searchInput" class="sr-only">Search</label>
        <div class="input-group">
          <input type="text" class="form-control" id="searchInput" placeholder="Search" onkeydown="handleEnter(event)">
          <div class="input-group-append">
            <button type="button" class="btn btn-success btn-hover" onclick="filterTable()">Search</button>
          </div>
        </div>
      </div>
      <a href="Merge_Owners.php" class="btn btn-primary">Merge Owners</a>
    </div>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered modern-table" id="propertyTable">
        <thead class="thead-dark">
          <tr>
            <th class="align-middle">ID</th>
            <th class="align-middle">Name</th>
            <th class="align-middle">Address <br><small>House No., Street, District</small></th>
            <th class="align-middle">Information</th>
            <th class="align-middle">Edit</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($owners)): ?>
            <?php foreach ($owners as $owner): ?>
              <tr>
                <td><?= htmlspecialchars($owner['own_id']) ?></td>
                <td><?= htmlspecialchars($owner['own_fname'] . ' ' . $owner['own_mname'] . ' ' . $owner['own_surname']) ?></td>
                <td><?= htmlspecialchars($owner['house_no'] . ', ' . $owner['street'] . ', ' . $owner['barangay'] . ', ' . $owner['city'] . ', ' . $owner['district'] . ', ' . $owner['province']) ?></td>
                <td><?= htmlspecialchars($owner['own_info']) ?></td>
                <td>
                 <button class="btn btn-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#editModal"
                  data-id="<?= htmlspecialchars($owner['own_id']) ?>"
                  data-fname="<?= htmlspecialchars($owner['own_fname']) ?>"
                  data-mname="<?= htmlspecialchars($owner['own_mname']) ?>"
                  data-sname="<?= htmlspecialchars($owner['own_surname']) ?>"
                  data-tin="<?= htmlspecialchars($owner['tin_no'] ?? '') ?>"
                  data-house="<?= htmlspecialchars($owner['house_no']) ?>"
                  data-street="<?= htmlspecialchars($owner['street']) ?>"
                  data-barangay="<?= htmlspecialchars($owner['barangay']) ?>"
                  data-district="<?= htmlspecialchars($owner['district']) ?>"
                  data-city="<?= htmlspecialchars($owner['city']) ?>"
                  data-province="<?= htmlspecialchars($owner['province']) ?>">
                  EDIT
                </button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No records found</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="d-flex align-items-center mt-3">
        <a href="javascript:void(0)" id="backBtn" class="mr-2">
      <i class="fas fa-chevron-left"></i>
    </a>
    <span class="mr-2">Page:</span>
    <select id="pageSelect" class="form-control form-control-sm w-auto mr-2"></select>
    <a href="javascript:void(0)" id="nextBtn" class="ml-2">
      <i class="fas fa-chevron-right"></i>
    </a>
    </div>
      <!-- View All Button -->
      <div class="d-flex justify-content-between mt-3">
        <a href="Add_POwner.php" class="btn btn-success add-owner-button">Add Owner</a>
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewAllModal">View All</button>
      </div>
  </section>


 <!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">
      
      <!-- Header -->
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title fw-bold" id="editModalLabel">Edit Owner Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body">
        <form class="needs-validation" novalidate>

          <!-- Basic Information -->
          <h6 class="fw-bold border-bottom pb-2 mb-3">Basic Information</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="firstName" class="form-label">First Name</label>
              <input type="text" class="form-control" id="firstName" maxlength="20">
            </div>
            <div class="col-md-4">
              <label for="middleName" class="form-label">Middle Name</label>
              <input type="text" class="form-control" id="middleName" maxlength="20">
            </div>
            <div class="col-md-4">
              <label for="surname" class="form-label">Surname</label>
              <input type="text" class="form-control" id="surname" maxlength="20">
            </div>
            <div class="col-md-6">
              <label for="tinNo" class="form-label">TIN No.</label>
              <input type="text" class="form-control" id="tinNo" maxlength="15">
            </div>
          </div>

          <!-- Address Information -->
          <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3">Address</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="houseNumber" class="form-label">House Number</label>
              <input type="text" class="form-control" id="houseNumber" maxlength="10">
            </div>
            <div class="col-md-4">
              <label for="street" class="form-label">Street</label>
              <input type="text" class="form-control" id="street" maxlength="50">
            </div>
            <div class="col-md-4">
              <label for="barangay" class="form-label"><span class="text-danger">*</span> Barangay</label>
              <select class="form-select" id="barangay" name="barangay" required>
                <option value="" selected disabled>Select Barangay</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="district" class="form-label"><span class="text-danger">*</span> District</label>
              <select class="form-select" id="district" name="district" required>
                <option value="" selected disabled>Select District</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="city" class="form-label"><span class="text-danger">*</span> Municipality / City</label>
              <select class="form-select" id="city" name="city" required>
                <option value="" selected disabled>Select Municipality</option>
                <?php while ($row = $municipalities_result->fetch_assoc()) { ?>
                  <option value="<?= htmlspecialchars($row['m_id']) ?>">
                    <?= htmlspecialchars($row['m_description']) ?>
                  </option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-4">
              <label for="province" class="form-label">Province</label>
              <input type="text" class="form-control" id="province" value="Camarines Norte" readonly>
            </div>
          </div>

          <!-- Contact Information -->
          <h6 class="fw-bold border-bottom pb-2 mt-4 mb-3">Contact Information</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="telephone" class="form-label">Telephone</label>
              <input type="text" class="form-control" id="telephone" maxlength="11">
            </div>
            <div class="col-md-4">
              <label for="fax" class="form-label">Fax</label>
              <input type="text" class="form-control" id="fax" maxlength="15">
            </div>
            <div class="col-md-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" maxlength="50">
            </div>
            <div class="col-12">
              <label for="website" class="form-label">Website</label>
              <input type="text" class="form-control" id="website" maxlength="100">
            </div>
          </div>

        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>

    </div>
  </div>
</div>



<!-- View All Modal -->
<div class="modal fade" id="viewAllModal" tabindex="-1" aria-labelledby="viewAllModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewAllModalLabel">All Owners</h5>
      </div>
      <div class="modal-body">
        <!-- Search Bar and Button Below the Title -->
        <div class="input-group mb-3" style="max-width: 300px;">
          <input type="text" class="form-control" id="modalSearchInput" placeholder="Search by Name or Address" onkeyup="handleModalSearch(event)">
          <div class="input-group-append">
            <button class="btn btn-success" type="button" onclick="viewAllSearch()">Search</button>
          </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
          <table class="table table-bordered text-center modern-table" id="modalTable">
            <thead class="thead-dark">
              <tr>
                <th class="text-center align-middle">ID</th>
                <th class="text-center align-middle">Name</th>
                <th class="text-center align-middle">Address <br><small>House No., Street, District</small></th>
                <th class="text-center align-middle">Information</th>
              </tr>
            </thead>
            <tbody id="modalTableBody">
              <?php if (!empty($owners)): ?>
                <?php foreach ($owners as $owner): ?>
                  <tr>
                    <td><?= htmlspecialchars($owner['own_id']) ?></td>
                    <td><?= htmlspecialchars($owner['own_fname'] . ' ' . $owner['own_mname'] . ' ' . $owner['own_surname']) ?></td>
                    <td><?= htmlspecialchars($owner['house_no'] . ', ' . $owner['street'] . ', ' . $owner['barangay'] . ', ' . $owner['city'] . ', ' . $owner['district'] . ', ' . $owner['province']) ?></td>
                    <td><?= htmlspecialchars($owner['own_info']) ?></td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="4">No records found</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>



  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>

  <!-- jQuery (latest version) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" 
        crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>      

  <script src="http://localhost/ERPTS/Own_list.js"></script>


  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Capitalize first letter of text input fields
      function capitalizeInput(event) {
        event.target.value = event.target.value.replace(/\b\w/g, function(char) {
          return char.toUpperCase();
        });
      }

      // Add event listeners to text input fields
      const textInputs = document.querySelectorAll(
        "#firstName, #middleName, #surname, #street, #barangay, #district, #city, #province"
      );

      textInputs.forEach(input => {
        input.addEventListener("input", capitalizeInput);
      });

      // Limit phone number and fax to 11 digits
      function limitPhoneFax(event) {
        let value = event.target.value;
        if (value.length > 11) {
          event.target.value = value.slice(0, 11); // Limit to 11 characters
        }
      }

      // Add event listeners to phone and fax input fields
      const phoneFaxInputs = document.querySelectorAll("#telephone, #fax");

      phoneFaxInputs.forEach(input => {
        input.addEventListener("input", (event) => {
          // Allow only numbers
          event.target.value = event.target.value.replace(/[^0-9]/g, "");
          limitPhoneFax(event);
        });
      });

      // Handle modal data population
      $('#editModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget); // Button that triggered the modal

        // Extract data attributes
        const id = button.data('id');
        const fname = button.data('fname');
        const mname = button.data('mname');
        const sname = button.data('sname');
        const tin = button.data('tin');
        const house = button.data('house');
        const street = button.data('street');
        const barangay = button.data('barangay');
        const district = button.data('district');
        const city = button.data('city');
        const province = button.data('province');

        // Populate the modal fields
        const modal = $(this);
        modal.find('#firstName').val(fname);
        modal.find('#middleName').val(mname);
        modal.find('#surname').val(sname);
        modal.find('#tinNo').val(tin);
        modal.find('#houseNumber').val(house);
        modal.find('#street').val(street);
        modal.find('#barangay').val(barangay);
        modal.find('#district').val(district);
        modal.find('#city').val(city);
        modal.find('#province').val(province);
      });

      $('#editModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const id = button.data('id');
        const fname = button.data('fname');
        const mname = button.data('mname');
        const sname = button.data('sname');
        const tin = button.data('tin');
        const house = button.data('house');
        const street = button.data('street');
        const barangay = button.data('barangay');
        const district = button.data('district');
        const city = button.data('city');
        const province = button.data('province');

        // Populate the modal with current data
        $('#firstName').val(fname);
        $('#middleName').val(mname);
        $('#surname').val(sname);
        $('#tinNo').val(tin);
        $('#houseNumber').val(house);
        $('#street').val(street);
        $('#barangay').val(barangay);
        $('#district').val(district);
        $('#city').val(city);
        $('#province').val(province);

        // Add event listener to save changes
        $('#editModal .btn-primary').on('click', function() {
          const updatedData = {
            own_id: id,
            own_fname: $('#firstName').val(),
            own_mname: $('#middleName').val(),
            own_surname: $('#surname').val(),
            tin_no: $('#tinNo').val(),
            house_no: $('#houseNumber').val(),
            street: $('#street').val(),
            barangay: $('#barangay').val(),
            district: $('#district').val(),
            city: $('#city').val(),
            province: $('#province').val()
          };

          $.ajax({
            url: 'ownListUpdate.php',
            type: 'POST',
            data: updatedData,
            success: function(response) {
              console.log(response); // Log the response for debugging
              if (response === 'success') {
                alert('Owner information updated successfully!');
                location.reload(); // Reload the page to reflect changes
              } else {
                alert('Error updating owner information: ' + response); // Show the error message from the response
              }
            },
            error: function(xhr, status, error) {
              console.log(xhr.responseText); // Log any response errors
              alert('An error occurred while saving the data: ' + error); // Show the error details
            }
          });
        });
      });
    });
  </script>
</body>

</html>