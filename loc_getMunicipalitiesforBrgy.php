<?php
// Include the database connection
include('database.php');

$conn = Database::getInstance();

// Fetch all municipalities that are active
$query = "SELECT m_id, m_description FROM municipality WHERE m_status = 'Active'";
$result = $conn->query($query);

// Check if there are results
if ($result->num_rows > 0) {
    // Output data of each row as an HTML option
    while ($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['m_id'] . '">' . $row['m_description'] . '</option>';
    }
} else {
    echo '<option value="">No municipalities available</option>';
}

// Close the database connection
$conn->close();
?>