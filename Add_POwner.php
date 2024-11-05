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
  <link rel="stylesheet" href="Add_POwner.css"> 
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
        <li class="nav-item dropdown active"> 
          <a class="nav-link dropdown-toggle" href="RPU-Management.php" id="navbarDropdown" role="button"
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown"> 
            <a class="dropdown-item active" href="Real-Property-Unit-List.php">RPU List</a>
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
        <li class="nav-item ml-3">
          <button type="button" class="btn btn-danger">Log Out</button> 
        </li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
<section class="container mt-5">
  <div class="card p-4 shadow-sm form-container">
    <h3 class="mb-4 text-center">Add Property Owner</h3>
    <form>
      <!-- Owner's Information -->
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" class="form-control input-field" id="firstName" placeholder="Enter First Name">
      </div>
      <div class="form-group">
        <label for="middleName">Middle Name</label>
        <input type="text" class="form-control input-field" id="middleName" placeholder="Enter Middle Name">
      </div>
      <div class="form-group">
        <label for="surname">Surname</label>
        <input type="text" class="form-control input-field" id="surname" placeholder="Enter Surname">
      </div>
      <div class="form-group">
        <label for="tinNumber">TIN No.</label>
        <input type="text" class="form-control input-field" id="tinNumber" placeholder="Enter TIN Number">
      </div>

      <!-- Address -->
      <h5 class="mt-4">Address</h5>
      <div class="form-group">
        <label for="houseNumber">House Number</label>
        <input type="text" class="form-control input-field" id="houseNumber" placeholder="Enter House Number">
      </div>
      <div class="form-group">
        <label for="street">Street</label>
        <input type="text" class="form-control input-field" id="street" placeholder="Enter Street">
      </div>
      <div class="form-group">
        <label for="barangay">Barangay</label>
        <input type="text" class="form-control input-field" id="barangay" placeholder="Enter Barangay">
      </div>
      <div class="form-group">
        <label for="district">District</label>
        <input type="text" class="form-control input-field" id="district" placeholder="Enter District">
      </div>
      <div class="form-group">
        <label for="city">City</label>
        <input type="text" class="form-control input-field" id="city" placeholder="Enter City">
      </div>
      <div class="form-group">
        <label for="province">Province</label>
        <input type="text" class="form-control input-field" id="province" placeholder="Enter Province">
      </div>

      <!-- Owner Contact Information -->
      <h5 class="mt-4">Owner Information</h5>
      <div class="form-group">
        <label for="telephone">Telephone</label>
        <input type="text" class="form-control input-field" id="telephone" placeholder="Enter Telephone Number">
      </div>
      <div class="form-group">
        <label for="fax">Fax</label>
        <input type="text" class="form-control input-field" id="fax" placeholder="Enter Fax Number">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control input-field" id="email" placeholder="Enter Email Address">
      </div>
      <div class="form-group">
        <label for="website">Website</label>
        <input type="url" class="form-control input-field" id="website" placeholder="Enter Website URL">
      </div>

      <button type="submit" class="btn btn-primary submit-btn">Submit</button>
    </form>
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
  <script src="http://localhost/ERPTS/Real-Property-Unit-List.js"></script> 
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
