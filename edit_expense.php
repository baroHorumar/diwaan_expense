<?php
require './includes/conn.php';

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(array("error" => "Connection failed")));
}

$json_data = file_get_contents('php://input');

$data = json_decode($json_data, true);


// Sanitize input data using the validateInput function
$expenseId = validateInput($data["expense_id"]);
$amount = (float) validateInput($data["amount"]);
$description = validateInput($data["description"]);
$date = date("Y-m-d");
$updatedByUserId = validateInput($data["updated_by_user_id"]);
$status = validateInput($data["status"]);

// Prepare and bind statement for transaction update
$stmt = $conn->prepare("UPDATE expenses SET amount = ?, description = ?, date = ?, updated_by_user_id = ?, status = ?, updated_at = NOW() WHERE expense_id = ?");
$stmt->bind_param("dssisi", $amount, $description, $date, $updatedByUserId, $status, $expenseId);

// Execute statement for transaction insertion
if ($stmt->execute()) {
    // Transaction inserted successfully
    die(json_encode(array("success" => true)));
} else {
    die(json_encode(array("error" => "Error inserting transaction: " . $stmt->error)));
}
function validateInput($data)
{
    // You might want to customize this based on your requirements
    return htmlspecialchars(trim($data));
}
