<?php
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';
?>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card invoice-info-card">
                    <div class="card-body">
                        <div class="row align-items-center justify-content-center">
                            <div class="col-lg-6 col-md-6">
                                <div class="invoice-terms">
                                    <h6>Notes:</h6>
                                    <p class="mb-0">Enter customer notes or any other details</p>
                                </div>
                                <div class="invoice-terms">
                                    <h6>Terms and Conditions:</h6>
                                    <p class="mb-0">Enter customer notes or any other details</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="invoice-total-card">
                                    <div class="invoice-total-box">
                                        <div class="invoice-total-inner">
                                            <p>Taxable <span>$6,660.00</span></p>
                                            <p>Additional Charges <span>$6,660.00</span></p>
                                            <p>Discount <span>$3,300.00</span></p>
                                            <p class="mb-0">Sub total <span>$3,300.00</span></p>
                                        </div>
                                        <div class="invoice-total-footer">
                                            <h4>Total Amount <span>$143,300.00</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
</body>

</html>