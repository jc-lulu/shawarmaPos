<?php
include('server_side/check_session.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accounts</title>
    <?php include("header/header.php"); ?>
    <link href="styles/manageAccounts.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex">
        <?php include 'sidebar.php'; ?>

        <div class="container py-4 page-container">
            <h1 class="page-title">MANAGE ACCOUNTS</h1>

            <div class="row mb-4 d-flex">
                <div class="col-md-12 d-flex justify-content-end gap-3 mb-3">
                    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                        <i class="fa-solid fa-user-plus me-2"></i>Add New Account
                    </button>
                </div>
            </div>

            <div class="data-table">
                <table id="accountsTable" class="table table-hover text-center display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <!-- <th>Date Created</th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('cedric_dbConnection.php');

                        $query = "SELECT usersId, username, email, role FROM userstable";
                        $result = $connection->query($query);

                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['usersId'] . "</td>";
                                echo "<td>" . $row['username'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";

                                // Display role
                                if ($row['role'] == 0) {
                                    echo "<td><span class='role-admin'>Admin</span></td>";
                                } else {
                                    echo "<td><span class='role-staff'>Staff</span></td>";
                                }

                                // Format date
                                // $created_at = isset($row['created_at']) ? date('M d, Y', strtotime($row['created_at'])) : 'N/A';
                                // echo "<td>" . $created_at . "</td>";

                                // Action buttons
                                echo "<td class='action-buttons-container'>";

                                echo "<button class='btn btn-edit btn-action edit-btn' 
                                    data-id='" . $row['usersId'] . "'
                                    data-username='" . $row['username'] . "'
                                    data-email='" . $row['email'] . "'
                                    data-role='" . $row['role'] . "'
                                    data-bs-toggle='tooltip' 
                                    title='Edit User'>
                                    <i class='fas fa-edit'></i>
                                </button>";

                                // Do not allow admin to delete themselves
                                if ($row['usersId'] != $_SESSION['user_id']) {
                                    echo "<button class='btn btn-delete btn-action delete-btn' 
                                        data-id='" . $row['usersId'] . "' 
                                        data-username='" . $row['username'] . "' 
                                        data-bs-toggle='tooltip' 
                                        title='Delete User'>
                                        <i class='fas fa-trash'></i>
                                    </button>";
                                }

                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No user accounts found</td></tr>";
                        }

                        $connection->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #4e73df; color: white;">
                    <h5 class="modal-title" id="addAccountModalLabel">Add New Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="0">Admin</option>
                                <option value="1" selected>Staff</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveAccountBtn">Create Account</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17a2b8; color: white;">
                    <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAccountForm">
                        <input type="hidden" id="edit_user_id" name="user_id">

                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>

                        <div class="mb-3">
                            <label for="edit_role" class="form-label">Role</label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="0">Admin</option>
                                <option value="1">Staff</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="updateAccountBtn">Update Account</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    //Footer
    include("header/footer.php");
    ?>

    <script>
        $(document).ready(function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Initialize DataTable
            $('#accountsTable').DataTable({
                responsive: true,
                // dom: '<"dt-buttons"B><"clear">lfrtip',
                // buttons: [{
                //         extend: 'copy',
                //         className: 'btn btn-outline-secondary btn-sm me-1',
                //         text: '<i class="fas fa-copy me-1"></i> Copy',
                //         titleAttr: 'Copy to clipboard'
                //     },
                //     {
                //         extend: 'csv',
                //         className: 'btn btn-outline-success btn-sm me-1',
                //         text: '<i class="fas fa-file-csv me-1"></i> CSV',
                //         titleAttr: 'Export as CSV'
                //     },
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-outline-primary btn-sm me-1',
                //         text: '<i class="fas fa-file-excel me-1"></i> Excel',
                //         titleAttr: 'Export as Excel'
                //     },
                //     {
                //         extend: 'pdf',
                //         className: 'btn btn-outline-danger btn-sm me-1',
                //         text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                //         titleAttr: 'Export as PDF'
                //     },
                //     {
                //         extend: 'print',
                //         className: 'btn btn-outline-dark btn-sm',
                //         text: '<i class="fas fa-print me-1"></i> Print',
                //         titleAttr: 'Print table'
                //     }
                // ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                order: [
                    [0, 'asc']
                ],
            });

            //custom styling for the buttons container
            $('.dt-buttons').addClass('mb-3');

            // Create new account
            $('#saveAccountBtn').click(function() {
                // Validate form
                if (!$('#addAccountForm')[0].checkValidity()) {
                    $('#addAccountForm')[0].reportValidity();
                    return;
                }

                // Check if passwords match
                if ($('#password').val() !== $('#confirm_password').val()) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Passwords do not match!',
                        icon: 'error'
                    });
                    return;
                }

                // Get form data
                const formData = {
                    username: $('#username').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    role: $('#role').val()
                };

                // Send AJAX request
                $.ajax({
                    url: 'server_side/create_account.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#addAccountModal').modal('hide');

                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload(); // Refresh the page
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while creating account',
                            icon: 'error'
                        });
                    }
                });
            });

            // Edit account - populate modal
            $(document).on('click', '.edit-btn', function() {

                $('.tooltip').hide();

                const id = $(this).data('id');
                const username = $(this).data('username');
                const email = $(this).data('email');
                const role = $(this).data('role');

                $('#edit_user_id').val(id);
                $('#edit_username').val(username);
                $('#edit_email').val(email);
                $('#edit_role').val(role);

                // Clear password field
                $('#edit_password').val('');

                // Open modal
                $('#editAccountModal').modal('show');
            });

            // Update account
            $('#updateAccountBtn').click(function() {
                // Validate form
                if (!$('#editAccountForm')[0].checkValidity()) {
                    $('#editAccountForm')[0].reportValidity();
                    return;
                }

                // Get form data
                const formData = {
                    user_id: $('#edit_user_id').val(),
                    username: $('#edit_username').val(),
                    email: $('#edit_email').val(),
                    password: $('#edit_password').val(),
                    role: $('#edit_role').val()
                };

                // Send AJAX request
                $.ajax({
                    url: 'server_side/update_account.php',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#editAccountModal').modal('hide');

                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload(); // Refresh the page
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while updating account',
                            icon: 'error'
                        });
                    }
                });
            });

            // Delete account
            $(document).on('click', '.delete-btn', function() {
                // Hide any active tooltips
                $('.tooltip').hide();

                const id = $(this).data('id');
                const username = $(this).data('username');

                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete the account for <strong>${username}</strong>.<br>This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX delete request
                        $.ajax({
                            url: 'server_side/delete_account.php',
                            type: 'POST',
                            data: {
                                user_id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload(); // Refresh the page
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message,
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Server error while deleting account',
                                    icon: 'error'
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>