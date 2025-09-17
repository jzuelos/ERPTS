// Disable all form elements
const plantsSectionForm = document.querySelector('#plants-section form');
plantsSectionForm.querySelectorAll('input, select, textarea, button').forEach(el => {
  el.disabled = true;
});

// Form submission handling
const plantsForm = document.getElementById('plantsForm');
plantsForm.addEventListener('submit', function(e){
  e.preventDefault(); // prevent form submission for demonstration
  if(this.checkValidity()){
    alert('Form submitted successfully!');
    const modalEl = document.getElementById('editPnT');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
  } else {
    this.reportValidity();
  }
});

// Modal close button confirmation
const closeBtn = document.getElementById('modalCloseBtn');
closeBtn.addEventListener('click', function(event) {
  // Prevent automatic closing for now
  event.preventDefault();

  // Show confirmation
  const confirmClose = confirm("The form data will not be saved. Are you sure you want to close?");
  if (confirmClose) {
    // Clear form
    plantsForm.reset();

    // Hide the modal manually
    const modalEl = closeBtn.closest('.modal');
    const modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
  }
});

// Warn user before leaving or refreshing the page
window.addEventListener('beforeunload', function (e) {
    e.preventDefault();
    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
});
