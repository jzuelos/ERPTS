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
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Own_list.css">
  <link rel="stylesheet" href="Real-Property-Unit-List.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="d-inline-block align-top"
        alt="">
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
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item active" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
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
<section class="container mt-5">
  <div class="card p-4">
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
      <table class="table table-bordered text-center modern-table" id="propertyTable">
        <thead class="thead-dark">
          <tr>
            <th class="text-center align-middle">ID</th>
            <th class="text-center align-middle">Name</th>
            <th class="text-center align-middle">Address <br><small>House No., Street, District</small></th>
            <th class="text-center align-middle">Information</th>
            <th class="text-center align-middle">Edit</th>
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
                    data-toggle="modal"
                    data-target="#editModal"
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
      <a href="javascript:void(0)" id="backBtn" class="mr-2"><<<</a>
      <span class="mr-2">Page:</span>
      <select id="pageSelect" class="form-control form-control-sm w-auto mr-2"></select>
      <a href="javascript:void(0)" id="nextBtn" class="ml-2">>></a>
    </div>
      <!-- View All Button -->
      <div class="d-flex justify-content-between mt-3">
        <a href="Add_POwner.php" class="btn btn-success add-owner-button">Add Owner</a>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#viewAllModal">View All</button>
      </div>
  </section>


  <!-- Edit Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Owner Information</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Basic Information -->
          <h5>Basic Information</h5>
          <form>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" placeholder="Enter first name">
              </div>
              <div class="form-group col-md-4">
                <label for="middleName">Middle Name</label>
                <input type="text" class="form-control" id="middleName" placeholder="Enter middle name">
              </div>
              <div class="form-group col-md-4">
                <label for="surname">Surname</label>
                <input type="text" class="form-control" id="surname" placeholder="Enter surname">
              </div>
            </div>
            <div class="form-group">
              <label for="tinNo">TIN No.</label>
              <input type="text" class="form-control" id="tinNo" placeholder="Enter TIN number">
            </div>

            <!-- Address Fields -->
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="houseNumber">House Number</label>
                <input type="text" class="form-control" id="houseNumber" placeholder="Enter house number">
              </div>
              <div class="form-group col-md-6">
                <label for="street">Street</label>
                <input type="text" class="form-control" id="street" placeholder="Enter street">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="barangay">Barangay</label>
                <input type="text" class="form-control" id="barangay" placeholder="Enter barangay">
              </div>
              <div class="form-group col-md-6">
                <label for="district">District</label>
                <input type="text" class="form-control" id="district" placeholder="Enter district">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" placeholder="Enter city">
              </div>
              <div class="form-group col-md-6">
                <label for="province">Province</label>
                <input type="text" class="form-control" id="province" placeholder="Enter province">
              </div>
            </div>

            <!-- Owner Information -->
            <h5>Owner Information</h5>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="telephone">Telephone</label>
                <input type="text" class="form-control" id="telephone" placeholder="Enter telephone number">
              </div>
              <div class="form-group col-md-4">
                <label for="fax">Fax</label>
                <input type="text" class="form-control" id="fax" placeholder="Enter fax number">
              </div>
              <div class="form-group col-md-4">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email address">
              </div>
            </div>
            <div class="form-group">
              <label for="website">Website</label>
              <input type="text" class="form-control" id="website" placeholder="Enter website">
            </div>
          </form>
        </div>
        <div class="modal-footer">
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- jQuery (latest version) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap 4.5.2 JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- Your custom JS files -->
  <script src="http://localhost/ERPTS/main_layout.js"></script>
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