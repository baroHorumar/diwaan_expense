<?php

require './includes/conn.php';

// Check if product ID is provided
if (isset($_POST["edit_product"])) {
    // Assign product ID from POST parameter
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $description = $_POST["description"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];
    $category_id = $_POST["category_id"];
    $user_id = $_POST["user_id"];
    $company_id = $_POST["company_id"];
    $created_by = $_POST["created_by"];
    $branch_id = $_POST["branch_id"];

    // Prepare and bind parameters
    $stmt = $conn->prepare("UPDATE products
SET product_name=?, description=?, price=?, quantity=?, category_id=?, user_id=?, company_id=?, created_by=?, branch_id=?
WHERE product_id=?");
    $stmt->bind_param("ssdiiiiiii", $product_name, $description, $price, $quantity, $category_id, $user_id, $company_id, $created_by, $branch_id, $product_id);

    // Execute statement
    if ($stmt->execute()) {
        $response = array(
            "success" => true,
            "message" => "Product updated successfully"
        );
    } else {
        $response = array(
            "success" => false,
            "message" => "Error updating product: " . $stmt->error
        );
    }

    // Close statement
    $stmt->close();
} else {
    $response = array(
        "success" => false,
        "message" => "One or more required fields are missing for product update"
    );
}

echo json_encode($response);
