<?php
session_start();
require './includes/conn.php';
require './includes/header.php';
require './includes/sidebar.php';
if (isset($_POST['edit_company'])) {
    // Sanitize and validate user inputs
    $business_name = htmlspecialchars(trim($_POST['business_name']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $business_type = htmlspecialchars(trim($_POST['business_type']));

    // Check if inputs are not empty
    if (!empty($business_name) && !empty($phone_number) && !empty($business_type)) {
        // Update company table
        $update_company_query = "UPDATE company SET business_name = ?, phone_number = ?, business_type = ? WHERE company_id = ?";
        $stmt = $conn->prepare($update_company_query);
        $stmt->bind_param("sssi", $business_name, $phone_number, $business_type, $_SESSION['company_id']);

        if ($stmt->execute()) {
            // Redirect if update is successful
            echo '<script>window.location.href = "profile.php";</script>';
            exit; // Ensure PHP execution stops after the redirect
        } else {
            $errorMessage = "Error updating company information: " . $conn->error;
        }
    } else {
        $errorMessage = "All fields are required.";
    }
}


$errorMessage = "";
$company_id = $_SESSION['company_id'];
$company_query = "SELECT * FROM company WHERE company_id = ?";
$stmt = $conn->prepare($company_query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$company_result = $stmt->get_result();
$company_data = $company_result->fetch_assoc();

$branch_query = "SELECT branch_name,location FROM company_branch WHERE company_id = ?";
$stmt_branch = $conn->prepare($branch_query);
$stmt_branch->bind_param("i", $company_id);
$stmt_branch->execute();
$branch_result = $stmt_branch->get_result();
while ($row = $branch_result->fetch_assoc()) {
    $branch_data[] = $row;
};
?>

<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">Edit Company</h3>
                </div>
            </div>
        </div>
        <?php echo $errorMessage; ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Company Info</h4>
                        <form action="edit_company.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Macaga Ganacsiga:</label>
                                        <input type="text" required class="form-control" name="business_name" value="<?php echo htmlspecialchars($company_data['business_name']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($company_data['phone_number']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nooca Ganacsiga:</label>
                                        <input type="text" class="form-control" name="business_type" value="<?php echo htmlspecialchars($company_data['business_type']); ?>">
                                    </div>
                                </div>
                                <!-- <? // php // foreach ($branch_data as $branch) : 
                                        ?>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Macaga Laanta</label>
                                            <input type="text" class="form-control" name="phone_number" value="<?php echo htmlspecialchars($branch['branch_name']); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Goobta :</label>
                                            <input type="text" class="form-control" name="business_type" value="<?php echo htmlspecialchars($branch['location']); ?>">
                                        </div>
                                    <?php //endforeach; 
                                    ?> -->
                                <!-- </div> -->
                            </div>
                            <div class="text-center">
                                <button type="submit" name="edit_company" class="btn btn-primary">Update Company Info</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>