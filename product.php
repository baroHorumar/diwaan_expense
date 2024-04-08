<?php
require './includes/conn.php';

// Get the data sent from the Flutter app
$productName = $_POST["product_name"];
$description = $_POST["description"];
$price = $_POST["price"];
$quantity = $_POST["quantity"];
$categoryId = $_POST["category_id"];
$userId = $_POST["user_id"];
$companyId = $_POST["company_id"];
$createdBy = $_POST["created_by"];
$branchId = $_POST["branch_id"];

// Prepare and execute the SQL statement to insert the product data into the database
$stmt = $conn->prepare("INSERT INTO products (product_name, description, price, quantity,  user_id, company_id, created_by, branch_id) VALUES (?, ?, ?,  ?, ?, ?, ?, ?)");
$stmt->bind_param("ssdiidiii", $productName, $description, $price, $quantity,  $userId, $companyId, $createdBy, $branchId);

if ($stmt->execute()) {
    // Product inserted successfully
    echo json_encode(array("info" => "Product added successfully"));
} else {
    // Failed to insert product
    echo json_encode(array("info" => "Failed to add product"));
}

// Close the database connection
$stmt->close();
$conn->close();
