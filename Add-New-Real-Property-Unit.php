<!DOCTYPE html>
<html style="font-size: 16px;" lang="en">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8">
  <meta name="keywords" content="">
  <meta name="description" content="">
  <title>Add New Real Property Unit</title>
  <link rel="stylesheet" href="nicepage.css" media="screen">
  <link rel="stylesheet" href="Add-New-Real-Property-Unit.css" media="screen">
  <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
  <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
  <meta name="generator" content="Nicepage 6.18.5, nicepage.com">
  <meta name="referrer" content="origin">
  <link id="u-theme-google-font" rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">

  <script type="application/ld+json">{
    "@context": "http://schema.org",
    "@type": "Organization",
    "name": "Site 2 (Orig)",
    "logo": "images/coconut_.__1_-removebg-preview1.png"
}</script>
  <meta name="theme-color" content="#478ac9">
  <meta property="og:title" content="Add New Real Property Unit">
  <meta property="og:description" content="">
  <meta property="og:type" content="website">
  <meta data-intl-tel-input-cdn-path="intlTelInput/">
</head>

<body data-path-to-root="./" data-include-products="false" class="u-body u-xl-mode" data-lang="en">

  <?php
  require_once 'database.php'; // Include the database connection class
  
  // Get the database connection
  $conn = Database::getInstance();

  // Check if the connection is successful
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } else {
    echo "Connected successfully"; // This will confirm a successful connection
  }
  
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
      <h2 class="u-text u-text-default u-text-1">Add New Real Property Unit</h2>
    </div>
  </section>
  <section
    class="u-align-center u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-clearfix u-container-align-center u-section-2"
    id="sec-ffed">
    <!-- Add New ERPTS -->
    <div class="u-clearfix u-sheet u-sheet-1">
      <div class="u-form u-form-1">
        <form action=""
          class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" name="form"
          style="padding: 10px;">
          <div class="u-form-group u-form-name u-form-partition-factor-3 u-label-top">
            <label for="name-5668" class="u-label">Last Name</label>
            <input type="text" id="name-5668" name="name" class="u-input u-input-rectangle" required="">
          </div>
          <div class="u-form-email u-form-group u-form-partition-factor-3 u-label-top">
            <label for="email-5668" class="u-label">First Name</label>
            <input type="email" id="email-5668" name="email" class="u-input u-input-rectangle" required="">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-3">
            <label for="text-71a2" class="u-label">Middle Name</label>
            <input type="text" placeholder="" id="text-71a2" name="text" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-label-top u-form-group-4">
            <label for="text-e9d2" class="u-label">Address</label>
            <input type="text" placeholder="" id="text-e9d2" name="text-1" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-label-top u-form-group-5">
            <label for="text-e2e3" class="u-label">Location of Property</label>
            <input type="text" placeholder="(Number and Street)" id="text-e2e3" name="text-2"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-6">
            <label for="select-11f0" class="u-form-control-hidden u-label"></label>
            <div class="u-form-select-wrapper">
              <select id="select-11f0" name="(Barangay)" class="u-input u-input-rectangle">
                <option value="(Barangay)" data-calc="" selected="selected">Barangay</option>
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
              <select id="select-7617" name="(City)" class="u-input u-input-rectangle">
                <option value="(City)" data-calc="">(City)</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-8">
            <label for="text-cc0a" class="u-label">Email</label>
            <input type="text" id="text-cc0a" name="text-3" class="u-input u-input-rectangle"
              placeholder="Enter Email Address">
          </div>
          <div class="u-form-date u-form-group u-form-partition-factor-2 u-label-top u-form-group-9">
            <label for="date-0971" class="u-label">Date</label>
            <input type="text" placeholder="MM/DD/YYYY" id="date-0971" name="date" class="u-input u-input-rectangle"
              required="" data-date-format="mm/dd/yyyy">
          </div>
          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-1"></div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-11">
            <label for="text-4ef3" class="u-label">Boundaries</label>
            <input type="text" placeholder="North" id="text-4ef3" name="text-4" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="text-93dc" class="u-form-control-hidden u-label"></label>
            <input type="text" placeholder="East" id="text-93dc" name="text-5" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-13">
            <label for="text-2f40" class="u-form-control-hidden u-label"></label>
            <input type="text" id="text-2f40" name="text-6" class="u-input u-input-rectangle" placeholder="South">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-14">
            <label for="text-7d2b" class="u-form-control-hidden u-label"></label>
            <input type="text" placeholder="West" id="text-7d2b" name="text-7" class="u-input u-input-rectangle">
          </div>
          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-2"></div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-16">
            <label for="text-5634" class="u-label">Kind of Property</label>
            <input type="text" placeholder="" id="text-5634" name="text-8" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-17">
            <label for="text-6f19" class="u-label">Area</label>
            <input type="text" placeholder="" id="text-6f19" name="text-9" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-18">
            <label for="text-3a03" class="u-label">Actual Use</label>
            <input type="text" placeholder="" id="text-3a03" name="text-10" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-19">
            <label for="text-2939" class="u-label">Market Value</label>
            <input type="text" placeholder="" id="text-2939" name="text-11" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-20">
            <label for="text-8a17" class="u-label">Assessment Level</label>
            <input type="text" placeholder="" id="text-8a17" name="text-12" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-top u-form-group-21">
            <label for="text-135e" class="u-label">Assessed Value</label>
            <input type="text" placeholder="" id="text-135e" name="text-13" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-22">
            <label for="text-5fb5" class="u-label">Value</label>
            <input type="text" placeholder="" id="text-5fb5" name="text-14" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-top u-form-group-23">
            <label for="text-95ec" class="u-label">PIN</label>
            <input type="text" placeholder="" id="text-95ec" name="text-15" class="u-input u-input-rectangle">
          </div>
          <div class="u-border-3 u-border-grey-dark-1 u-form-group u-form-line u-line u-line-horizontal u-line-3"></div>
          <div class="u-align-right u-form-group u-form-submit u-label-top">
            <a href="#" class="u-border-none u-btn u-btn-submit u-button-style u-none u-btn-1">Clear</a>
            <input type="submit" value="submit" class="u-form-control-hidden">
          </div>
          <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
          <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try
            again. </div>
          <input type="hidden" value="" name="recaptchaResponse">
          <input type="hidden" name="formServices" value="">
        </form>
      </div>
    </div>
  </section>
  <section
    class="u-align-center u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-clearfix u-container-align-center u-section-3"
    id="sec-8a3b">
    <div class="u-clearfix u-sheet u-sheet-1">
      <h4 class="u-text u-text-default u-text-1">Land Appraisal</h4>
      <div class="u-expanded-width u-table u-table-responsive u-table-1">
        <table class="u-table-entity u-table-entity-1">
          <colgroup>
            <col width="16.6%">
            <col width="16.6%">
            <col width="16.6%">
            <col width="16.6%">
            <col width="17%">
            <col width="16.6%">
          </colgroup>
          <thead class="u-black u-table-header u-table-header-1">
            <tr style="height: 67px;">
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Classification</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Sub-Class</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Area</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Actual Use</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Unit Value </th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Market Value </th>
            </tr>
          </thead>
          <tbody class="u-table-body">
            <tr style="height: 75px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 1</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 2</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 3</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 4</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
          </tbody>
          <tfoot class="u-table-footer">
            <tr style="height: 47px;">
              <td class="u-align-center u-border-1 u-border-grey-15 u-table-cell u-table-cell-43">TOTAL</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </section>
  <section class="u-align-center u-clearfix u-container-align-center u-section-4" id="carousel_fa37">
    <div class="u-clearfix u-sheet u-sheet-1">
      <h4 class="u-text u-text-default u-text-1">Plant and Trees Appraisal</h4>
      <div class="u-expanded-width u-table u-table-responsive u-table-1">
        <table class="u-table-entity u-table-entity-1">
          <colgroup>
            <col width="16.6%">
            <col width="16.6%">
            <col width="16.6%">
            <col width="16.6%">
            <col width="17%">
            <col width="16.6%">
          </colgroup>
          <thead class="u-black u-table-header u-table-header-1">
            <tr style="height: 67px;">
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Classification</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Sub-Class</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Area</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Actual Use</th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Unit Value </th>
              <th class="u-align-center u-border-1 u-border-black u-table-cell">Market Value </th>
            </tr>
          </thead>
          <tbody class="u-table-body">
            <tr style="height: 75px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 1</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 2</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 3</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell">Row 4</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell">Description</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
            <tr style="height: 76px;">
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
          </tbody>
          <tfoot class="u-table-footer">
            <tr style="height: 47px;">
              <td class="u-align-center u-border-1 u-border-grey-15 u-table-cell u-table-cell-43">TOTAL</td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
              <td class="u-border-1 u-border-grey-30 u-table-cell"></td>
            </tr>
          </tfoot>
        </table>
      </div>
      <a href="#" class="u-border-none u-btn u-btn-round u-button-style u-custom-color-1 u-radius u-btn-1">Print<span
          style="font-weight: 700;"></span>
      </a>
    </div>
  </section>



  <footer class="u-align-center u-clearfix u-container-align-center u-footer u-grey-80 u-footer" id="sec-7e36">
    <div class="u-clearfix u-sheet u-sheet-1">
      <p class="u-small-text u-text u-text-variant u-text-1">Sample text. Click to select the Text Element.</p>
    </div>
  </footer>

</body>

</html>