<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';

$employees = array(); // Initialize employees array

$company_id = $_SESSION['company_id']; // Example company ID

$sql = "SELECT * FROM employee WHERE company_id = ? ORDER BY hire_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch employee data and store in array
while ($row = $result->fetch_assoc()) {
    $employees[] = $row;
}
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title text-center">Dhamaan Shaqaalaha</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Invoice Grid</li>
                    </ul>
                </div>
                <div class="col-auto">
                    <a href="add_employee.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                    </a>
                    <a class="btn btn-primary filter-btn" href="javascript:void(0);" id="filter_search">
                        <i class="fas fa-filter"></i>
                    </a>
                </div>
            </div>
        </div>
        <div id="filter_inputs" class="card filter-card">
            <div class="card-body pb-0">
                <!-- Filter inputs -->
            </div>
        </div>
        <div class="row">
            <!-- Displaying employees using the provided card structure -->
            <?php foreach ($employees as $employee) : ?>
                <div class="col-sm-6 col-lg-4 col-xl-3">
                    <div class="card <?php echo $employee['is_active'] ? 'bg-success-light' : 'bg-warning-light'; ?>">
                        <div class="card-body">
                            <div class="inv-header mb-3">
                                <a class="text-dark" href="#" onclick="displayEmployeeDetails(<?php echo $employee['employee_id']; ?>)">
                                    <h3 class="text-center"><?php echo $employee['full_name']; ?></h3>
                                </a>
                            </div>
                            <div class="invoice-id mb-3">
                                <p class="text-yellow text-center ">Phone Number:<?php echo ' ' . $employee['phone_number']; ?></p>
                            </div>
                            <div class="row align-items-center">
                                <div class="col">
                                    <span class="text-sm text-muted"><i class="far fa-money-bill-alt"></i>Hadhaa</span>
                                    <h6 class="mb-0">$<?php echo $employee['salary']; ?></h6>
                                </div>
                                <div class="col-auto text-end">
                                    <span class="text-sm text-muted"><i class="far fa-calendar-alt"></i>La Abuuray</span>
                                    <h6 class="mb-0"><?php echo date('j-M-Y', strtotime($employee['hire_date'])); ?></h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="badge <?php echo $employee['is_active'] ? 'bg-success-light' : 'bg-warning-light'; ?>"><?php echo $employee['is_active'] ? 'Active' : 'Inactive'; ?></span>
                                </div>
                                <div class="col d-flex justify-content-end">
                                    <!-- Modal button to view employee details -->
                                    <button type="button" class="btn btn-primary btn-sm rounded-pill circle-btn c" name="view_employee" style="margin-right: 5px;" onclick="displayEmployeeDetails(<?php echo $employee['employee_id']; ?>)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="edit_employee.php" method="POST">
                                        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill circle-btn c" name="update_employee">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Employee Details Modal -->
            <div id="employeeDetailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="employeeDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-full-width">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="employeeDetailsModalLabel">Employee Details</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="employeeDetailsModalBody">
                            <!-- Employee details will be displayed here -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function displayEmployeeDetails(employeeId) {
        // You can fetch employee details from the server using AJAX and populate the modal here
        // For demonstration purposes, I'm simply displaying a placeholder message
        var modalBody = document.getElementById('employeeDetailsModalBody');
        modalBody.innerHTML = '<p>Employee ID: ' + employeeId + '</p>' +
            '<p>Full Name: <?php echo $employee['full_name']; ?></p>' +
            '<p>Phone Number: <?php echo $employee['phone_number']; ?></p>' +
            '<p>Hire Date: <?php echo $employee['hire_date']; ?></p>' +
            '<p>Is Active: <?php echo $employee['is_active'] ? 'Yes' : 'No'; ?></p>' +
            '<p>User ID: <?php echo $employee['user_id']; ?></p>' +
            '<p>Company ID: <?php echo $employee['company_id']; ?></p>' +
            '<p>Created By: <?php echo $employee['created_by']; ?></p>' +
            '<p>Salary: <?php echo $employee['salary']; ?></p>';

        // Show the modal
        $('#employeeDetailsModal').modal('show');
    }
</script>

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