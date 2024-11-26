<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'database.php'; 

$conn = Database::getInstance();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_role = $_SESSION['user_type'] ?? 'user'; // Default to 'user' if role is not set

// Fetch owner data from `owners_tb` table
$query_owner_data = "SELECT own_id, own_fname, own_mname, own_surname FROM owners_tb LIMIT 3";
$result_owner_data = $conn->query($query_owner_data);

// Fetch total number of owners
$query_total_owners = "SELECT COUNT(*) AS total_owners FROM owners_tb";
$result_total_owners = $conn->query($query_total_owners);
$total_owners = $result_total_owners->fetch_assoc()['total_owners'];


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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJr+8+6g5K4r53m5s3xmw1Is0J6wBd04YOeFvXOsZTgmYF9flT/qe6LZ9s+0" crossorigin="anonymous">
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

    <?php if ($user_role === 'admin'): ?>
  <button onclick="location.href='Admin-Page-2.php'" style="background-color: #d6c21c; color: #000; border-radius: 5px; padding: 5px 15px; border: none; cursor: pointer;">
    Admin Dashboard
  </button>
<?php endif; ?>



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
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </nav>

<!-- Main Body -->
<div class="container-fluid p-0" style="margin-top: 80px;"> <!-- Adjust margin-top based on header height -->
    <div class="row">
        <!-- Left Section: Property Listing and Owner Statistics -->
        <div class="col-lg-7 col-md-12" style="padding-left: 10px;"> <!-- Left section occupies 7 columns on large screens -->
            
<!-- Property Listing -->
<div class="modern-card shadow-lg p-5 mb-4 rounded-lg" style="height: auto; width: 100%; position: relative;"> <!-- Adjusted height -->
    <h4 class="font-weight-bold custom-text-color">Property Listings</h4>
    <p class="lead custom-text-color">Current number of Properties</p>
    <div class="text-center">
        <i class="fas fa-building fa-4x text-warning"></i>
    </div>
    <div class="mt-4 text-center">
        <h5>Total Properties: <?php echo $total_properties; ?></h5>
    </div>

    <!-- Button to Go to RPU List -->
    <div class="mt-3 d-flex justify-content-end" style="position: absolute; bottom: 20px; right: 20px;">
    <a href="Real-Property-Unit-List.php" class="btn" style="background-color: #489474; border-color: #489474; color: white; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">Go to RPU List</a>
</div>
</div>

            
            <!-- Owner Statistics -->
            <div class="modern-card shadow-lg p-5 mb-4 rounded-lg" style="height: auto; width: 100%; position: relative;"> <!-- Adjusted height -->
                <h4 class="font-weight-bold custom-text-color">Owner Statistics</h4>
                <p class="lead custom-text-color">Summary of current users.</p>
                <div class="text-center">
                    <i class="fas fa-users fa-3x text-warning"></i>
                    <h5>Total Owners: <?php echo $total_owners; ?></h5>
                </div>
                <div class="mt-4">
                    <!-- Owner Data Table -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Surname</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result_owner_data->num_rows > 0) {
                                while ($row = $result_owner_data->fetch_assoc()) {
                                    echo "<tr>";

                                    echo "<td>" . htmlspecialchars($row['own_fname']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['own_mname']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['own_surname']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No owners available.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- Button to Go to Owner's List -->
<div class="mt-3 d-flex justify-content-end">
    <a href="Own_list.php" class="btn" style="background-color: #489474; border-color: #489474; color: white; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);">Go to Owner's List</a>
</div>
            </div>
        </div>

        <!-- Right Section: Main Content -->
        <div class="col-lg-5 col-md-12"> 
            <div class="modern-card shadow-lg p-4 rounded-lg" style="height: 100%;">
                <h3 class="font-weight-bold custom-text-color">CITIZEN'S CHARTER OFFICE OF THE PROVINCIAL ASSESSOR</h3>
                <h5 class="text-secondary mb-4 custom-text-color">Capitol, Daet, Camarines Norte</h5>
                <p class="lead custom-text-color">The Office of the Provincial Assessor is a key entity in the Provincial Government, operating under Republic Act No. 7160, also known as the Local Government Code of 1991.</p>
                <p class="custom-text-color">Its primary goal is to perform duties related to real property taxation, adhering to fundamental principles such as:</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Appraising real property at its current and fair market value.</li>
                    <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Classification of property for assessment based on actual use.</li>
                    <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring uniform assessment classification within the local government unit.</li>
                    <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Restricting private persons from performing appraisal and assessment tasks.</li>
                    <li class="list-group-item custom-text-color"><i class="fas fa-check-circle text-primary"></i> Ensuring equitable property appraisal and assessment.</li>
                </ul>
                <p class="mt-3 custom-text-color">Under Sec. 472, par (b) of the Code, the Office has the following key responsibilities:</p>
                <ol class="ml-3">
                    <li class="custom-text-color">Enforcing laws and policies regarding property appraisal and taxation.</li>
                    <li class="custom-text-color">Reviewing and recommending improvements to policies and practices in property valuation and assessment.</li>
                    <li class="custom-text-color">Establishing efficient property assessment systems and maintaining accurate property records.</li>
                    <li class="custom-text-color">Ensuring proper tax mapping and conducting frequent surveys for verification of listed properties.</li>
                    <li class="custom-text-color">Coordinating with municipal assessors for better data management and tax mapping operations.</li>
                </ol>
                <p class="mt-4 custom-text-color">The office operates under the supervision of the Governor, with technical oversight from the Department of Finance and the Bureau of Local Government Finance.</p>
                <p class="font-weight-bold custom-text-color mt-4">Currently, the Office is composed of three divisions: Assessment and Appraisal, Tax Mapping and Records, and Administrative.</p>
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


<!-- JS Files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.js"></script>

</body>
</html>