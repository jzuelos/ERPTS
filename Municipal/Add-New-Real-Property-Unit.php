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
  <link rel="stylesheet" href="../main_layout.css">
  <link rel="stylesheet" href="../header.css">
  <link rel="stylesheet" href="Home.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <div id="selectedOwnerDisplay"></div> <!-- Display area for selected owner IDs -->
  <?php
  session_start(); // Start session at the top

  // Prevent the browser from caching this page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
  header("Cache-Control: post-check=0, pre-check=0", false);
  header("Pragma: no-cache");

  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once '../database.php';

  $conn = Database::getInstance();
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if form is submitted via POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $house_number = filter_input(INPUT_POST, 'house_number', FILTER_SANITIZE_NUMBER_INT);
    $block_number = filter_input(INPUT_POST, 'block_number', FILTER_SANITIZE_NUMBER_INT);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = filter_input(INPUT_POST, 'municipality', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $house_tag = filter_input(INPUT_POST, 'house_tag_number', FILTER_SANITIZE_NUMBER_INT);
    $land_area = filter_input(INPUT_POST, 'land_area', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $lot_no = isset($_POST['lot_no']) ? htmlspecialchars($_POST['lot_no'], ENT_QUOTES) : '';
    $zone_no = isset($_POST['zone_no']) ? htmlspecialchars($_POST['zone_no'], ENT_QUOTES) : '';
    $block_no = isset($_POST['block_no']) ? htmlspecialchars($_POST['block_no'], ENT_QUOTES) : '';
    $psd = isset($_POST['psd']) ? htmlspecialchars($_POST['psd'], ENT_QUOTES) : '';

    $desc_land = "$lot_no $zone_no $block_no $psd";
    $documents = isset($_POST['documents']) ? implode(', ', $_POST['documents']) : '';

    $selected_owner_ids = isset($_POST['selected_owner_ids']) ? explode(',', $_POST['selected_owner_ids']) : [];
    $selected_owner_ids = array_map('intval', $selected_owner_ids);

    // Ensure house number and city are provided
    if ($house_number && $city) {
      $conn->begin_transaction();

      try {
        // Insert property data into p_info table
        $stmt = $conn->prepare("INSERT INTO p_info (house_no, block_no, province, city, district, barangay, house_tag_no, land_area, desc_land, documents, ownID_Fk) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
          $owner_id = !empty($selected_owner_ids) ? $selected_owner_ids[0] : null;
          $stmt->bind_param("iissssiissi", $house_number, $block_number, $province, $city, $district, $barangay, $house_tag, $land_area, $desc_land, $documents, $owner_id);

          if ($stmt->execute()) {
            $property_id = $stmt->insert_id; // Get last inserted ID
            $_SESSION['last_property_id'] = $property_id; // Store it in session

            // Insert owners into propertyowner table and collect propertyowner_ids
            $propertyowner_ids = [];
            if (!empty($selected_owner_ids)) {
              foreach ($selected_owner_ids as $owner_id) {
                // Ensure the owner exists
                $check_owner_stmt = $conn->prepare("SELECT 1 FROM owners_tb WHERE own_id = ?");
                $check_owner_stmt->bind_param("i", $owner_id);
                $check_owner_stmt->execute();
                $check_owner_stmt->store_result();

                if ($check_owner_stmt->num_rows > 0) {
                  // Insert into propertyowner table and get the propertyowner_id
                  $owner_stmt = $conn->prepare("INSERT INTO propertyowner (property_id, owner_id) VALUES (?, ?)");
                  if ($owner_stmt) {
                    $owner_stmt->bind_param("ii", $property_id, $owner_id);
                    if ($owner_stmt->execute()) {
                      // Get the last inserted propertyowner_id
                      $propertyowner_id = $owner_stmt->insert_id;
                      $propertyowner_ids[] = $propertyowner_id; // Collect owner IDs
                    } else {
                      throw new Exception("Error inserting into propertyowner: " . $owner_stmt->error);
                    }
                    $owner_stmt->close();
                  } else {
                    throw new Exception("Error preparing statement for propertyowner insertion: " . $conn->error);
                  }
                } else {
                  echo "<p>Error: Owner with ID $owner_id does not exist.</p>";
                }
                $check_owner_stmt->close();
              }
            }

            // Now insert a single FAAS record with all the owner IDs as JSON
            $faas_stmt = $conn->prepare("INSERT INTO FAAS (pro_id, propertyowner_id) VALUES (?, ?)");
            if ($faas_stmt) {
              // Convert the owner IDs array to JSON format
              $owners_json = json_encode($propertyowner_ids);

              $faas_stmt->bind_param("is", $property_id, $owners_json);
              if ($faas_stmt->execute()) {
                echo "<p>Inserted into FAAS for property_id $property_id with owners: " . implode(", ", $propertyowner_ids) . ".</p>";
              } else {
                throw new Exception("Error executing FAAS insertion: " . $faas_stmt->error);
              }
              $faas_stmt->close();
            } else {
              throw new Exception("Error preparing FAAS statement: " . $conn->error);
            }

            // Commit the transaction
            $conn->commit();
            $_SESSION['message'] = "Property Added with owner ID(s): " . htmlspecialchars(implode(", ", $selected_owner_ids));
            $_SESSION['property_added'] = true;
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
          } else {
            throw new Exception("Error: " . $stmt->error);
          }
          $stmt->close();
        } else {
          throw new Exception("Error preparing statement: " . $conn->error);
        }
      } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        echo "<p>Transaction failed: " . $e->getMessage() . "</p>";
      }
    } else {
      echo "<p>Error: House number and city are required.</p>";
    }
  }

  // Show modal after successful property addition
  if (isset($_SESSION['property_added']) && $_SESSION['property_added'] === true) {
    unset($_SESSION['property_added']);
    echo "<script>
        window.onload = function() {
            $('#confirmationModal').modal('show');
        };
    </script>";
  }

  // Display session message
  if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
  }
  ?>

  <!-- Bootstrap Modal for Confirmation -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Property Added</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Property added <br> Do you want to continue to the FAAS sheet?
        </div>
        <!-- Modal Footer with Dynamic Link -->
        <div class="modal-footer">
          <a href="FAAS.php<?php echo isset($_SESSION['last_property_id']) ? '?id=' . $_SESSION['last_property_id'] : ''; ?>"
            class="btn btn-primary">Yes</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Include Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Header Navigation -->
  <?php include '../header.php'; ?>

  <!-- Main Header -->
  <section class="text-center my-4">
    <h2 class="text-black">Property Information</h2>
  </section>

  <!-- Form Section -->
  <section class="container my-4">
    <div class="card">
      <div class="card-body">
        <!-- Owner Search Section -->
        <div class="mb-3">
          <form action="" method="POST" id="ownerSearchForm">
            <label for="owner_search" class="form-label"><span style="color: red;">*</span> Search for Owner</label>
            <div class="input-group">
              <input type="text" id="owner_search" name="search" class="form-control" placeholder="Search Owner"
                required>
              <button type="submit" class="btn btn-primary">Search</button>
              <button type="button" class="btn btn-secondary clear-button"
                onclick="clearOwnerSearchForm()">Clear</button>
            </div>
          </form>
        </div>

        <table class="table table-bordered mb-3">
          <thead class="table-light">
            <tr>
              <th class="text-center align-middle">ID</th>
              <th class="text-center align-middle">Owner Name<br><small>(Surname, Firstname)</small></th>
              <th class="text-center align-middle">Address<br><small>(Street, Barangay, City, Province)</small></th>
              <th class="text-center align-middle">Select</th>
            </tr>
          </thead>
          <tbody id="resultsBody">
            <?php
            // Include the database connection
            require_once '../database.php';

            // Get the database connection
            $conn = Database::getInstance();

            // Fetch initial data
            $stmt = $conn->prepare("SELECT * FROM owners_tb ORDER BY own_surname ASC, own_fname ASC LIMIT 5");
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                $ownerId = htmlspecialchars($row['own_id'], ENT_QUOTES);
                $fullName = htmlspecialchars($row['own_fname'] . ', ' . $row['own_surname'], ENT_QUOTES);
                $address = htmlspecialchars($row['street'] . ', ' . $row['barangay'] . ', ' . $row['city'] . ', ' . $row['province'], ENT_QUOTES);

                // Output each row
                echo "<tr>";
                echo "<td class='text-center align-middle'>" . $ownerId . "</td>";
                echo "<td class='text-center align-middle'>" . $fullName . "</td>";
                echo "<td class='text-center align-middle'>" . $address . "</td>";
                echo "<td class='text-center align-middle'><input type='checkbox' name='selected_ids[]' value='" . $ownerId . "'></td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='4' class='text-center'>No data found</td></tr>";
            }

            $stmt->close();
            ?>
          </tbody>
        </table>

        <form action="" id="propertyForm" method="POST" onsubmit="return validateForm();">
          <input type="hidden" name="selected_owner_ids" id="selected_owner_ids" />

          <!-- Location of Property -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="house_number" class="form-label"><span style="color: red;">*</span> Location of
                Property</label>
              <input type="number" id="house_number" name="house_number" class="form-control" placeholder="House Number"
                required>
            </div>
            <div class="col-md-6">
              <label for="block_number" class="form-label">Block Number</label>
              <input type="number" id="block_number" name="block_number" class="form-control"
                placeholder="Block Number">
            </div>
          </div>

          <?php
          // Get the database connection
          $conn = Database::getInstance();

          // Fetch active provinces
          $stmt = $conn->prepare("SELECT province_id, province_name FROM province");
          $stmt->execute();
          $regions_result = $stmt->get_result();

          // Fetch active municipalities with their district names
          $municipalities_stmt = $conn->prepare("
            SELECT 
              municipality.m_id, 
              municipality.m_description, 
              district.description AS district_name 
            FROM municipality
            LEFT JOIN district ON municipality.m_id = district.m_id 
            WHERE municipality.m_status = 'Active'
        ");
          $municipalities_stmt->execute();
          $municipalities_result = $municipalities_stmt->get_result();

          // Fetch active barangays
          $barangays_stmt = $conn->prepare("SELECT brgy_id, brgy_name FROM brgy WHERE status = 'Active'");
          $barangays_stmt->execute();
          $barangays_result = $barangays_stmt->get_result();
          ?>
          <!-- Province Dropdown -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="province" class="form-label">Province</label>
              <select class="form-control" id="province" name="province" required>
                <option value="" disabled selected>Select Province</option>
                <?php
                while ($row = $regions_result->fetch_assoc()) {
                  echo "<option value='" . htmlspecialchars($row['province_id'], ENT_QUOTES) . "'>" . htmlspecialchars($row['province_name'], ENT_QUOTES) . "</option>";
                }
                ?>
              </select>
            </div>

            <!-- Municipality Dropdown -->
            <div class="col-md-3">
              <label for="municipality" class="form-label">Municipality</label>
              <select class="form-control" id="municipality" name="municipality" required>
                <option value="" disabled selected>Select Municipality</option>
                <?php
                while ($row = $municipalities_result->fetch_assoc()) {
                  $m_id = htmlspecialchars($row['m_id'], ENT_QUOTES);
                  $municipality = htmlspecialchars($row['m_description'], ENT_QUOTES);
                  $district = htmlspecialchars($row['district_name'], ENT_QUOTES);
                  echo "<option value='$m_id' data-district='$district'>$municipality</option>";
                }
                ?>
              </select>
            </div>


            <!-- District (auto-filled) -->
            <div class="col-md-3">
              <label for="district" class="form-label">District</label>
              <input type="text" class="form-control" id="district" name="district" readonly placeholder="Auto-filled from Municipality">
            </div>

            <!-- Barangay Dropdown -->
            <div class="col-md-3">
              <label for="barangay" class="form-label">Barangay</label>
              <select class="form-control" id="barangay" name="barangay" required>
                <option value="" disabled selected>Select Barangay</option>
                <?php
                if ($barangays_result && $barangays_result->num_rows > 0) {
                  while ($row = $barangays_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['brgy_id']) . "'>" . htmlspecialchars($row['brgy_name']) . "</option>";
                  }
                } else {
                  echo "<option disabled>No active barangays</option>";
                }
                ?>
              </select>
            </div>
          </div>

          <!-- House Tag Number and Land Area -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="house_tag_number" class="form-label">House Tag Number</label>
              <input type="number" id="house_tag_number" name="house_tag_number" class="form-control"
                placeholder="House Tag Number">
            </div>
            <div class="col-md-6">
              <label for="land_area" class="form-label"><span style="color: red;">*</span> Land Area (sq. m)</label>
              <input type="number" id="land_area" name="land_area" class="form-control" placeholder="Land Area"
                required>
            </div>
          </div>

          <!-- Description of Land -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="lot_no" class="form-label">Lot Number</label>
              <input type="number" id="lot_no" name="lot_no" class="form-control" placeholder="Lot Number">
            </div>
            <div class="col-md-6">
              <label for="zone_no" class="form-label">Zone Number</label>
              <input type="number" id="zone_no" name="zone_no" class="form-control" placeholder="Zone Number">
            </div>
          </div>

          <!-- Documents -->
          <fieldset class="border p-3 mb-3">
            <legend class="w-auto">Documents</legend>
            <div class="form-check">
              <input type="checkbox" id="cb_affidavit" name="documents[]" value="affidavit" class="form-check-input">
              <label for="cb_affidavit" class="form-check-label">Affidavit of Ownership</label>
            </div>
            <div class="form-check">
              <input type="checkbox" id="cb_barangay" name="documents[]" value="barangay" class="form-check-input">
              <label for="cb_barangay" class="form-check-label">Barangay Certificate</label>
            </div>
            <div class="form-check">
              <input type="checkbox" id="cb_tag" name="documents[]" value="land_tagging" class="form-check-input">
              <label for="cb_tag" class="form-check-label">Land Tagging</label>
            </div>
          </fieldset>

          <!-- Button Group -->
          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-secondary ml-2" onclick="clearMainForm()">Clear Form</button>
            <a href="Real-Property-Unit-List.php" class="btn btn-danger ml-2">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </section>


  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span>
    </div>
  </footer>

  <script src="http://localhost/ERPTS/Add-New-Real-Property-Unit.js"></script>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>

</html>