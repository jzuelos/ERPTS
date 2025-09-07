<?php 
$user_role = $_SESSION['user_type'] ?? 'user'; // Default to 'user' if role is not set
?>

<!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top">
<div class="container-fluid px-3 d-flex align-items-center justify-content-between">
<a class="navbar-brand py-2 d-flex align-items-center" href="Home.php">
  <img src="/ERPTS/images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="me-2" alt="">
  <span class="fs-5 fw-semibold text-white">Electronic Real Property Tax System</span>
</a>



    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <?php if ($user_role === 'admin'): ?>
      <button onclick="location.href='Admin-Page-2.php'"
        class="btn btn-warning ms-2 me-auto admin-dashboard-btn">
        Admin Dashboard
      </button>
    <?php endif; ?>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link px-3" href="Home.php">Home</a>
        </li>
        
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle px-3" href="RPU-Management.php" id="navbarDropdown" role="button">
          RPU Management
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="Real-Property-Unit-List.php">RPU List</a>
          <a class="dropdown-item" href="Real-Property-Unit-List.php">FAAS</a>
          <a class="dropdown-item" href="Tax-Declaration-List.php">Tax Declaration</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="Track.php">Track Paper</a>
        </div>
      </li>

        <li class="nav-item">
          <a class="nav-link px-3" href="Transaction.php">Transaction</a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link px-3" href="Reports.php">Reports</a>
        </li>
        
        <li class="nav-item ms-3">
          <a href="logout.php" class="btn btn-danger">Log Out</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

  <script>
  document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector(".navbar");
    const navbarHeight = navbar.offsetHeight;
    document.body.style.paddingTop = navbarHeight + "px";
  });
</script>


