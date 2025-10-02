document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggleLogsBtn");
  const activityLogs = document.getElementById("activitylogs");
  const loginLogs = document.getElementById("loginlogs");

  toggleBtn.addEventListener("click", function () {
    const isActivityVisible = !activityLogs.classList.contains("d-none");

    if (isActivityVisible) {
      // Hide Activity Logs, Show Login Logs
      activityLogs.classList.add("d-none");
      loginLogs.classList.remove("d-none");
      toggleBtn.innerHTML = '<i class="fas fa-list me-1"></i> Show Activity Logs';
      toggleBtn.classList.remove("btn-primary");
      toggleBtn.classList.add("btn-secondary");
    } else {
      // Show Activity Logs, Hide Login Logs
      loginLogs.classList.add("d-none");
      activityLogs.classList.remove("d-none");
      toggleBtn.innerHTML = '<i class="fas fa-sign-in-alt me-1"></i> Show Log In Logs';
      toggleBtn.classList.remove("btn-secondary");
      toggleBtn.classList.add("btn-primary");
    }
  });
});
