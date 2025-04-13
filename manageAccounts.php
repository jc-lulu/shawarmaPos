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
                <table id="accountsTable" class="table table-hover text-center display responsive nowrap"
                    style="width:100%">
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
    <div class="modal fade" id="addAccountModal" tabindex="-1" aria-labelledby="addAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addAccountModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Add New Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addAccountForm" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="username" class="form-label">
                                <i class="fas fa-user text-primary me-2"></i>Username
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="invalid-feedback">Please provide a username.</div>
                            <div class="form-text">Username must be unique.</div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-primary me-2"></i>Email
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="invalid-feedback">Please provide a valid email address.</div>
                            <div class="form-text">Enter a valid email address.</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock text-primary me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Password is required.</div>
                                <div class="password-strength mt-2"></div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-check-circle text-primary me-2"></i>Confirm Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password"
                                        name="confirm_password" required>
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback">Please confirm your password.</div>
                                <div id="password-match-feedback" class="form-text"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag text-primary me-2"></i>Role
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled selected>Select a role</option>
                                    <option value="0">Admin</option>
                                    <option value="1">Staff</option>
                                </select>
                            </div>
                            <div class="invalid-feedback">Please select a role.</div>
                            <div class="form-text">Admin can manage all aspects of the system. Staff has limited access.
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveAccountBtn">
                        <i class="fas fa-save me-1"></i>Create Account
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Account Modal -->
    <div class="modal fade" id="editAccountModal" tabindex="-1" aria-labelledby="editAccountModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #17a2b8; color: white;">
                    <h5 class="modal-title" id="editAccountModalLabel">Edit Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
        $(document).ready(function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });


            // Initialize DataTable
            $('#accountsTable').DataTable({
                responsive: true,
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

            $('.toggle-password').click(function () {
                const passwordInput = $(this).siblings('input');
                const eyeIcon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            $('#password').keyup(function () {
                const password = $(this).val();
                const strengthIndicator = $('.password-strength');

                // Clear previous content
                strengthIndicator.removeClass('text-danger text-warning text-success').empty();

                if (password.length === 0) {
                    return;
                }

                // Check password strength
                let strength = 0;

                // Length check
                if (password.length >= 8) strength += 1;

                // Character variety checks
                if (password.match(/[A-Z]/)) strength += 1;  // uppercase
                if (password.match(/[a-z]/)) strength += 1;  // lowercase
                if (password.match(/[0-9]/)) strength += 1;  // numbers
                if (password.match(/[^A-Za-z0-9]/)) strength += 1;  // special chars

                // Display result
                if (strength < 2) {
                    strengthIndicator.addClass('text-danger').html('<i class="fas fa-exclamation-triangle me-1"></i>Weak password');
                } else if (strength < 4) {
                    strengthIndicator.addClass('text-warning').html('<i class="fas fa-info-circle me-1"></i>Moderate password');
                } else {
                    strengthIndicator.addClass('text-success').html('<i class="fas fa-check-circle me-1"></i>Strong password');
                }
            });

            $('#confirm_password').keyup(function () {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                const feedback = $('#password-match-feedback');

                if (confirmPassword.length === 0) {
                    feedback.removeClass('text-danger text-success').empty();
                    return;
                }

                if (password === confirmPassword) {
                    feedback.removeClass('text-danger').addClass('text-success').html('<i class="fas fa-check-circle me-1"></i>Passwords match');
                } else {
                    feedback.removeClass('text-success').addClass('text-danger').html('<i class="fas fa-times-circle me-1"></i>Passwords do not match');
                }
            });

            // Form validation
            $('#addAccountModal').on('show.bs.modal', function () {
                // Reset form and remove validation classes when modal opens
                $('#addAccountForm').trigger('reset');
                $('#addAccountForm input, #addAccountForm select').removeClass('is-invalid is-valid');
                $('.password-strength, #password-match-feedback').empty().removeClass('text-danger text-warning text-success');
            });

            // Create new account
            $('#saveAccountBtn').click(function () {
                const form = $('#addAccountForm')[0];

                // Mark all fields for validation
                $(form).find('input, select').each(function () {
                    if (!this.checkValidity()) {
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    }
                });

                if (!form.checkValidity()) {
                    return;
                }

                if ($('#password').val() !== $('#confirm_password').val()) {
                    $('#confirm_password').addClass('is-invalid');
                    $('#password-match-feedback').removeClass('text-success').addClass('text-danger')
                        .html('<i class="fas fa-times-circle me-1"></i>Passwords do not match');
                    return;
                }

                // Add loading indicator to button
                const originalBtnHTML = $(this).html();
                $(this).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Creating...');
                $(this).prop('disabled', true);

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
                    success: function (response) {
                        if (response.success) {
                            $('#addAccountModal').modal('hide');

                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function () {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message,
                                icon: 'error'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while creating account',
                            icon: 'error'
                        });
                    },
                    complete: function () {
                        // Restore button state
                        $('#saveAccountBtn').html(originalBtnHTML);
                        $('#saveAccountBtn').prop('disabled', false);
                    }
                });
            });

            // Edit account - populate modal
            $(document).on('click', '.edit-btn', function () {

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
            $('#updateAccountBtn').click(function () {
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
                    success: function (response) {
                        if (response.success) {
                            $('#editAccountModal').modal('hide');

                            Swal.fire({
                                title: 'Success',
                                text: response.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function () {
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
                    error: function () {
                        Swal.fire({
                            title: 'Error',
                            text: 'Server error while updating account',
                            icon: 'error'
                        });
                    }
                });
            });

            // Delete account
            $(document).on('click', '.delete-btn', function () {
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
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function () {
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
                            error: function () {
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