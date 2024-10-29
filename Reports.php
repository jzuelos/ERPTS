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
          <a class="nav-link" href="Home.php">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown">
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
        <li class="nav-item active">
          <a class="nav-link" href="Reports.php">Reports</a>
        </li>
        <li class="nav-item" style="margin-left: 20px">
          <button type="button" class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off">
            Log Out</button>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
  <section class="u-align-center u-clearfix u-section-2" id="sec-ffed">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <div class="u-form u-form-1">
        <form action="https://forms.nicepagesrv.com/v2/form/process"
          class="u-clearfix u-form-spacing-33 u-form-vertical u-inner-form" source="email" name="form"
          style="padding: 17px;">
          <div class="u-form-checkbox u-form-group u-label-top u-form-group-1">
            <input type="checkbox" id="checkbox-0878" name="checkbox" value="On" class="u-field-input">
            <label for="checkbox-0878" class="u-field-label" style="font-weight: 700;">Filter by: Classification
            </label>
          </div>
          <div class="u-form-group u-form-select u-label-top u-form-group-2">
            <label for="select-2eeb" class="u-label">Classification</label>
            <div class="u-form-select-wrapper">
              <select id="select-2eeb" name="select"
                class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white">
                <option value="Agricultural" data-calc="" selected="selected">Agricultural</option>
                <option value="Item 2">Item 2</option>
                <option value="Item 3">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div
            class="u-border-1 u-border-grey-dark-1 u-border-no-bottom u-border-no-left u-border-no-right u-form-group u-form-line u-label-top u-line u-line-horizontal u-opacity u-opacity-95 u-line-1">
          </div>
          <div class="u-form-checkbox u-form-group u-label-top u-form-group-4">
            <input type="checkbox" id="checkbox-b554" name="checkbox-1" value="On" class="u-field-input">
            <label for="checkbox-b554" class="u-field-label" style="font-weight: 700;">Filter by: Location</label>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-5">
            <label for="select-375f" class="u-label">Province</label>
            <div class="u-form-select-wrapper">
              <select id="select-375f" name="select-2"
                class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white">
                <option value="Item 1">Item 1</option>
                <option value="Item 2">Item 2</option>
                <option value="Item 3">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-6">
            <label for="select-b9b6" class="u-label">Municipality/City</label>
            <div class="u-form-select-wrapper">
              <select id="select-b9b6" name="select-1"
                class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white">
                <option value="Item 1">Item 1</option>
                <option value="Item 2">Item 2</option>
                <option value="Item 3">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-7">
            <label for="select-b135" class="u-label">District</label>
            <div class="u-form-select-wrapper">
              <select id="select-b135" name="select-3"
                class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white">
                <option value="Item 1">Item 1</option>
                <option value="Item 2">Item 2</option>
                <option value="Item 3">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-form-select u-label-top u-form-group-8">
            <label for="select-16ea" class="u-label">Barangay</label>
            <div class="u-form-select-wrapper">
              <select id="select-16ea" name="select-4"
                class="u-border-1 u-border-grey-30 u-input u-input-rectangle u-white">
                <option value="Item 1">Item 1</option>
                <option value="Item 2">Item 2</option>
                <option value="Item 3">Item 3</option>
              </select>
              <svg class="u-caret u-caret-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px"
                viewBox="0 0 16 16" style="fill:currentColor;" xml:space="preserve">
                <polygon class="st0" points="8,12 2,4 14,4 "></polygon>
              </svg>
            </div>
          </div>
          <div
            class="u-border-1 u-border-grey-dark-1 u-border-no-bottom u-border-no-left u-border-no-right u-form-group u-form-line u-label-top u-line u-line-horizontal u-opacity u-opacity-95 u-line-2">
          </div>
          <div class="u-form-checkbox u-form-group u-label-top u-form-group-10">
            <input type="checkbox" id="checkbox-e787" name="checkbox-2" value="On" class="u-field-input">
            <label for="checkbox-e787" class="u-field-label" style="font-weight: 700;">Filter by: Date Created:</label>
          </div>
          <div class="u-form-date u-form-group u-form-partition-factor-2 u-label-top u-form-group-11">
            <label for="date-cd84" class="u-label">From:</label>
            <input type="text" placeholder="MM/DD/YYYY" id="date-cd84" name="date"
              class="readonly u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required=""
              data-date-format="mm/dd/yyyy">
          </div>
          <div class="u-form-date u-form-group u-form-partition-factor-2 u-label-top u-form-group-12">
            <label for="date-cb69" class="u-label">To:</label>
            <input type="text" placeholder="MM/DD/YYYY" id="date-cb69" name="date-1"
              class="readonly u-border-1 u-border-grey-30 u-input u-input-rectangle u-white" required=""
              data-date-format="mm/dd/yyyy">
          </div>
          <div class="u-form-checkbox u-form-group u-label-top u-form-group-13">
            <input type="checkbox" id="checkbox-ca0e" name="checkbox-3" value="On" class="u-field-input">
            <label for="checkbox-ca0e" class="u-field-label" style="font-weight: 700;">Print ALL (No Filtering)</label>
          </div>
          <div class="u-align-right u-form-group u-form-submit u-label-top">
            <a href="LPC.html" class="u-btn u-btn-round u-btn-submit u-button-style u-custom-color-1 u-radius u-btn-1"
              target="blank">PRINT </a>
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

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>


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