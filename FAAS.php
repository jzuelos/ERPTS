<?php
  session_start();

  // Check if the user is logged in by verifying if 'user_id' exists in the session
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if user is not logged in
    exit; // Stop further execution after redirection
  }

  // Prevent the browser from caching this page
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Instruct the browser not to store or cache the page
  header("Cache-Control: post-check=0, pre-check=0", false); // Additional caching rules to prevent the page from being reloaded from cache
  header("Pragma: no-cache"); // Older cache control header for HTTP/1.0 compatibility
?>
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
          <a class="nav-link" href="Home.php">Home<span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item dropdown active">
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item active" href="FAAS.php">FAAS</a>
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
        <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
  <section class="u-align-center u-clearfix u-container-align-center u-section-2" id="sec-71a0">
    <div class="u-clearfix u-sheet u-sheet-1">
      <a href="Real-Property-Unit-List.html"><img class="u-image u-image-default u-preserve-proportions u-image-1"
          src="images/backward.png" alt="" data-image-width="512" data-image-height="512"></a>
      <h4 class="u-text u-text-default u-text-1">Owner's Information</h4>
      <img class="u-image u-image-default u-preserve-proportions u-image-2" src="images/download1.png" alt=""
        data-image-width="512" data-image-height="512">
      <div class="u-container-style u-group u-shape-rectangle u-group-1">
        <div class="u-container-layout">
          <div class="u-form u-form-1">
            <form action="https://forms.nicepagesrv.com/v2/form/process"
              class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" name="form-2"
              style="padding: 10px;">
              <div class="u-form-group u-form-name u-label-none">
                <label for="name-2035" class="u-label">Name</label>
                <input type="text" placeholder="Company or Owner" id="name-2035" name="name"
                  class="u-input u-input-rectangle" required="">
              </div>
              <div class="u-form-group u-label-none u-form-group-2">
                <label for="text-a534" class="u-label">Input</label>
                <input type="text" placeholder="Name" id="text-a534" name="text" class="u-input u-input-rectangle">
              </div>
              <div class="u-align-left u-form-group u-form-submit u-label-none">
                <a href="#" class="u-btn u-btn-submit u-button-style u-custom-color-1 u-btn-1">Remove</a>
                <input type="submit" value="submit" class="u-form-control-hidden">
              </div>
              <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
              <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then
                try again. </div>
              <input type="hidden" value="" name="recaptchaResponse">
              <input type="hidden" name="formServices" value="">
            </form>
          </div>
          <a href="#" class="u-btn u-button-style u-custom-color-1 u-btn-2">Add </a>
          <a href="#" class="u-btn u-button-style u-custom-color-1 u-btn-3">Search </a>
        </div>
      </div>
      <div class="u-container-style u-group u-shape-rectangle u-group-2">
        <div class="u-container-layout">
          <div class="u-form u-form-2">
            <form action="https://forms.nicepagesrv.com/v2/form/process"
              class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" name="form-2"
              style="padding: 10px;">
              <div class="u-form-group u-form-name u-label-none">
                <label for="name-2035" class="u-label">Name</label>
                <input type="text" placeholder="Name" id="name-2035" name="name" class="u-input u-input-rectangle"
                  required="">
              </div>
              <div class="u-align-left u-form-group u-form-submit u-label-none">
                <a href="#" class="u-btn u-btn-submit u-button-style u-custom-color-1 u-btn-4">Remove</a>
                <input type="submit" value="submit" class="u-form-control-hidden">
              </div>
              <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
              <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then
                try again. </div>
              <input type="hidden" value="" name="recaptchaResponse">
              <input type="hidden" name="formServices" value="">
            </form>
          </div>
          <a href="#" class="u-btn u-button-style u-custom-color-1 u-btn-5">Add </a>
          <a href="#" class="u-btn u-button-style u-custom-color-1 u-btn-6">Search </a>
        </div>
      </div>
    </div>
  </section>
  <section
    class="u-align-center u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-clearfix u-container-align-center u-section-3"
    id="sec-0cfe">
    <div class="u-clearfix u-sheet u-sheet-1">
      <h4 class="u-text u-text-default u-text-1">Property Information</h4>

      <div class="u-border-1 u-border-black u-form u-form-1">
        <form action="https://forms.nicepagesrv.com/v2/form/process"
          class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" style="padding: 0px;" source="email"
          name="form">
          <div class="u-form-group u-form-message u-form-partition-factor-4 u-label-none">
            <label for="message-3b9a" class="u-label">Message</label>
            <textarea placeholder="Location​" rows="4" cols="50" id="message-3b9a" name="message"
              class="u-input u-input-rectangle" required=""></textarea>
          </div>
          <div class="u-form-group u-form-message u-form-partition-factor-4 u-label-none u-form-group-2">
            <label for="message-a609" class="u-label">Message</label>
            <textarea placeholder="(Street) 
