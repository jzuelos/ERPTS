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
  <link rel="stylesheet" href="Real-Property-Unit-List.css"> 
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

  <!-- Main Body -->
  <section class="container mt-5"> 
    <div class="card p-4"> 
      <h3 class="mb-4">Real Property Units List</h3> 
      <div class="form-row mb-4"> 
        <div class="col-auto">
          <label for="searchInput" class="sr-only">Search</label> 
          <div class="input-group"> 
            <input type="text" class="form-control" id="searchInput" placeholder="Search"> <!-- Search input -->
            <select class="custom-select" id="barangayDropdown"> <!-- Dropdown for barangay selection -->
              <option selected value="">All Barangay</option> <!-- Default option -->
              <option value="Barangay 1">Barangay 1</option>
              <option value="Barangay 2">Barangay 2</option>
              <option value="Barangay 3">Barangay 3</option>
            </select>
          </div>
        </div>

        <div class="col-auto">
    <button type="button" class="btn btn-success btn-hover" onclick="filterTable()">Search</button>
    <button type="button" class="btn btn-success btn-hover" onclick="addNewRPU()">Add new RPU</button>
  </div>

      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered text-center modern-table" id="propertyTable"> <!-- Responsive table -->
          <thead>
            <tr>
              <th>OD ID</th>
              <th>Owner</th>
              <th>Location</th>
              <th>Land Area</th>
              <th>Edit</th>
            </tr>
          </thead>
          <tbody>
            <!-- Table rows with property data -->
            <tr>
              <td>12345</td>
              <td>John Doe</td>
              <td>Street 1, Barangay 1</td>
              <td>500 sqm</td>
              <td><a href="FAAS.php" class="btn btn-primary">EDIT</a></td> <!-- Edit button -->
            </tr>
            <tr>
              <td>67890</td>
              <td>Jane Smith</td>
              <td>Street 2, Barangay 2</td>
              <td>300 sqm</td>
              <td><a href="FAAS.php" class="btn btn-primary">EDIT</a></td>
            </tr>
            <tr>
              <td>11223</td>
              <td>Mike Johnson</td>
              <td>Street 3, Barangay 3</td>
              <td>400 sqm</td>
              <td><a href="FAAS.php" class="btn btn-primary">EDIT</a></td>
            </tr>
            <!-- More rows can be added here -->
          </tbody>
        </table>
      </div>

      <!-- View All Button -->
      <div class="view-all-container d-flex mt-3"> 
        <div class="ml-auto">
          <button type="button" class="btn btn-info">View All</button>
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
