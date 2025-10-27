// Eyecandy password toggle + Countdown that updates the existing error <p> element
document.addEventListener("DOMContentLoaded", () => {
  // ---------- Password toggle ----------
  const passwordField = document.getElementById('password');
  const togglePasswordButton = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  if (togglePasswordButton && passwordField && eyeIcon) {
    togglePasswordButton.addEventListener('click', () => {
      const isPassword = passwordField.type === 'password';
      passwordField.type = isPassword ? 'text' : 'password';

      // toggle icons cleanly
      eyeIcon.classList.toggle('fa-eye');
      eyeIcon.classList.toggle('fa-eye-slash');
    });
  }

  // ---------- Countdown (reuse existing error paragraph) ----------
  const username = document.getElementById("username");
  const password = document.getElementById("password");
  const submitBtn = document.querySelector("button[type='submit']");
  const loginCard = document.querySelector(".login-card");

  // Select the existing error paragraph that uses inline red style in the original design
  const errorP = loginCard ? loginCard.querySelector("p[style*='color: red']") : null;

  // Proceed only if lockExpires was injected by PHP, lock active, and we have the error paragraph
  if (typeof lockExpires !== "undefined" && lockExpires > Date.now() && loginCard && errorP) {
    let remaining = Math.floor((lockExpires - Date.now()) / 1000);

    if (username) username.disabled = true;
    if (password) password.disabled = true;
    if (submitBtn) submitBtn.disabled = true;

    const format = (secs) => {
      const mm = Math.floor(secs / 60).toString().padStart(2, '0');
      const ss = (secs % 60).toString().padStart(2, '0');
      return `${mm}:${ss}`;
    };

    // Update the existing red error paragraph with countdown (preserve its red style)
    const tick = () => {
      // Replace the original message with countdown + short text
      errorP.textContent = `Too many failed attempts. Wait ${format(remaining)} to try again.`;
      remaining--;
      if (remaining < 0) {
        clearInterval(intervalId);
        // Reload to let server reset attempts and re-enable inputs
        location.reload();
      }
    };

    tick();
    const intervalId = setInterval(tick, 1000);
  }
});
