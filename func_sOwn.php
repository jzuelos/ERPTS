<?php
session_start(); // Start session at the top

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once 'database.php';

// Get the database connection
$conn = Database::getInstance(); // Correctly retrieve the connection instance

// Check if it's an AJAX search
if (isset($_POST['search'])) {
    // Sanitize the search term
    $searchTerm = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Prepare the SQL statement with LIMIT 5 to restrict results
    // Adding ORDER BY clause to sort by surname
    $stmt = $conn->prepare("
        SELECT * FROM owners_tb 
        WHERE own_fname LIKE ? OR own_surname LIKE ? 
        ORDER BY own_surname ASC, own_fname ASC 
        LIMIT 5
    ");

    if ($stmt) {
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
                echo "<td class='text-center align-middle'>" . $ownerId . "</td>";
                echo "<td class='text-center align-middle'>" . $fullName . "</td>";
                echo "<td class='text-center align-middle'>" . $address . "</td>";
                echo "<td class='text-center align-middle'><input type='checkbox' name='selected_ids[]' value='" . $ownerId . "'></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No data found</td></tr>";
        }

        $stmt->close(); // Close the statement
    } else {
        echo "<tr><td colspan='4'>Error preparing statement: " . htmlspecialchars($conn->error, ENT_QUOTES) . "</td></tr>"; // Handle error in statement preparation
    }

    exit; // Prevent any further output
}
?>