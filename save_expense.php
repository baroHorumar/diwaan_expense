<?php
require './includes/conn.php';

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("error" => "Connection failed")));
}

// Function to validate input
function validateInput($data)
{
    // You might want to customize this based on your requirements
    return htmlspecialchars(trim($data));
}

// Get the raw POST data
$json_data = file_get_contents('php://input');

// Decode the JSON data
$data = json_decode($json_data, true);

// Retrieve form data for transaction
$amount = validateInput($data["amount"]);
$description = validateInput($data["description"]);
$date = validateInput($data["date"]);
$companyId = validateInput($data["company_id"]);
$branchId = validateInput($data["branch_id"]);
$createdByUserId = validateInput($data["created_by_user_id"]);
$updatedByUserId = validateInput($data["updated_by_user_id"]);
$status = validateInput($data["status"]);

$stmt = $conn->prepare("INSERT INTO expenses (amount, description, date, company_id, branch_id, created_by_user_id, updated_by_user_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("dssiiiss", $amount, $description, $date, $companyId, $branchId, $createdByUserId, $updatedByUserId, $status);

// Execute statement for transaction insertion
if ($stmt->execute()) {
    // Transaction inserted successfully
    die(json_encode(array("success" => true)));
} else {
    die(json_encode(array("error" => "Error inserting transaction: " . $stmt->error)));
}
