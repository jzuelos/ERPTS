// Function to toggle password visibility
function togglePasswordVisibility(passwordField, toggleButton, eyeIcon) {

    const type = passwordField.type === 'password' ? 'text' : 'password';
    passwordField.type = type;

    // Change the icon based on the visibility of the password
    if (type === 'password') {
      eyeIcon.classList.remove('fa-eye-slash');
      eyeIcon.classList.add('fa-eye');
    } else {
      eyeIcon.classList.remove('fa-eye');
      eyeIcon.classList.add('fa-eye-slash');
    }
  }

  // Get the password input and the toggle button
  const passwordField = document.getElementById('password');
  const togglePasswordButton = document.getElementById('togglePassword');
  const eyeIcon = document.getElementById('eyeIcon');

  // Add event listener to toggle password visibility
  togglePasswordButton.addEventListener('click', function () {
    togglePasswordVisibility(passwordField, togglePasswordButton, eyeIcon);
  });