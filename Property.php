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
<!--Main Body-->
<main class="container my-5">
    <h2 class="text-center mb-4">Property Details</h2>
    <form>
        <div class="form-group">
            <label for="classification">Select Option</label>
            <select class="form-control" id="classification">
                <option>Classification</option>
                <option>Actual Uses</option>
                <option>Subclasses</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" id="applyButton">Apply</button>
    </form>

    <div id="detailsForm" class="mt-5" style="display: none;">
        <h3>ADDRESS: Based Master Table - Land Actual Uses</h3>
        <form>
            <div class="form-group">
                <label for="reportCode">Report Code</label>
                <select class="form-control" id="reportCode">
                    <option>Scientific (SC)</option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" class="form-control" id="code" value="">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" value="">
            </div>
            <div class="form-group">
                <label for="assessmentLevel">Assessment Level</label>
                <input type="text" class="form-control" id="assessmentLevel" value="">
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
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="button" class="btn btn-danger">Cancel</button>
        </form>
    </div>
</main>
<!-- Footer -->
<footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
    <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
    </div>
  </footer>

  <!-- Optional JavaScript -->
  <script src="http://localhost/ERPTS/main-layout.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
    crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script src="Property.js"></script>


</body>
</html>
