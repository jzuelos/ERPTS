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
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <?php
  session_start(); // Start session at the top
  
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once 'database.php';

  $conn = Database::getInstance();
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    echo "Connected";
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $house_number = filter_input(INPUT_POST, 'house_number', FILTER_SANITIZE_NUMBER_INT);
    $block_number = filter_input(INPUT_POST, 'block_number', FILTER_SANITIZE_NUMBER_INT);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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

    if ($house_number && $city) {
      $stmt = $conn->prepare("INSERT INTO p_info (house_no, block_no, province, city, district, barangay, house_tag_no, land_area, desc_land, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      if ($stmt) {
        $stmt->bind_param("ssssssssss", $house_number, $block_number, $province, $city, $district, $barangay, $house_tag, $land_area, $desc_land, $documents);

        if ($stmt->execute()) {
          $_SESSION['message'] = "Property Added";
          header("Location: " . $_SERVER['PHP_SELF']);
          exit;
        } else {
          echo "<p>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
      } else {
        echo "<p>Error preparing statement: " . $conn->error . "</p>";
      }
    } else {
      echo "<p>Error: House number and city are required.</p>";
    }
  }

  if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']);
  }
  ?>

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
      <ul class="navbar-nav ml-auto"> <!-- Use ml-auto to align items to the right -->
        <li class="nav-item">
          <a class="nav-link" href="Home.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration-List.php">Tax Declaration</a>
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
        <li class="nav-item" style="margin-left: 20px">
          <button type="button" class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off">
            Log Out</button>
        </li>
      </ul>
    </div>
  </nav>

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
          <form action="" method="GET" id="ownerSearchForm">
            <label for="owner_search" class="form-label">Search for Owner</label>
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
              <th>ID</th>
              <th>Owner Name</th>
              <th>Address</th>
              <th>Select</th>
            </tr>
          </thead>
          <tbody id="resultsBody">
          
          </tbody>
        </table>


        <form action="" id="propertyForm" method="POST" onsubmit="return validateForm();">

          <!-- Location of Property -->
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="house_number" class="form-label">Location of Property</label>
              <input type="number" id="house_number" name="house_number" class="form-control" placeholder="House Number"
                required>
            </div>
            <div class="col-md-6">
              <label for="block_number" class="form-label">Block Number</label>
              <input type="number" id="block_number" name="block_number" class="form-control"
                placeholder="Block Number">
            </div>
          </div>

          <!-- Province, City, District, Barangay -->
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="province" class="form-label">Province</label>
              <select id="province" name="province" class="form-select" required>
                <option value="" disabled selected>Select Province</option>
                <option value="Province 1">Province 1</option>
                <option value="Province 2">Province 2</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="city" class="form-label">City</label>
              <select id="city" name="city" class="form-select">
                <option value="" disabled selected>Select City</option>
                <option value="Labo">Labo</option>
                <option value="Daet">Daet</option>
              </select>
            </div>
            <div class="col-md-3">
              <label for="district" class="form-label">District</label>
              <select id="district" name="district" class="form-select" required>
                <option value="" disabled selected>Select District</option>
                <option value="District 1">District 1</option>
                <option value="District 2">District 2</option>
              </select>
            </div>

            <div class="col-md-3">
              <label for="barangay" class="form-label">Barangay</label>
              <select id="barangay" name="barangay" class="form-select">
                <option value="" disabled selected>Select Barangay</option>
                <option value="Kalamunding">Kalamunding</option>
                <option value="Bautista">Bautista</option>
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
              <label for="land_area" class="form-label">Land Area (sq. m)</label>
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
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <script src="http://localhost/ERPTS/Add-New-Real-Property-Unit.js"></script>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
</body>

</html>