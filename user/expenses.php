<?php
session_start();
require './includes/header.php';
require './includes/sidebar.php';
include 'includes/conn.php';
if (!isset($_SESSION['login'])) {
    header('location:login.php');
}
$expenses = array(); // Initialize expenses array

$company_id = $_SESSION['company_id']; // Example company ID

$sql = "SELECT * FROM expenses WHERE company_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch expenses data and store in array
while ($row = $result->fetch_assoc()) {
    $row['created_at'] = date('j-M-Y', strtotime($row['created_at'])); // Format date
    $expenses[] = $row;
}
?>
<style>
    /* General card styling */
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        background-color: #ffffff;
        cursor: pointer;
        /* Add cursor pointer */
    }

    /* Card body styling */
    .card-body {
        padding: 20px;
    }

    /* Dash widget header styling */
    .dash-widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Dash count styling (parent) */
    .dash-count {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        margin-right: 20px;
        /* Add some space between the counts and the icon */
    }

    /* Expense/date title styling (children) */
    .expense-title,
    .date-title {
        font-weight: bold;
        margin-bottom: 5px;
        color: #333333;
        /* Darken the title color */
    }

    /* Expense/date values styling (children) */
    .dash-counts p {
        margin-bottom: 5px;
        /* Add space between values */
        color: #666666;
        /* Adjust the value color */
    }

    /* Icon styling */
    .dash-widget-icon {
        color: #ffffff;
        border-radius: 50%;
        padding: 15px;
        font-size: 24px;
    }
</style>
<div id="full-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="fullWidthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-width">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="fullWidthModalLabel">Expense Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="expenseDetailsModalBody">
                <h5>Expense ID: <span id="expenseId"></span></h5>
                <p>Amount: <span id="amount"></span></p>
                <p>Description: <span id="description"></span></p>
                <p>Date: <span id="date"></span></p>
                <p>Company ID: <span id="companyId"></span></p>
                <p>Branch ID: <span id="branchId"></span></p>
                <p>Created By User ID: <span id="createdByUserId"></span></p>
                <p>Updated By User ID: <span id="updatedByUserId"></span></p>
                <p>Created At: <span id="createdAt"></span></p>
                <p>Updated At: <span id="updatedAt"></span></p>
                <p>Status: <span id="status"></span></p>
            </div>
            <div class="modal-footer">
                <form action="update_expenses.php" method="post">
                    <input type="hidden" id="expenseIdInput" name="expense_id" value="expenseId">
                    <button type="submit" class="btn btn-success">Edit</button>
                </form>
                <form id="deleteForm" onsubmit="deleteExpense(); return false;">
                    <input type="hidden" id="expenseIdInput" name="expense_id" value="">
                    <button type="submit" class="btn btn-warning" data-bs-dismiss="modal">Delete</button>
                </form>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="row">
            <?php foreach ($expenses as $expense) : ?>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="card" data-bs-toggle="modal" data-bs-target="#full-width-modal" data-expense-id="<?php echo $expense['expense_id']; ?>">
                        <div class="card-body">
                            <div class="dash-widget-header">
                                <span class="dash-widget-icon <?php echo ($expense['status'] == 'INCOME') ? 'bg-3' : 'bg-1'; ?>">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <div class="dash-count">
                                    <div class="expense-title"><?php echo $expense['status']; ?></div>
                                    <div class="dash-counts">
                                        <p><?php echo $expense['amount']; ?></p>
                                    </div>
                                </div>
                                <div class="dash-count">
                                    <div class="date-title">Date</div>
                                    <div class="dash-counts">
                                        <p><?php echo $expense['created_at']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="fixed-bottom mb-3 me-3 d-flex justify-content-center">
        <style>
            .rounded-btn {
                border-radius: 50%;
                width: 80px;
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #007bff;
                /* Your desired button color */
                color: #ffffff;
                /* Text color */
                font-size: 20px;
                text-decoration: none;
                /* Remove underline */
                display: inline-flex;
                /* Ensure the link behaves like a button */
                border: none;
                outline: none;
                cursor: pointer;
            }

            .rounded-btn:hover {
                background-color: #0056b3;
                /* Change color on hover */
            }
        </style>
        <a href="add_expenses.php" class="rounded-btn">+</a>
    </div>
</div>
<script>
    // Function to fetch expense details
    function fetchExpenseDetails(expenseId) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var expense = JSON.parse(this.responseText);
                document.getElementById("expenseId").textContent = expense.expense_id;
                document.getElementById("amount").textContent = expense.amount;
                document.getElementById("description").textContent = expense.description;
                document.getElementById("date").textContent = expense.date;
                document.getElementById("companyId").textContent = expense.company_id;
                document.getElementById("branchId").textContent = expense.branch_id;
                document.getElementById("createdByUserId").textContent = expense.created_by_user_id;
                document.getElementById("updatedByUserId").textContent = expense.updated_by_user_id;
                document.getElementById("createdAt").textContent = expense.created_at;
                document.getElementById("updatedAt").textContent = expense.updated_at;
                document.getElementById("status").textContent = expense.status;
                document.getElementById("expenseIdInput").value = expense.expense_id;
            }
        };
        // Example URL to fetch expense details (replace with your actual endpoint)
        xhttp.open("GET", "expense_details.php?id=" + expenseId, true);
        xhttp.send();
    }

    // Attach event listeners to card elements
    var cards = document.getElementsByClassName("card");
    for (var i = 0; i < cards.length; i++) {
        cards[i].addEventListener("click", function() {
            var expenseId = this.getAttribute("data-expense-id");
            fetchExpenseDetails(expenseId);
        });
    }

    // Function to handle delete action
    // Function to handle delete action
    function deleteExpense() {
        var confirmation = confirm("Are you sure you want to delete this expense?");
        if (confirmation) {
            var expenseId = document.getElementById("expenseIdInput").value;
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Handle success or display error message
                    alert(this.responseText); // You might want to handle the response appropriately
                    location.reload(); // Reload the page to reflect changes
                }
            };
            // Example URL for delete action (replace with your actual endpoint)
            xhttp.open("POST", "delete_expense.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("expense_id=" + expenseId);
        }
    }
</script>
<?php
require './includes/footer.php';
?>