(Barangay)
(Municipality)
(Province)" rows="4" cols="50" id="message-a609" name="message-1" class="u-input u-input-rectangle"
              required=""></textarea>
          </div>
          <div class="u-form-email u-form-group u-form-partition-factor-4 u-label-none">
            <label for="email-3b9a" class="u-label">Email</label>
            <input type="email" placeholder="Number" id="email-3b9a" name="email" class="u-input u-input-rectangle"
              required="">
          </div>
          <div class="u-form-group u-form-name u-form-partition-factor-4 u-label-none">
            <label for="name-3b9a" class="u-label">Name</label>
            <input type="text" placeholder="Distinct" id="name-3b9a" name="name" class="u-input u-input-rectangle"
              required="">
          </div>
          <div class="u-form-group u-label-none u-form-group-5">
            <label for="text-45b3" class="u-label">Input</label>
            <input type="text" placeholder="House Tag Number:" id="text-45b3" name="text"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-6">
            <label for="text-1e77" class="u-label">Input</label>
            <input type="text" placeholder="Zone Number" id="text-1e77" name="text-2" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-7">
            <label for="text-22a6" class="u-label">Input</label>
            <input type="text" placeholder="Land Area" id="text-22a6" name="text-1" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-none u-form-group-8">
            <label for="text-acab" class="u-label">Input</label>
            <input type="text" placeholder="ARD Number" id="text-acab" name="text-3" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-none u-form-group-9">
            <label for="text-68f0" class="u-label">Input</label>
            <input type="text" placeholder="Taxability" id="text-68f0" name="text-4" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-3 u-label-none u-form-group-10">
            <label for="text-6d60" class="u-label">Input</label>
            <input type="text" placeholder="Effectivity" id="text-6d60" name="text-5" class="u-input u-input-rectangle">
          </div>
          <div class="u-align-right u-form-group u-form-submit">
            <a href="#" class="u-btn u-btn-submit u-button-style u-custom-color-1 u-btn-1">Edit Property Information
            </a>
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
  <section class="u-align-center u-clearfix u-section-4" id="carousel_3df6">
    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
      <h4 class="u-text u-text-default u-text-1">Declaration of Property</h4>
      <p class="u-text u-text-2">Identification Numberss </p>
      <div class="u-form u-form-1">
        <form action="https://forms.nicepagesrv.com/v2/form/process"
          class="u-clearfix u-form-horizontal u-form-spacing-10 u-inner-form" source="email" name="form-3"
          style="padding: 10px;">
          <div class="u-form-email u-form-group u-label-none">
            <label for="email-4a6d" class="u-label">Email</label>
            <input type="email" placeholder="Tax Declaration Number" id="email-4a6d" name="email"
              class="u-input u-input-rectangle" required="">
          </div>
          <div class="u-align-left u-form-group u-form-submit u-label-none">
            <a href="#" class="u-btn u-btn-submit u-button-style u-custom-color-1 u-btn-1">Edit ID</a>
            <input type="submit" value="submit" class="u-form-control-hidden">
          </div>
          <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
          <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try
            again. </div>
          <input type="hidden" value="" name="recaptchaResponse">
          <input type="hidden" name="formServices" value="">
        </form>
      </div>
      <p class="u-text u-text-3">Approval</p>
      <img class="u-image u-image-contain u-image-default u-preserve-proportions u-image-1"
        src="images/cloud-computing.png" alt="" data-image-width="512" data-image-height="512">
      <div class="u-border-2 u-border-black u-form u-form-2">
        <form action="https://forms.nicepagesrv.com/v2/form/process"
          class="u-clearfix u-form-spacing-10 u-form-vertical u-inner-form" source="email" name="form-1"
          style="padding: 10px;">
          <div class="u-form-group u-form-name u-form-partition-factor-2 u-label-none">
            <label for="name-f654" class="u-label">Name</label>
            <input type="text" placeholder="Provincial Assessor" id="name-f654" name="name"
              class="u-input u-input-rectangle" required="">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-4">
            <label for="text-c9c5" class="u-label">Input</label>
            <input type="text" placeholder="Date (mm/dd/yyyy)" id="text-c9c5" name="text"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-email u-form-group u-form-partition-factor-2 u-label-none">
            <label for="email-f654" class="u-label">Email</label>
            <input type="email" placeholder="City/Municipal Assessor" id="email-f654" name="email"
              class="u-input u-input-rectangle" required="">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-6">
            <label for="text-125c" class="u-label">Input</label>
            <input type="text" placeholder="Date (mm/dd/yyyy)" id="text-125c" name="text-1"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-7">
            <label for="text-8dac" class="u-label">Input</label>
            <input type="text" placeholder="Cancels TD Number" id="text-8dac" name="text-2"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-8">
            <label for="text-b2d9" class="u-label">Input</label>
            <input type="text" placeholder="Previous PIN" id="text-b2d9" name="text-3"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-9">
            <label for="text-4567" class="u-label">Input</label>
            <input type="text" placeholder="Tax Begins with the Year" id="text-4567" name="text-4"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-10">
            <label for="text-2d9a" class="u-label">Input</label>
            <input type="text" placeholder="" id="text-2d9a" name="text-5" class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-11">
            <label for="text-c88a" class="u-label">Input</label>
            <input type="text" placeholder="entered1nRPAREForYear" id="text-c88a" name="text-6"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-12">
            <label for="text-2a1e" class="u-label">Input</label>
            <input type="text" placeholder="Entered1nRPAREForby" id="text-2a1e" name="text-7"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-13">
            <label for="text-77f7" class="u-label">Input</label>
            <input type="text" placeholder="Previous Owner" id="text-77f7" name="text-8"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-form-group u-form-partition-factor-2 u-label-none u-form-group-14">
            <label for="text-aad6" class="u-label">Input</label>
            <input type="text" placeholder="Previous Assessed Value" id="text-aad6" name="text-9"
              class="u-input u-input-rectangle">
          </div>
          <div class="u-align-right u-form-group u-form-submit u-label-none">
            <a href="#" class="u-black u-border-none u-btn u-btn-submit u-button-style u-btn-2">Print</a>
            <input type="submit" value="submit" class="u-form-control-hidden">
          </div>
          <div class="u-form-send-message u-form-send-success"> Thank you! Your message has been sent. </div>
          <div class="u-form-send-error u-form-send-message"> Unable to send your message. Please fix errors then try
            again. </div>
          <input type="hidden" value="" name="recaptchaResponse">
          <input type="hidden" name="formServices" value="">
        </form>
      </div>
      <p class="u-text u-text-default u-text-4">Memoranda</p>
      <div class="u-align-justify u-border-2 u-border-grey-75 u-container-style u-group u-white u-group-1">
        <div class="u-container-layout u-valign-middle u-container-layout-1">
          <p class="u-align-center u-text u-text-default u-text-5">TRANSFERRED BY VIRTUE OF ORIGINAL CERTIFICATE OF
            TITLE NO.2021000115 CERTIFICATION OF LAND TAX PAYMENT OF BOTH SUBMITTED CERTIFICATION OF TRANSFER TAX
            PRESENTED.</p>
        </div>
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