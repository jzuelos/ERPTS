<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>Add New Real Property Unit</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Add-New-Real-Property-Unit.css">
</head>

<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">

  <?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);

  require_once 'database.php';

  $conn = Database::getInstance();

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    echo "Connected";
  }

  session_start(); // Start the session at the top of your script
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $house_number = filter_input(INPUT_POST, 'house_number', FILTER_SANITIZE_NUMBER_INT);
    $block_number = filter_input(INPUT_POST, 'block_number', FILTER_SANITIZE_NUMBER_INT);
    $province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $district = filter_input(INPUT_POST, 'district', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $barangay = filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $house_tag = filter_input(INPUT_POST, 'house_tag_number', FILTER_SANITIZE_NUMBER_INT);
    $land_area = filter_input(INPUT_POST, 'land_area', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $desc_land = htmlspecialchars($_POST['lot_no'], ENT_QUOTES) . ' ' .
      htmlspecialchars($_POST['zone_no'], ENT_QUOTES) . ' ' .
      htmlspecialchars($_POST['block_no'], ENT_QUOTES) . ' ' .
      htmlspecialchars($_POST['psd'], ENT_QUOTES);

    $documents = isset($_POST['documents']) && is_array($_POST['documents']) ? implode(", ", $_POST['documents']) : '';

    $stmt = $conn->prepare("INSERT INTO p_info (house_no, block_no, province, city, district, barangay, house_tag_no, land_area, desc_land, documents) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $house_number, $block_number, $province, $city, $district, $barangay, $house_tag, $land_area, $desc_land, $documents);

    if ($stmt->execute()) {
      // Set confirmation message
      $_SESSION['message'] = "Property Added";
      // Redirect to avoid re-submission
      header("Location: " . $_SERVER['PHP_SELF']);
      exit;
    } else {
      echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
  }

  // Display confirmation message
  if (isset($_SESSION['message'])) {
    echo "<p>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Clear the message after displaying
  }

  $conn->close();
  ?>


  <header class="u-clearfix u-custom-color-1 u-header u-header" id="sec-34db"><a href="#"
      class="u-image u-logo u-image-1" data-image-width="2000" data-image-height="2000">
      <img src="images/coconut_.__1_-removebg-preview1.png" class="u-logo-image u-logo-image-1">
    </a>
    <nav class="u-dropdown-icon u-menu u-menu-dropdown u-offcanvas u-menu-1" data-responsive-from="MD">
      <div class="menu-collapse" style="font-size: 0.875rem; letter-spacing: 0px; font-weight: 500;">
        <a class="u-button-style u-custom-active-border-color u-custom-active-color u-custom-border u-custom-border-color u-custom-borders u-custom-color u-custom-hover-border-color u-custom-hover-color u-custom-left-right-menu-spacing u-custom-padding-bottom u-custom-text-active-color u-custom-text-color u-custom-text-decoration u-custom-text-hover-color u-custom-top-bottom-menu-spacing u-nav-link u-text-active-palette-1-base u-text-hover-palette-2-base"
          href="#">
          <svg class="u-svg-link" viewBox="0 0 24 24">
            <use xlink:href="#menu-hamburger"></use>
          </svg>
          <svg class="u-svg-content" version="1.1" id="menu-hamburger" viewBox="0 0 16 16" x="0px" y="0px"
            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
            <g>
              <rect y="1" width="16" height="2"></rect>
              <rect y="7" width="16" height="2"></rect>
              <rect y="13" width="16" height="2"></rect>
            </g>
          </svg>
        </a>
      </div>
      <div class="u-custom-menu u-nav-container">
        <ul class="u-nav u-spacing-2 u-unstyled u-nav-1">
          <li class="u-nav-item"><a
              class="u-border-3 u-border-active-palette-3-base u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-light-1 u-text-hover-white"
              href="Home.html" style="padding: 10px 20px;">Home</a>
          </li>
          <li class="u-nav-item"><a
              class="u-border-3 u-border-active-palette-3-base u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-light-1 u-text-hover-white"
              href="RPU-Management.html" style="padding: 10px 20px;">RPU Management</a>
            <div class="u-nav-popup">
              <ul class="u-border-1 u-border-grey-75 u-h-spacing-11 u-nav u-unstyled u-v-spacing-1 u-nav-2">
                <li class="u-nav-item"><a
                    class="u-active-palette-3-base u-button-style u-hover-palette-3-base u-nav-link u-white"
                    href="Real-Property-Unit-List.html">Real Property Unit List</a>
                </li>
                <li class="u-nav-item"><a
                    class="u-active-palette-3-base u-button-style u-hover-palette-3-base u-nav-link u-white"
                    href="FAAS.html">FAAS</a>
                </li>
                <li class="u-nav-item"><a
                    class="u-active-palette-3-base u-button-style u-hover-palette-3-base u-nav-link u-white"
                    href="Tax-Declaration-List.html">Tax Declaration List</a>
                </li>
                <li class="u-nav-item"><a
                    class="u-active-palette-3-base u-button-style u-hover-palette-3-base u-nav-link u-white"
                    href="Track.html">Track</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="u-nav-item"><a
              class="u-border-3 u-border-active-palette-3-base u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-light-1 u-text-hover-white"
              href="Transaction.html" style="padding: 10px 20px;">Transaction</a>
          </li>
          <li class="u-nav-item"><a
              class="u-border-3 u-border-active-palette-3-base u-border-hover-palette-3-base u-border-no-left u-border-no-right u-border-no-top u-button-style u-nav-link u-text-active-palette-3-light-1 u-text-hover-white"
              href="Reports.html" style="padding: 10px 20px;">Reports</a>
          </li>
        </ul>
      </div>
      <div class="u-custom-menu u-nav-container-collapse">
        <div class="u-black u-container-style u-inner-container-layout u-opacity u-opacity-95 u-sidenav">
          <div class="u-inner-container-layout u-sidenav-overflow">
            <div class="u-menu-close"></div>
            <ul class="u-align-center u-nav u-popupmenu-items u-unstyled u-nav-3">
              <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Home.html">Home</a>
              </li>
              <li class="u-nav-item"><a class="u-button-style u-nav-link" href="RPU-Management.html">RPU Management</a>
                <div class="u-nav-popup">
                  <ul class="u-border-1 u-border-grey-75 u-h-spacing-11 u-nav u-unstyled u-v-spacing-1 u-nav-4">
                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Real-Property-Unit-List.html">Real
                        Property Unit List</a>
                    </li>
                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="FAAS.html">FAAS</a>
                    </li>
                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Tax-Declaration-List.html">Tax
                        Declaration List</a>
                    </li>
                    <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Track.html">Track</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Transaction.html">Transaction</a>
              </li>
              <li class="u-nav-item"><a class="u-button-style u-nav-link" href="Reports.html">Reports</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="u-black u-menu-overlay u-opacity u-opacity-70"></div>
      </div>
      <style class="menu-style">
        @media (max-width: 939px) {
          [data-responsive-from="MD"] .u-nav-container {
            display: none;
          }

          [data-responsive-from="MD"] .menu-collapse {
            display: block;
          }
        }
      </style>
    </nav>
    <p class="u-custom-font u-heading-font u-text u-text-default u-text-1">Electronic Real Property Tax System</p>
  </header>
  <section class="u-clearfix u-section-1" id="sec-4f2c">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <h2 class="u-text u-text-default u-text-1">Property Information</h2>
    </div>
  </section>
  <!-- 1st Section -->
  <section
    class="u-align-center u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-clearfix u-container-align-center u-section-2"
    id="sec-ffed">

    <!-- Add New ERPTS -->
    <div class="u-clearfix u-sheet u-sheet-1">
      <div class="u-form u-form-1">
        <form action="" id="propertyForm" class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form"
          method="POST" style="padding: 10px;" onsubmit="return validateForm();">
          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-1"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="text-93dc" class="u-label">Location of Property</label>
            <input type="number" placeholder="House Number" id="house_number" name="house_number"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-13">
            <label for="text-2f40" class="u-form-control-hidden u-label"></label>
            <input type="number" id="block_number" name="block_number" class="u-input u-input-rectangle"
              placeholder="Block Number">
          </div>

          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-6">
            <label for="select-11f0" class="u-form-control-hidden u-label"></label>
            <div class="u-form-select-wrapper">
              <select id="province" name="province" class="u-input u-input-rectangle" required>
                <option value="Province" data-calc="" selected="selected">Province</option>
                <option value="Item 2" data-calc="">Item 2</option>
                <option value="Item 3" data-calc="">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-7">
            <label for="select-7617" class="u-form-control-hidden u-label"></label>
            <div class="u-form-select-wrapper">
              <select id="city" name="city" class="u-input u-input-rectangle">
                <option value="(City)" data-calc="">City</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-6">
            <label for="select-11f0" class="u-form-control-hidden u-label"></label>
            <div class="u-form-select-wrapper">
              <select id="district" name="district" class="u-input u-input-rectangle">
                <option value="District" data-calc="" selected="selected">District</option>
                <option value="Item 2" data-calc="">Item 2</option>
                <option value="Item 3" data-calc="">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-7">
            <label for="select-7617" class="u-form-control-hidden u-label"></label>
            <div class="u-form-select-wrapper">
              <select id="barangay" name="barangay" class="u-input u-input-rectangle">
                <option value="(City)" data-calc="">Barangay</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>

          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-1"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="text-93dc" class="u-label">House Tag Number</label>
            <input type="number" placeholder="House Tag Number" id="house_tag_number" name="house_tag_number"
              class="u-input u-input-rectangle">
          </div>

          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-1"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="text-93dc" class="u-label">Land Area</label>
            <input type="number" placeholder="Land Area" id="land_area" name="land_area"
              class="u-input u-input-rectangle" required>
          </div>

          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-1"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-11">
            <label for="text-4ef3" class="u-label">Description of Land</label>
            <input type="number" placeholder="Lot Number" id="lot_no" name="lot_no" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="text-93dc" class="u-form-control-hidden u-label"></label>
            <input type="number" placeholder="Zone Number" id="zone_no" name="zone_no"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-13">
            <label for="text-2f40" class="u-form-control-hidden u-label"></label>
            <input type="number" id="block_no" name="block_no" class="u-input u-input-rectangle"
              placeholder="Block Number">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-14">
            <label for="text-7d2b" class="u-form-control-hidden u-label"></label>
            <input type="number" placeholder="Psd13" id="psd" name="psd" class="u-input u-input-rectangle">
          </div>

          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-2"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-11">

            <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-11">
              <label for="text-4ef3" class="u-label">Documents</label>

              <div class="u-form-checkbox">
                <input type="checkbox" id="cb_affidavit" name="documents[]" value="affidavit">
                <label for="cb_affidavit">&nbsp;&nbsp;&nbsp;Affidavit of Ownership</label>
              </div>

              <div class="u-form-checkbox">
                <input type="checkbox" id="cb_barangay" name="documents[]" value="barangay">
                <label for="cb_barangay">&nbsp;&nbsp;&nbsp;Barangay Certificate</label>
              </div>

              <div class="u-form-checkbox">
                <input type="checkbox" id="cb_tag" name="documents[]" value="land_tagging">
                <label for="cb_tag">&nbsp;&nbsp;&nbsp;Land Tagging</label>
              </div>

            </div>

          </div>

          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-2"></div>
          <label for="text-4ef3" class="u-label">Owner</label>
          <table class="u-table">
            <thead>
              <tr>
                <th class="u-table-header">Name</th>
                <th class="u-table-header">Address</th>
                <th class="u-table-header">Check</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Row 1, Cell 1</td>
                <td>Row 1, Cell 2</td>
                <td>Row 1, Cell 3</td>
              </tr>
              <tr>
                <td>Row 2, Cell 1</td>
                <td>Row 2, Cell 2</td>
                <td>Row 2, Cell 3</td>
              </tr>
              <tr>
                <td>Row 3, Cell 1</td>
                <td>Row 3, Cell 2</td>
                <td>Row 3, Cell 3</td>
              </tr>
            </tbody>
          </table>

          <div class="button-group" style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit"
              class="u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-radius">Submit</button>
            <button type="button"
              class="clear-button u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-radius">Clear</button>
            <a href="Real-Property-Unit-List.html" class="u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-radius">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="u-align-center u-clearfix u-container-align-center u-footer u-grey-80 u-footer" id="sec-7e36">
    <div class="u-clearfix u-sheet u-sheet-1">
      <p class="u-small-text u-text u-text-variant u-text-1"></p>
    </div>
  </footer>

  <script src="Add-New-Real-Property-Unit.js"></script>

</body>

</html>