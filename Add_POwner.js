document.addEventListener("DOMContentLoaded", function () {
    // List of field IDs to apply capitalization
    const capitalizeFields = ["firstName", "middleName", "surname", "street", "barangay", "district", "city", "province"];

    // Function to capitalize the first letter
    function capitalizeFirstLetter(event) {
      const input = event.target;
      input.value = input.value.charAt(0).toUpperCase() + input.value.slice(1).toLowerCase();
    }

    // Add capitalization event listeners
    capitalizeFields.forEach(id => {
      const field = document.getElementById(id);
      if (field) {
        field.addEventListener("input", capitalizeFirstLetter);
      }
    });

    // List of field IDs to restrict to numbers only with a max length of 11
    const numericFields = ["tinNumber", "telephone", "fax"];
    const maxLength = 11;

    // Function to restrict input to numbers and enforce max length
    function restrictToNumbers(event) {
      const input = event.target;
      input.value = input.value.replace(/\D/g, ""); // Remove non-numeric characters
      if (input.value.length > maxLength) {
        input.value = input.value.slice(0, maxLength); // Enforce max length
      }
    }

    // Add numeric restriction event listeners
    numericFields.forEach(id => {
      const field = document.getElementById(id);
      if (field) {
        field.addEventListener("input", restrictToNumbers);
      }
    });
});