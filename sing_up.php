<?php
require './includes/conn.php';
// Retrieve form data for admin signup
$businessName = validateInput($_POST["business_name"]);
$phoneNumber = validateInput($_POST["phone_number"]);
$location = validateInput($_POST["location"]);
$businessType = validateInput($_POST["business_type"]);
$username = validateInput($_POST["username"]);
$password = validateInput($_POST["password"]);
$registrationDate = date('Y-m-d'); // Set current date for registration
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    die(json_encode(array("error" => "Username already exists.")));
}
$stmt = $conn->prepare("INSERT INTO company (business_name, phone_number, business_type, registration_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $businessName, $phoneNumber, $businessType, $registrationDate);
if ($stmt->execute()) {
    $companyId = $stmt->insert_id;
    $role = "Admin";
    $status = 0;
    $deactivation_date = date('Y-m-d', strtotime($registrationDate . ' +30 days'));
    $stmt = $conn->prepare("INSERT INTO users (username, password, registration_date, deactivation_date, role, status, created_by, company_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssiii", $username, $hashedPassword, $registrationDate, $deactivation_date, $role, $status, $createdBy, $companyId);
    if ($stmt->execute()) {
        $userId = $stmt->insert_id;
        $branch = $businessName . ' branch 1';
        $stmt = $conn->prepare("INSERT INTO company_branch (company_id, branch_name, location) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $companyId, $branch, $location);
        if ($stmt->execute()) {
            die(json_encode(array("success" => "Company and branch information inserted successfully.")));
        } else {
            die(json_encode(array("error" => "Error inserting branch information")));
        }
    } else {
        die(json_encode(array("error" => "Error creating admin")));
    }
} else {
    die(json_encode(array("error" => "Error creating company")));
}
