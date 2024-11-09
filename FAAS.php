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
  <link rel="stylesheet" href="FAAS.css">
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
        <button type="button" class="btn btn-danger">Log Out</button>
      </li>
    </ul>
  </div>
</nav>
<!--Main Body-->

<!-- Owner's Information Section -->
<section class="container mt-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
      <a href="Real-Property-Unit-List.php">
        <img src="images/backward.png" width="40" height="40" alt="Back">
      </a>
      <h4 class="ml-3">Owner's Information</h4>
    </div>
    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleEdit('owner-info')">Edit</button>
  </div>
  <div id="owner-info" class="row">
    <div class="col-md-6 mb-3">
      <form class="form-card">
        <div class="form-group">
          <label for="ownerName">Company or Owner</label>
          <input type="text" class="form-control" id="ownerName" placeholder="Enter company or owner name" disabled>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="removeOwner" disabled>Remove</button>
          <div>
            <a href="#" class="btn btn-link" id="addOwner" disabled>Add</a>
            <a href="#" class="btn btn-link" id="searchOwner" disabled>Search</a>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-6 mb-3">
      <form class="form-card">
        <div class="form-group">
          <label for="ownerInputName">Name</label>
          <input type="text" class="form-control" id="ownerInputName" placeholder="Enter name" disabled>
        </div>
        <div class="d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="removeName" disabled>Remove</button>
          <div>
            <a href="#" class="btn btn-link" id="addName" disabled>Add</a>
            <a href="#" class="btn btn-link" id="searchName" disabled>Search</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- Property Information Section -->
<section class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Property Information</h4>
    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleEdit('property-info')">Edit</button>
  </div>
  <form id="property-info" class="form-card">
    <div class="row">
      <div class="col-md-12 mb-3">
        <div class="form-group">
          <label for="location">Location</label>
        </div>
      </div>

      <!-- Street Field -->
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="street">Street</label>
          <input type="text" class="form-control" id="street" placeholder="Enter street" disabled>
        </div>
      </div>

      <!-- Barangay Field -->
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="barangay">Barangay</label>
          <input type="text" class="form-control" id="barangay" placeholder="Enter barangay" disabled>
        </div>
      </div>

      <!-- Municipality Field -->
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="municipality">Municipality</label>
          <input type="text" class="form-control" id="municipality" placeholder="Enter municipality" disabled>
        </div>
      </div>

      <!-- Province Field -->
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="province">Province</label>
          <input type="text" class="form-control" id="province" placeholder="Enter province" disabled>
        </div>
      </div>

      <!-- Other Fields -->
      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="houseNumber">House Number</label>
          <input type="text" class="form-control" id="houseNumber" placeholder="Enter house number" disabled>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="landArea">Land Area</label>
          <input type="text" class="form-control" id="landArea" placeholder="Enter land area" disabled>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="zoneNumber">Zone Number</label>
          <input type="text" class="form-control" id="zoneNumber" placeholder="Enter zone number" disabled>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="ardNumber">ARD Number</label>
          <input type="text" class="form-control" id="ardNumber" placeholder="Enter ARD number" disabled>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="taxability">Taxability</label>
          <select class="form-control" id="taxability" disabled>
            <option value="Taxable">Taxable</option>
            <option value="Non-Taxable">Non-Taxable</option>
          </select>
        </div>
      </div>

      <div class="col-md-6 mb-3">
        <div class="form-group">
          <label for="effectivity">Effectivity</label>
          <input type="text" class="form-control" id="effectivity" placeholder="Enter effectivity date" disabled>
        </div>
      </div>
    </div>
  </form>
</section>

<!-- Declaration of Property Section -->
<section class="container my-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Declaration of Property</h4>
    <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleEdit('declaration-info')">Edit</button>
  </div>
  <form id="declaration-info" class="form-card">
    <div class="form-group">
      <label for="taxDeclarationNumber">Tax Declaration Number</label>
      <input type="text" class="form-control" id="taxDeclarationNumber" placeholder="Enter tax declaration number" disabled>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="provincialAssessor">Provincial Assessor</label>
        <input type="text" class="form-control" id="provincialAssessor" placeholder="Enter name of provincial assessor" disabled>
      </div>
      <div class="form-group col-md-6">
        <label for="assessorDate">Date</label>
        <input type="text" class="form-control" id="assessorDate" placeholder="Enter date" disabled>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="previousPin">Previous PIN</label>
        <input type="text" class="form-control" id="previousPin" placeholder="Enter previous PIN" disabled>
      </div>
      <div class="form-group col-md-6">
        <label for="previousOwner">Previous Owner</label>
        <input type="text" class="form-control" id="previousOwner" placeholder="Enter previous owner's name" disabled>
      </div>
    </div>

    <!-- Print Button -->
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
  </form>
</section>

<!-- Memoranda Field Section (Separated) -->
<section class="container my-5">
  <div class="form-group">
    <label for="memoranda">Memoranda</label>
    <textarea class="form-control" id="memoranda" rows="3" disabled style="font-weight: bold;">TRANSFERRED BY VIRTUE OF ORIGINAL CERTIFICATE OF TITLE NO.2021000115
CERTIFICATION OF LAND TAX PAYMENT OF BOTH SUBMITTED CERTIFICATION OF TRANSFER TAX PRESENTED.</textarea>
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
  <script src="http://localhost/ERPTS/main_layout.js"></script>
  <script src="http://localhost/ERPTS/FAAS.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>

</html>