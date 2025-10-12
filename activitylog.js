document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggleLogsBtn");
  const activityLogs = document.getElementById("activitylogs");
  const loginLogs = document.getElementById("loginlogs");
  const urlParams = new URLSearchParams(window.location.search);
  let logType = urlParams.get("log_type") || "activity";

  // Function to update button and table visibility
  function showTable(type) {
    if (type === "login") {
      activityLogs.classList.add("d-none");
      loginLogs.classList.remove("d-none");
      toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i> Show Activity Logs';
      toggleBtn.classList.remove("btn-primary");
      toggleBtn.classList.add("btn-secondary");
    } else {
      loginLogs.classList.add("d-none");
      activityLogs.classList.remove("d-none");
      toggleBtn.innerHTML = '<i class="fas fa-sign-in-alt me-1"></i> Show Log In Logs';
      toggleBtn.classList.remove("btn-secondary");
      toggleBtn.classList.add("btn-primary");
    }
  }

  // Initialize the correct table on page load
  showTable(logType);

  // When toggle button is clicked
  toggleBtn.addEventListener("click", function () {
    logType = logType === "activity" ? "login" : "activity";

    // Update the URL parameter without reloading
    urlParams.set("log_type", logType);
    const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
    window.history.replaceState({}, "", newUrl);

    showTable(logType);
  });
});


// Toggle full/truncated activity text
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation(); // Prevent row click conflict
      const id = this.getAttribute('data-id');
      const textDiv = document.getElementById('activity' + id);
      const isTruncated = textDiv.classList.contains('text-truncate');

      if (isTruncated) {
        textDiv.classList.remove('text-truncate');
        textDiv.style.whiteSpace = 'normal';
        this.classList.remove('bi-caret-down-fill');
        this.classList.add('bi-caret-up-fill');
      } else {
        textDiv.classList.add('text-truncate');
        textDiv.style.whiteSpace = 'nowrap';
        this.classList.remove('bi-caret-up-fill');
        this.classList.add('bi-caret-down-fill');
      }
    });
  });
});