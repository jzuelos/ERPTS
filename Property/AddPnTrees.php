<?php
session_start();
$user_role = $_SESSION['user_type'] ?? 'user';
$basePath ='../';
include '../header.php';
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../main_layout.css">
  <link rel="stylesheet" href="../FAAS.css">
  <link rel="stylesheet" href="../header.css">
  <link rel="stylesheet" href="pnt.css">
    <title>Electronic Real Property Tax System</title>
</head>

<body>

    <!-- Header Navigation -->
  <?php include '../header.php'; ?>
 <!--Plant and Trees--> 
<section class="container wide-form my-5" id="plants-section">
  <div class="row justify-content-center">
    <div class="col-lg-11 col-xl-10">
     <div class="d-flex justify-content-between align-items-center mb-4">
        <!-- Left: back button + title -->
        <div class="d-flex align-items-center">
          <a href="../FAAS.php" class="text-decoration-none me-2">
            <i class="fas fa-arrow-left"></i>
          </a>
          <h4 class="section-title mb-0">Plants / Trees</h4>
        </div>

        <!-- Right: Add button -->
        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
          data-bs-target="#editPnT">Edit</button>
      </div>

  <div class="row justify-content-center">
    <div class="col-lg-12 col-xl-13"> 
      <div class="card border-0 shadow p-4 rounded-3 compact-form">
        <form>
          <!-- Identification -->
          <h5 class="section-title">Identification</h5>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label for="surveyNumber" class="form-label">Survey Number</label>
              <input type="text" id="surveyNumber" class="form-control" maxlength="20">
            </div>
            <div class="col-md-6 mb-4">
              <label for="landPin" class="form-label">Land PIN</label>
              <input type="text" id="landPin" class="form-control" maxlength="20">
            </div>
          </div>

          <!-- Administrator Information + Plants and Trees Appraisal -->
          <div class="row mt-4">
            <!-- Administrator Information -->
            <div class="col-md-6">
              <h5 class="section-title">Administrator Information</h5>
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Last Name</label>
                  <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;"
                    pattern="[A-Za-z ]+" title="Only letters and spaces are allowed"
                    oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">First Name</label>
                  <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;"
                    pattern="[A-Za-z ]+" title="Only letters and spaces are allowed"
                    oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Middle Name</label>
                  <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;"
                    pattern="[A-Za-z ]+" title="Only letters and spaces are allowed"
                    oninput="this.value=this.value.replace(/[^A-Za-z ]/g,'')" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Street/Number</label>
                  <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;"
                    pattern="[A-Za-z0-9 ]+" title="Only letters, numbers, and spaces are allowed"
                    oninput="this.value=this.value.replace(/[^A-Za-z0-9 ]/g,'')" required>
                </div>
                <div class="col-md-6 mb-4">
                   <label class="form-label">Barangay</label>
                  <select class="form-select">
                    <option value="" disabled selected>Select Barangay</option>
                    <option>Barangay 1</option>
                    <option>Barangay 2</option>
                    <option>Barangay 3</option>
                  </select>
                </div>
                <div class="col-md-6 mb-4">
                   <label class="form-label">District</label>
                  <select class="form-select">
                    <option value="" disabled selected>Select District</option>
                    <option>District 1</option>
                    <option>District 2</option>
                    <option>District 3</option>
                  </select>
                </div>
                <div class="col-md-6 mb-4">
                   <label class="form-label">Municipality/City</label>
                  <select class="form-select">
                    <option value="" disabled selected>Select Municipality/City</option>
                    <option>Municipality 1</option>
                    <option>Municipality 2</option>
                    <option>Municipality 3</option>
                  </select>
                </div>
                <div class="col-md-6 mb-4">
                   <label class="form-label">Province</label>
                  <select class="form-select">
                    <option value="" disabled selected>Select Province</option>
                    <option>Camarines Norte</option>
                  </select>
                </div>
                <div class="col-md-12 mb-4">
                  <label class="form-label">Contact Number</label>
                  <input type="number" class="form-control" id="contactNumber" oninput="this.value=this.value.slice(0,11)" required>
                </div>
              </div>
            </div>

            <!-- Plants and Trees Appraisal -->
            <div class="col-md-6">
              <h5 class="section-title">Plants and Trees Appraisal</h5>
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Product Class</label>
                  <select class="form-select" required>
                    <option value="" disabled selected>Select Product Class</option>
                    <option>Bamboo 1st Class</option>
                    <option>Bamboo 2nd Class</option>
                    <option>Coconut</option>
                    <option>Mango</option>
                  </select>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Area Planted (sqm)</label>
                  <input type="number" class="form-control" min="1" max="99999" oninput="this.value=this.value.slice(0,6)" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Number of Trees</label>
                  <input type="number" class="form-control" min="1" max="99999" oninput="this.value=this.value.slice(0,6)" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Fruit Bearing?</label><br>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="fruitBearing" id="fruitYes">
                    <label class="form-check-label" for="fruitYes">Yes</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="fruitBearing" id="fruitNo">
                    <label class="form-check-label" for="fruitNo">No</label>
                  </div>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Unit Price</label>
                  <input type="number" class="form-control" min="1" max="99999" oninput="this.value=this.value.slice(0,6)" required>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Age</label>
                  <input type="number" class="form-control" min="1" max="200" oninput="this.value=this.value.slice(0,3)" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Value Computation + Property Assessment -->
          <div class="row mt-4">
            <div class="col-md-6">
              <h5 class="section-title">Value Computation</h5>
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Adjustment Factor</label>
                  <input type="text" class="form-control" maxlength="10">
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Percent Adjustment</label>
                  <input type="number" class="form-control" oninput="this.value=this.value.slice(0,3)">
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Value Adjustment</label>
                  <input type="number" class="form-control" oninput="this.value=this.value.slice(0,10)">
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Adjusted Market Value</label>
                  <input type="number" class="form-control" oninput="this.value=this.value.slice(0,12)">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <h5 class="section-title">Property Assessment</h5>
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label">Actual Use</label>
                  <select class="form-select">
                    <option value="" disabled selected>Select Actual Use</option>
                    <option>Residential Trees</option>
                    <option>Commercial Trees</option>
                    <option>Agricultural Trees</option>
                  </select>
                </div>
                <div class="col-md-6 mb-4">
                  <label class="form-label">Assessment Level (%)</label>
                  <input type="number" class="form-control" min="1" max="100" oninput="if(this.value.length>3)this.value=this.value.slice(0,3)" required>
                </div>
              </div>
            </div>
          </div>

          <!-- Certification -->
          <h5 class="section-title mt-3">Certification</h5>
          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label">Verified By</label>
              <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\s]+" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Plottings By</label>
              <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\s]+" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Noted By</label>
              <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\s]+" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Date</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Approved By</label>
              <input type="text" class="form-control" maxlength="50" style="text-transform: uppercase;" pattern="[A-Za-z\s]+" required>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label">Date</label>
              <input type="date" class="form-control">
            </div>
          </div>

          <!-- Memoranda -->
          <h5 class="section-title mt-2 text-center">Memoranda</h5>
          <div class="row justify-content-center">
            <div class="col-md-8">
              <textarea class="form-control mb-4" rows="4" placeholder="Type the memoranda here... (Optional)"></textarea>
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success px-4">Submit</button>
          </div>
        </form>
      </div>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="pnt.js"></script>
  <script src="https://kit.fontawesome.com/yourkit.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>