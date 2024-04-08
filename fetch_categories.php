<?php
require './includes/conn.php';

// Get the company_id from the Flutter app
$companyId = (int)$_POST["company_id"];
// Prepare and execute the SQL statement to fetch categories by company_id
$stmt = $conn->prepare("SELECT category_id, category_name FROM categories WHERE company_id = ?");
$stmt->bind_param("i", $companyId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch and store categories in an array
$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

// Close the database connection
$stmt->close();
$conn->close();

// Check if categories are found
if (empty($categories)) {
    // Return JSON response indicating no categories found
    die(json_encode(array("info" => "No categories found")));
} else {
    // Return JSON response with categories
    die(json_encode(array("info" => "Success", "categories" => $categories)));
}
