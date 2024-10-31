<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once 'database.php';

// Get the database connection
$conn = Database::getInstance(); // Correctly retrieve the connection instance

// Check if it's an AJAX search
if (isset($_GET['search'])) {
    // Sanitize the search term
    $searchTerm = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM owners_tb WHERE own_fname LIKE ? OR own_surname LIKE ?");
    $likeTerm = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ownerId = htmlspecialchars($row['own_id'], ENT_QUOTES);
            $fullName = htmlspecialchars($row['own_fname'] . ', ' . $row['own_surname'], ENT_QUOTES);
            $address = htmlspecialchars($row['street'] . ', ' . $row['barangay'] . ', ' . $row['city'] . ', ' . $row['province'], ENT_QUOTES);

            // Output only the table rows
            echo "<tr>";
            echo "<td>" . $ownerId . "</td>";
            echo "<td>" . $fullName . "</td>";
            echo "<td>" . $address . "</td>";
            echo "<td><input type='checkbox' name='selected_ids[]' value='" . $ownerId . "'></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No data found</td></tr>";
    }
    $stmt->close();
    exit; // Prevent any further output
}
?>
