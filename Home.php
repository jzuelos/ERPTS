<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css"
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Home.css">
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
        <li class="nav-item active">
          <a class="nav-link" href="Home.php">Home<span class="sr-only">(current)</span></a>
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
        <li class="nav-item">
          <a class="nav-link" href="Reports.php">Reports</a>
        </li>
        <li class="nav-item" style = "margin-left: 20px">
          <button type="button" class="btn btn-danger" data-toggle="button" aria-pressed="false" autocomplete="off">
            Log Out</button>
        </li>
      </ul>
    </div>
  </nav>

  <!--Main Body-->
  <div class="container py-5 my-3">
    <div class="row justify-content-center">
        <!-- Large Horizontal Grid on the Left -->
        <div class="col-lg-8 mb-3">
            <div class="modern-card">
                <h3>Assessor's Office</h3>
                <p>The Assessor's Office is a vital part of local government, tasked with determining the value of properties for taxation purposes. 
                  By conducting detailed property appraisals and analyzing market trends, the office ensures that property tax assessments are fair and equitable.
                   This process is crucial for funding essential community services like schools, infrastructure, and public safety. The office also provides 
                   transparency and assistance to property owners, offering guidance on assessment processes and handling appeals. Through its commitment 
                   to accuracy and fairness, the Assessor's Office supports the financial health and growth of the community.</p>
                
                              <!-- Image Slider -->
                <div id="imageCarousel" class="carousel slide mt-4" data-bs-ride="carousel" data-bs-interval="3500">
                    <div class="carousel-inner">
                        <!-- First Slide -->
                        <div class="carousel-item active">
                            <img src="images/Doc2.jpg" class="d-block w-100" alt="Image 1">
                        </div>
                        <!-- Second Slide -->
                        <div class="carousel-item">
                            <img src="images/Doc3.jpg" class="d-block w-100" alt="Image 2">
                        </div>
                        <!-- Third Slide -->
                        <div class="carousel-item">
                            <img src="images/Doc4.jpg" class="d-block w-100" alt="Image 3">
                        </div>
                    </div>
                    <!-- Previous Button -->
                    <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <!-- Next Button -->
                    <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
</div>
</div>
        <!-- Two Square Grids on the Right -->
        <div class="col-lg-4">
            <div class="row">
                <!-- Top Square -->
                <div class="col-12 mb-3">
                    <div class="modern-card">
                        <h3>Owner Statistics</h3>
                        <p>Comprehensive data on property ownership trends and demographics.</p>
                    </div>
                </div>
                <!-- Bottom Square -->
                <div class="col-12">
                    <div class="modern-card">
                        <h3>Property Listings</h3>
                        <p>Current listings and market analysis for properties in the area.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>