document.addEventListener("DOMContentLoaded", function () {
  // Set today's date in date inputs
  const today = new Date().toISOString().split('T')[0];
  document.querySelectorAll('input[type="date"]').forEach(dateInput => {
    if (!dateInput.value) dateInput.value = today;
  });

  // Reset modal forms (if any)
  function resetForm() {
    document.querySelectorAll('.modal form').forEach(form => form.reset());
    document.querySelectorAll('.modal input, .modal select, .modal textarea').forEach(field => {
      if (field.type === "checkbox" || field.type === "radio") {
        field.checked = field.defaultChecked;
      } else if (field.tagName === "SELECT") {
        field.selectedIndex = 0;
      } else {
        field.value = "";
      }
    });
  }

  // Restrict unwanted characters (live filtering)
  document.querySelectorAll('input[pattern]').forEach(input => {
    input.addEventListener("input", () => {
      let regex = new RegExp(input.getAttribute("pattern"));
      if (!regex.test(input.value)) {
        input.value = input.value.replace(/[^A-Za-z0-9 ]/g, "");
      }
    });
  });

  // Restrict numeric inputs to digits only + max length
  document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener("input", () => {
      input.value = input.value.replace(/[^0-9]/g, ""); // keep digits only
      let maxLength = input.getAttribute("maxlength");
      if (maxLength && input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength);
      }
    });
  });
});

// Warn the user on page unload
window.addEventListener('beforeunload', function (e) {
    e.preventDefault(); // Some browsers require this
    e.returnValue = ''; // This triggers the confirmation dialog
});


