<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; // Include your database connection

$conn = Database::getInstance();
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch total number of owners
$query_owners = "SELECT COUNT(*) AS total_owners FROM owners_tb";
$result_owners = $conn->query($query_owners);
$total_owners = $result_owners->fetch_assoc()['total_owners'];

// Fetch total number of properties
$query_properties = "SELECT COUNT(*) AS total_properties FROM p_info";
$result_properties = $conn->query($query_properties);
$total_properties = $result_properties->fetch_assoc()['total_properties'];
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
  <link rel="stylesheet" href="main_layout.css">
  <link rel="stylesheet" href="Home.css">
  <title>Electronic Real Property Tax System</title>
</head>

<body>
  <!-- Header Navigation -->
  <?php include 'header.php'; ?>

  <!-- Main Body -->
  <div class="container-fluid p-0" style="margin-top: 80px;"> <!-- Adjust margin-top based on header height -->
    <div class="row">
      <!-- Left Section: Property Listing and Owner Statistics -->
      <div class="col-lg-7" style="padding-left: 20px;"> <!-- Added padding-left -->
        <!-- Property Listing -->
        <div class="modern-card shadow-lg p-5 mb-4 rounded-lg" style="height: 500px; width: 100%;">
          <h4 class="font-weight-bold custom-text-color">Property Listings</h4>
          <p class="lead custom-text-color">Up-to-date property listings and market analysis for residential,
            commercial, and industrial properties in the area.</p>
          <div class="text-center">
            <i class="fas fa-building fa-4x text-warning"></i>
          </div>
          <div class="mt-4 text-center">
            <h5>Total Properties: <?php echo $total_properties; ?></h5>
          </div>
        </div>
        <!-- Owner Statistics -->
        <div class="modern-card shadow-lg p-5 mb-4 rounded-lg" style="height: 300px; width: 100%;">
          <h4 class="font-weight-bold custom-text-color">Owner Statistics</h4>
          <p class="custom-text-color">Comprehensive data on property ownership trends, demographics, and distribution
            across the province.</p>
          <div class="text-center">
            <i class="fas fa-users fa-3x text-warning"></i>
          </div>
          <div class="mt-4 text-center">
            <h5>Total Owners: <?php echo $total_owners; ?></h5>
          </div>
        </div>
      </div>

      <!-- Right Section: Main Content -->
      <div class="col-lg-4">
        <div class="modern-card shadow-lg p-4 rounded-lg" style="height: 100%;">
          <h3 class="font-weight-bold custom-text-color">CITIZEN'S CHARTER OFFICE OF THE PROVINCIAL ASSESSOR</h3>
          <h5 class="text-secondary mb-4 custom-text-color">Capitol, Daet, Camarines Norte</h5>
          <p class="lead custom-text-color">The Office of the Provincial Assessor is a key entity in the Provincial
            Government, operating under Republic Act No. 7160, also known as the Local Government Code of 1991.</p>
          <p class="custom-text-color">Its primary goal is to perform duties related to real property taxation, adhering
            to fundamental principles such as:</p>
          <ul class="list-group list-group-flush">
            <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Appraising
              real property at its current and fair market value.</li>
            <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i>
              Classification of property for assessment based on actual use.</li>
            <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
              uniform assessment classification within the local government unit.</li>
            <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Restricting
              private persons from performing appraisal and assessment tasks.</li>
            <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring
              equitable property appraisal and assessment.</li>
          </ul>
          <p class="mt-3 custom-text-color">Under Sec. 472, par (b) of the Code, the Office has the following key
            responsibilities:</p>
          <ol class="ml-3">
            <li class="custom-text-color">Enforcing laws and policies regarding property appraisal and taxation.</li>
            <li class="custom-text-color">Reviewing and recommending improvements to policies and practices in property
              valuation and assessment.</li>
            <li class="custom-text-color">Establishing efficient property assessment systems and maintaining accurate
              property records.</li>
            <li class="custom-text-color">Ensuring proper tax mapping and conducting frequent surveys for verification
              of listed properties.</li>
            <li class="custom-text-color">Coordinating with municipal assessors for better data management and tax
              mapping operations.</li>
          </ol>
          <p class="mt-4 custom-text-color">The office operates under the supervision of the Governor, with technical
            oversight from the Department of Finance and the Bureau of Local Government Finance.</p>
          <p class="font-weight-bold custom-text-color mt-4">Currently, the Office is composed of three divisions:
            Assessment and Appraisal, Tax Mapping and Records, and Administrative.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-body-tertiary text-center text-lg-start mt-auto">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
        <span class="text-muted">© 2024 Electronic Real Property Tax System. All Rights Reserved.</span> 
        </div>
    </footer>

  <!-- JS Files -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.js"></script>

</body>

</html>