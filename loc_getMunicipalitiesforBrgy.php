<?php
// loc_getMunicipalitiesforBrgy.php
include('database.php');

$conn = Database::getInstance();

// Fetch all active municipalities with district info (if any)
$query = "
  SELECT 
    municipality.m_id, 
    municipality.m_description AS municipality_name,
    district.description AS district_name,
    district.status AS district_status
  FROM municipality
  LEFT JOIN district ON municipality.m_id = district.m_id
  WHERE municipality.m_status = 'Active'
";

$result = $conn->query($query);

// Output data of each row as an HTML option
if ($result->num_rows > 0) {
    echo '<option value="" disabled selected>Select Location</option>';
    while ($row = $result->fetch_assoc()) {
        $m_id = htmlspecialchars($row['m_id'], ENT_QUOTES);
        $municipality = htmlspecialchars($row['municipality_name'], ENT_QUOTES);
        $district = $row['district_name'];
        $district_status = $row['district_status'];

        // Show "District - Municipality" only if district exists and is Active
        if (!empty($district) && $district_status === 'Active') {
            $display = htmlspecialchars($district . ' - ' . $municipality, ENT_QUOTES);
        } else {
            $display = $municipality;
        }

        echo '<option value="' . $m_id . '">' . $display . '</option>';
    }
} else {
    echo '<option value="">No municipalities available</option>';
}

$conn->close();
?>