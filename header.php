<?php
$user_role = $_SESSION['user_type'] ?? 'user';
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
      <ul class="navbar-nav ms-auto align-items-center">
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

        <!-- Admin notification bell -->
        <?php if ($user_role === 'admin'): ?>
        <li class="nav-item ms-3">
          <a class="nav-link position-relative" href="#" id="notificationBell">
            <i class="fas fa-bell fa-lg text-warning"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifBadge">
              3
              <span class="visually-hidden">unread notifications</span>
            </span>
          </a>
        </li>
        <?php endif; ?>

        <li class="nav-item ms-3">
          <a href="/ERPTS/logout.php" class="btn btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i>Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Notification Dropdown Panel -->
<div id="notificationPanel" class="notification-dropdown shadow-lg">
  <div class="notif-header">
    <h5 class="mb-0">Notifications</h5>
    <button id="notifSettings" class="btn-icon">
      <i class="fas fa-ellipsis-h"></i>
    </button>
  </div>

  <div class="notif-tabs">
    <button class="notif-tab active" data-tab="all">All</button>
    <button class="notif-tab" data-tab="unread">Unread</button>
  </div>

  <div class="notif-content" id="notificationList">
    <div class="notif-section-header">
      <span class="section-title">Recent</span>
      <a href="#" class="see-all-link">See all</a>
    </div>

    <!-- Loading State -->
    <div class="notif-loading" id="notifLoading">
      <div class="text-center py-4">
        <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
        <p class="mt-2 text-muted">Loading notifications...</p>
      </div>
    </div>

    <!-- Empty State -->
    <div class="notif-empty" id="notifEmpty" style="display: none;">
      <div class="text-center py-5">
        <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
        <p class="text-muted">No notifications yet</p>
      </div>
    </div>

    <!-- Notifications will be loaded here dynamically -->
  </div>

  <div class="notif-footer">
    <a href="#" class="see-previous-link">See previous notifications</a>
  </div>
