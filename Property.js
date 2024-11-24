document.getElementById("applyButton").addEventListener("click", function() {
    var selectedOption = document.getElementById("classification").value;
    if (selectedOption === "Subclasses") {
        document.getElementById("detailsForm").style.display = "block";
    } else {
        document.getElementById("detailsForm").style.display = "none";
    }
});