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
<main class="container my-5">
  <!-- Back Button (Positioned to the top left) -->
  <div class="mb-4 d-flex justify-content-start">
    <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left"></i> Back
    </a>
  </div>

  <!-- Location Title -->
  <div class="text-center mb-5">
    <h2 class="text-secondary font-weight-bold" style="font-size: 2.5rem;">Location</h2>
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
              <option value="">Select...</option>
              <option value="District">District</option>
              <option value="Municipality">Municipality</option>
              <option value="City">City</option>
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
        <button type="submit" class="btn btn-primary" id="submitFormBtn">Submit</button>
      </div>
    </div>
  </div>
</div>

<!-- Temporary Municipality Modal -->
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
        <p>This is a temporary modal for the Municipality.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save</button>
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
                    <form>
                        <div class="form-group">
                            <label for="municipality">Municipality / City</label>
                            <select class="form-control" id="municipality">
                                <option></option>
                                <!-- Add more Municipality/City -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="code">Code</label>
                            <input type="text" class="form-control" id="code" value="">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input type="text" class="form-control" id="description" value=" ">
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
        <button type="submit" class="btn btn-primary" id="submitFormBtn">Submit</button>
                </div>
            </div>
        </div>
    </div>


 <!-- Footer -->
 <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/main-layout.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="http://localhost/ERPTS/Location.js"></script>


</body>
</html>
