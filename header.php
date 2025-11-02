<?php
$user_role = 'admin'; // Demo - change to $_SESSION['user_type'] ?? 'user';
?>

<!-- Header Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-custom fixed-top">
  <div class="container-fluid px-3 d-flex align-items-center justify-content-between">
    <a class="navbar-brand py-2 d-flex align-items-center" href="/Home.php">
      <img src="images/coconut_.__1_-removebg-preview1.png" width="50" height="50" class="me-2" alt="">
      <span class="fs-5 fw-semibold text-white">Electronic Real Property Tax System</span>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <?php if ($user_role === 'admin'): ?>
      <button onclick="location.href='Admin-Page-2.php'" class="btn btn-warning ms-2 me-auto admin-dashboard-btn">
        <i class="fas fa-user-shield me-2"></i>Admin Dashboard
      </button>
    <?php endif; ?>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link px-3" href="/ERPTS/Home.php">
            <i class="fas fa-home me-2"></i>Home
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle px-3" href="RPU-Management.php" id="navbarDropdown" role="button">
            <i class="fas fa-building me-2"></i>RPU Management
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="/ERPTS/Real-Property-Unit-List.php">
              <i class="fas fa-list me-2"></i>RPU List
            </a>
            <a class="dropdown-item" href="/ERPTS/Real-Property-Unit-List.php">
              <i class="fas fa-file-alt me-2"></i>FAAS
            </a>
            <a class="dropdown-item" href="/ERPTS/Tax-Declaration-List.php">
              <i class="fas fa-file-invoice-dollar me-2"></i>Tax Declaration
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="/ERPTS/Track.php">
              <i class="fas fa-search me-2"></i>Track Paper
            </a>
          </div>
        </li>

        <li class="nav-item">
          <a class="nav-link px-3" href="/ERPTS/Transaction.php">
            <i class="fas fa-exchange-alt me-2"></i>Transaction
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link px-3" href="/ERPTS/Reports.php">
            <i class="fas fa-chart-bar me-2"></i>Reports
          </a>
        </li>

        <li class="nav-item ms-3">
          <a href="/ERPTS/logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i>Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const navbar = document.querySelector(".navbar");
    const navbarHeight = navbar.offsetHeight;
    document.body.style.paddingTop = navbarHeight + "px";
    
    // Add active class to current page
    const currentLocation = location.pathname;
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      if (link.getAttribute('href') === currentLocation) {
        link.classList.add('active');
      }
    });
  });
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const dropdowns = document.querySelectorAll('.nav-item.dropdown');

  dropdowns.forEach(dropdown => {
    let timeout;

    dropdown.addEventListener('mouseenter', function () {
      clearTimeout(timeout);
      const menu = this.querySelector('.dropdown-menu');
      menu.style.display = 'block';
      setTimeout(() => {
        menu.style.opacity = '1';
      }, 10); // fade-in smoothly
    });

    dropdown.addEventListener('mouseleave', function () {
      const menu = this.querySelector('.dropdown-menu');
      timeout = setTimeout(() => {
        menu.style.opacity = '0';
        setTimeout(() => {
          menu.style.display = 'none';
        }, 300); // matches your CSS fade duration
      }, 200); // 200ms delay before hiding (you can tweak)
    });
  });
});
</script>
  