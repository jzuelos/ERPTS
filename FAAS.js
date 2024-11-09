function toggleEdit(sectionId) {
    const section = document.getElementById(sectionId);
    const inputs = section.querySelectorAll('input, textarea, select');
    const removeButtons = section.querySelectorAll('button[id^="remove"]');
    const addButtons = section.querySelectorAll('a[id^="add"]');
    const searchButtons = section.querySelectorAll('a[id^="search"]');
  
    inputs.forEach(input => {
      input.disabled = !input.disabled;
    });
  
    removeButtons.forEach(button => {
      button.disabled = !button.disabled;
    });
  
    addButtons.forEach(button => {
      button.disabled = !button.disabled;
    });
    searchButtons.forEach(button => {
      button.disabled = !button.disabled;
    });
  }
  