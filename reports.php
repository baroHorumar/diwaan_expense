<?php
session_start();
include('./includes/header.php');
include('./includes/sidebar.php');
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="page-header">
            <h3 class="page-title">Dhamaan Report</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Reports</li>
            </ul>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="report_read.php" method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Dooro Nooca Report</label>
                                <select name="status" class="form-control">
                                    <option value="INCOME">Dhakhli</option>
                                    <option value="EXPENSE">Kharash</option>
                                    <option value="approved">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>From</label>
                                <input class="form-control" name="from_date" type="date">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>To</label>
                                <input class="form-control" name="to_date" type="date">
                            </div>
                        </div>
                        <div class="col-md-12 text-center"> <!-- Adjust the width of the column according to your layout -->
                            <button name="report_read" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/select2.min.js"></script>

<?php
include('./includes/footer.php');
?>