</div>

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
      }, 10);
    });

    dropdown.addEventListener('mouseleave', function () {
      const menu = this.querySelector('.dropdown-menu');
      timeout = setTimeout(() => {
        menu.style.opacity = '0';
        setTimeout(() => {
          menu.style.display = 'none';
        }, 300);
      }, 200);
    });
  });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const bell = document.getElementById("notificationBell");
  const panel = document.getElementById("notificationPanel");
  const notifTabs = document.querySelectorAll(".notif-tab");
  const notifBadge = document.getElementById("notifBadge");
  const notifList = document.getElementById("notificationList");
  const notifLoading = document.getElementById("notifLoading");
  const notifEmpty = document.getElementById("notifEmpty");
  const markAllReadBtn = document.getElementById("markAllReadBtn");
  
  let allNotifications = [];
  let currentTab = 'all';

  // Function to get icon based on notification type
  function getNotificationIcon(type) {
    const icons = {
      'danger': 'fa-exclamation-circle',
      'warning': 'fa-exclamation-triangle',
      'success': 'fa-check-circle',
      'info': 'fa-info-circle',
      'default': 'fa-bell'
    };
    return icons[type] || icons['default'];
  }

  // Function to get avatar class based on type
  function getAvatarClass(type) {
    const classes = {
      'danger': 'bg-danger',
      'warning': 'bg-warning',
      'success': 'bg-success',
      'info': 'bg-info',
      'default': ''
    };
    return classes[type] || classes['default'];
  }

  // Function to render notifications
  function renderNotifications(notifications) {
    // Clear existing notifications (except header and states)
    const existingItems = notifList.querySelectorAll('.notif-item');
    existingItems.forEach(item => item.remove());

    if (notifications.length === 0) {
      notifLoading.style.display = 'none';
      notifEmpty.style.display = 'block';
      return;
    }

    notifLoading.style.display = 'none';
    notifEmpty.style.display = 'none';

    notifications.forEach(notif => {
      const notifItem = document.createElement('div');
      notifItem.className = 'notif-item' + (notif.unread ? ' unread' : '');
      notifItem.dataset.notifId = notif.id;

      const avatarClass = getAvatarClass(notif.type);
      const icon = getNotificationIcon(notif.type);

      notifItem.innerHTML = `
        <div class="notif-avatar ${avatarClass}">
          <i class="fas ${icon}"></i>
        </div>
        <div class="notif-details">
          <p class="notif-text"><strong>${notif.title}:</strong> ${notif.description}</p>
          <span class="notif-time">${notif.time}</span>
        </div>
        ${notif.unread ? '<span class="unread-indicator"></span>' : ''}
      `;

      // Add click handler to mark as read
      notifItem.addEventListener('click', function() {
        markAsRead(notif.id);
        this.classList.remove('unread');
        const indicator = this.querySelector('.unread-indicator');
        if (indicator) {
          indicator.remove();
        }
        updateBadgeCount();
      });

      notifList.appendChild(notifItem);
    });
  }

  // Function to fetch notifications
  async function fetchNotifications() {
    try {
      const response = await fetch('fetch_notifications.php');
      const data = await response.json();

      if (data.success) {
        allNotifications = data.notifications;
        renderNotifications(allNotifications);
        updateBadgeCount();
      } else {
        console.error('Error fetching notifications:', data.error);
        notifLoading.style.display = 'none';
        notifEmpty.style.display = 'block';
      }
    } catch (error) {
      console.error('Error:', error);
      notifLoading.style.display = 'none';
      notifEmpty.style.display = 'block';
    }
  }

  // Function to mark notification as read
  async function markAsRead(notifId) {
    // Update local state
    const notif = allNotifications.find(n => n.id === notifId);
    if (notif) {
      notif.unread = false;
    }
    
    // Send to backend
    try {
      await fetch('mark_notification_read.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({notif_id: notifId})
      });
    } catch (error) {
      console.error('Error marking notification as read:', error);
    }
  }

  // Function to update badge count
  function updateBadgeCount() {
    const unreadCount = allNotifications.filter(n => n.unread).length;
    if (notifBadge) {
      notifBadge.textContent = unreadCount;
      notifBadge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
    }
  }

  // Function to filter notifications by tab
  function filterNotifications(tab) {
    currentTab = tab;
    let filtered = allNotifications;
    
    if (tab === 'unread') {
      filtered = allNotifications.filter(n => n.unread);
    }
    
    renderNotifications(filtered);
  }

  // Toggle notification panel
  bell.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    
    const isVisible = panel.classList.contains("show");
    panel.classList.toggle("show");
    
    // Fetch notifications when opening panel
    if (!isVisible) {
      fetchNotifications();
    }
  });

  // Close when clicking outside
  document.addEventListener("click", function (e) {
    if (!panel.contains(e.target) && !bell.contains(e.target)) {
      panel.classList.remove("show");
    }
  });

  // Prevent panel from closing when clicking inside
  panel.addEventListener("click", function(e) {
    e.stopPropagation();
  });

  // Tab switching
  notifTabs.forEach(tab => {
    tab.addEventListener("click", function() {
      notifTabs.forEach(t => t.classList.remove("active"));
      this.classList.add("active");

      const tabType = this.getAttribute("data-tab");
      filterNotifications(tabType);
    });
  });

  // Mark all as read
  markAllReadBtn.addEventListener("click", async function(e) {
    e.preventDefault();
    
    allNotifications.forEach(n => n.unread = false);
    
    // Remove unread indicators from DOM
    document.querySelectorAll('.notif-item').forEach(item => {
      item.classList.remove('unread');
      const indicator = item.querySelector('.unread-indicator');
      if (indicator) {
        indicator.remove();
      }
    });
    
    updateBadgeCount();
    
    // Send to backend
    try {
      await fetch('mark_all_notifications_read.php', {method: 'POST'});
    } catch (error) {
      console.error('Error marking all as read:', error);
    }
  });

  // Close with Escape key
  document.addEventListener("keydown", function(e) {
    if (e.key === "Escape" && panel.classList.contains("show")) {
      panel.classList.remove("show");
    }
  });

  // Auto-refresh notifications every 30 seconds
  setInterval(() => {
    if (panel.classList.contains("show")) {
      fetchNotifications();
    }
  }, 30000);

  // Initial fetch on page load to update badge
  fetchNotifications();
});
</script>