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
  <link rel="stylesheet" href="Reports.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-custom">
    <a class="navbar-brand" href="#">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" alt="">
      Electronic Real Property Tax System
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"><a class="nav-link" href="Home.php">Home</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">RPU Management</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
            <a class="dropdown-item" href="FAAS.php">FAAS</a>
            <a class="dropdown-item" href="Tax-Declaration-List.php">Tax Declaration</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="Track.php">Track Paper</a>
          </div>
        </li>
        <li class="nav-item"><a class="nav-link" href="Transaction.php">Transaction</a></li>
        <li class="nav-item"><a class="nav-link active" href="Reports.php">Reports</a></li>
        <li class="nav-item ml-3"><button class="btn btn-danger">Log Out</button></li>
      </ul>
    </div>
  </nav>

  <!-- Main Body -->
   <main>
  <section class="container mt-4">
    <div class="card p-4">
      <form>
        <!-- Filter by Classification -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="classificationCheck">
          <label class="form-check-label font-weight-bold" for="classificationCheck">Filter by: Classification</label>
        </div>
        <div class="form-group">
          <label for="classificationSelect">Classification</label>
          <select class="form-control w-25" id="classificationSelect">
            <option>Agricultural</option>
            <option>Item 2</option>
            <option>Item 3</option>
          </select>
        </div>
        <hr>

        <!-- Filter by Location -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="locationCheck">
          <label class="form-check-label font-weight-bold" for="locationCheck">Filter by: Location</label>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="provinceSelect">Province</label>
            <select class="form-control" id="provinceSelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="citySelect">Municipality/City</label>
            <select class="form-control" id="citySelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="districtSelect">District</label>
            <select class="form-control" id="districtSelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="barangaySelect">Barangay</label>
            <select class="form-control" id="barangaySelect">
              <option>Item 1</option>
              <option>Item 2</option>
              <option>Item 3</option>
            </select>
          </div>
        </div>
        <hr>

        <!-- Filter by Date -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="dateCheck">
          <label class="form-check-label font-weight-bold" for="dateCheck">Filter by: Date Created</label>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="fromDate">From:</label>
            <input type="date" class="form-control" id="fromDate">
          </div>
          <div class="form-group col-md-6">
            <label for="toDate">To:</label>
            <input type="date" class="form-control" id="toDate">
          </div>
        </div>

        <!-- Print All Checkbox -->
        <div class="form-group form-check">
          <input type="checkbox" class="form-check-input" id="printAllCheck">
          <label class="form-check-label font-weight-bold" for="printAllCheck">Print ALL (No Filtering)</label>
        </div>

        <!-- Submit Button -->
        <div class="text-right">
          <a href="#" class="btn btn-primary" target="_blank">PRINT</a>
        </div>
      </form>
    </div>
  </section>
</main>
 <!-- Footer -->
 <footer class="bg-body-tertiary text-center text-lg-start">
    <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
      © 2020 Copyright:
      <a class="text-body" href="https://mdbootstrap.com/">MDBootstrap.com</a>
    </div>
  </footer>

  <!-- Optional JavaScript -->
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
