document.addEventListener("DOMContentLoaded", () => {
  // ========================================
  // PASSWORD TOGGLE
  // ========================================
  const passwordField = document.getElementById('password');
  const togglePasswordButton = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  if (togglePasswordButton && passwordField && eyeIcon) {
    togglePasswordButton.addEventListener('click', () => {
      const isPassword = passwordField.type === 'password';
      passwordField.type = isPassword ? 'text' : 'password';
      eyeIcon.classList.toggle('fa-eye');
      eyeIcon.classList.toggle('fa-eye-slash');
    });
  }

  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const submitBtn = document.getElementById("loginBtn");
  const loginForm = document.getElementById("loginForm");
  const errorAlert = document.getElementById("errorAlert");

  // ========================================
  // PERMANENT LOCK
  // ========================================
  if (typeof isPermanentLock !== "undefined" && isPermanentLock) {
    if (username) username.disabled = true;
    if (password) password.disabled = true;
    if (submitBtn) submitBtn.disabled = true;

    if (errorAlert) {
      errorAlert.style.backgroundColor = '#dc3545';
      errorAlert.style.color = '#fff';
      errorAlert.style.border = '3px solid #bd2130';
      errorAlert.style.fontWeight = 'bold';
      errorAlert.style.padding = '20px';
    }
    return;
  }

  // ========================================
  // TEMPORARY LOCK COUNTDOWN
  // ========================================
  if (typeof remainingSeconds !== "undefined" && remainingSeconds > 0) {
    let remaining = remainingSeconds;

    // Disable form
    if (username) username.disabled = true;
    if (password) password.disabled = true;
    if (submitBtn) submitBtn.disabled = true;

    // Simple countdown format
    const formatTime = (seconds) => {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    // Update every second
    const updateCountdown = () => {
      if (errorAlert) {
        errorAlert.innerHTML = `
          <i class="fas fa-clock"></i> 
          <strong>Account Locked</strong><br>
          Time remaining: <strong>${formatTime(remaining)}</strong><br>
          <small>Please wait before trying again</small>
        `;
      }

      remaining--;

      if (remaining < 0) {
        clearInterval(countdownInterval);
        location.reload();
      }
    };

    updateCountdown();
    const countdownInterval = setInterval(updateCountdown, 1000);

    if (errorAlert) {
      errorAlert.style.backgroundColor = '#fff3cd';
      errorAlert.style.color = '#856404';
      errorAlert.style.border = '2px solid #ffc107';
      errorAlert.style.fontWeight = 'bold';
      errorAlert.style.padding = '20px';
    }
  }

  // ========================================
  // CAPS LOCK WARNING
  // ========================================
  if (password) {
    const capsWarning = document.createElement('small');
    capsWarning.className = 'text-warning';
    capsWarning.style.display = 'none';
    capsWarning.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Caps Lock is ON';
    password.parentElement.appendChild(capsWarning);

    password.addEventListener('keyup', (e) => {
      const isCapsLock = e.getModifierState && e.getModifierState('CapsLock');
      capsWarning.style.display = isCapsLock ? 'block' : 'none';
    });
  }

  // ========================================
  // AUTO-HIDE ERRORS (NON-LOCKOUT)
  // ========================================
  if (errorAlert && typeof remainingSeconds === "undefined" && typeof isPermanentLock === "undefined") {
    setTimeout(() => {
      errorAlert.style.transition = 'opacity 0.5s';
      errorAlert.style.opacity = '0';
      setTimeout(() => errorAlert.remove(), 500);
    }, 5000);
  }

  // ========================================
  // PREVENT MULTIPLE SUBMISSIONS
  // ========================================
  if (loginForm) {
    let isSubmitting = false;
    
    loginForm.addEventListener('submit', (e) => {
      if (isSubmitting) {
        e.preventDefault();
        return false;
      }
      
      isSubmitting = true;
      
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
      }
    });
  }
});