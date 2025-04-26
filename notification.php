<?php
include('server_side/check_session.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <?php include('header/header.php') ?>
    <link href="styles/notification.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex" style="padding-left: 0;">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <h1>Notifications</h1>

            <div class="action-row mb-4">
                <button class="btn btn-primary me-2" id="refreshNotifications">
                    <i class="fa-solid fa-rotate me-2"></i>Refresh
                </button>
            </div>

            <div class="filters-section mb-4">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="searchNotification"
                            placeholder="Search notifications...">
                    </div>
                    <div class="col-md-4 mb-2">
                        <select class="form-select" id="filterStatus">
                            <option value="">All Notifications</option>
                            <option value="0">Unread</option>
                            <option value="1">Read</option>
                        </select>
                    </div>
                </div>
            </div>

            <div id="notifications-list" class="notifications-container">
                <!-- Notifications will be loaded here -->
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Details Modal -->
    <div class="modal fade" id="notificationDetailModal" tabindex="-1" aria-labelledby="notificationDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationDetailLabel">Notification Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="notification-details-content">
                        <!-- Notification details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success d-none" id="approveBtn">Approve</button>
                    <button type="button" class="btn btn-danger d-none" id="declineBtn">Decline</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary" id="viewTransactionBtn">View Transaction</button> -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadNotifications() {
            $("#notifications-list").html(`
                <div class="loading-spinner text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);

            const searchTerm = $("#searchNotification").val();
            const filterType = $("#filterType").val();
            const filterStatus = $("#filterStatus").val();

            $.ajax({
                url: "server_side/fetchNotifications.php",
                type: "GET",
                data: {
                    search: searchTerm,
                    type: filterType,
                    status: filterStatus
                },
                dataType: "json",
                success: function(notifications) {
                    let notificationsHTML = "";

                    if (notifications.length > 0) {
                        notifications.forEach(function(notification) {
                            const statusClass = notification.notificationStatus == 0 ? "unread" :
                                "read";
                            const dateFormatted = new Date(notification.createdAt).toLocaleString();

                            let typeIcon, typeBadge;
                            if (notification.notificationType == 0) {
                                typeIcon = "fa-regular fa-bell";
                                typeBadge = "badge bg-success";
                                typeText = "Request";
                            } else if (notification.notificationType == 1) {
                                typeIcon = "fa-solid fa-circle-exclamation";
                                typeBadge = "badge bg-warning";
                                typeText = "Alert";
                            } else {
                                typeIcon = "fa-regular fa-bell";
                                typeBadge = "badge bg-info";
                                typeText = "Response";
                            }

                            notificationsHTML += `

                            <div class="notification-item ${statusClass}" data-id="${notification.id}">
                                <div class="notification-icon">
                                    <i class="fas ${typeIcon}"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="notification-header">
                                        <span class="${typeBadge}">${typeText}</span>
                                    </div>
                                    <div class="notification-message">${notification.notificationMessage}</div>
                                </div>
                                <div class="notification-actions">
                                    <button class="btn btn-sm view-notification" data-id="${notification.id}" data-transaction-id="${notification.transactionId}" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm mark-read" data-id="${notification.id}" title="${notification.notificationStatus == 0 ? 'Mark as Read' : 'Mark as Unread'}">
                                        <i class="fas ${notification.notificationStatus == 0 ? 'fa-envelope-open' : 'fa-envelope'}"></i>
                                    </button>
                                </div>
                            </div>
                            `;
                        });
                    } else {
                        notificationsHTML = `
                        <div class="no-notifications">
                            <i class="fas fa-bell-slash"></i>
                            <p>No notifications found</p>
                        </div>`;
                    }

                    $("#notifications-list").html(notificationsHTML);

                    // Add click event for viewing notification details
                    $(".view-notification").click(function() {
                        const notificationId = $(this).data("id");
                        const transactionId = $(this).data(
                            "transactionId"); // This will get the value from data-transaction-id
                        viewNotificationDetails(notificationId, transactionId);
                    });

                    // Add click event for marking notification as read/unread
                    $(".mark-read").click(function(e) {
                        e.stopPropagation();
                        const notificationId = $(this).data("id");
                        const isUnread = $(this).closest(".notification-item").hasClass("unread");
                        markNotification(notificationId, isUnread ? 1 : 0);
                    });

                    // Make entire notification clickable
                    $(".notification-item").click(function() {
                        const notificationId = $(this).data("id");
                        // Add this line to get the transaction ID from the view button
                        const transactionId = $(this).find(".view-notification").data("transactionId");
                        console.log("Notification item clicked:", notificationId, transactionId);
                        viewNotificationDetails(notificationId, transactionId);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error loading notifications:", error);
                    $("#notifications-list").html(`
                        <div class="alert alert-danger text-center">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Failed to load notifications. Please try again.
                        </div>
                    `);
                }
            });
        }

        function approveTransaction(notificationId, transactionId) {
            Swal.fire({
                title: 'Confirm Approval',
                text: 'Are you sure you want to approve this transaction?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Approving transaction',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: 'server_side/approve_transaction.php',
                        type: 'POST',
                        data: {
                            notificationId: notificationId,
                            transactionId: transactionId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Approved!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Close modal
                                    $("#notificationDetailModal").modal("hide");
                                    // Reload notifications
                                    loadNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message || 'Failed to approve transaction',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            console.log("Response:", xhr.responseText);

                            Swal.fire({
                                title: 'Server Error!',
                                text: 'There was a problem connecting to the server',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        function viewNotificationDetails(notificationId, transactionId) {
            $.ajax({
                url: "server_side/getNotificationDetails.php",
                type: "GET",
                data: {
                    id: notificationId,
                    transactionId: transactionId
                },
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        const notification = data.notification;
                        let typeText;

                        console.log("View details called with:", notificationId, transactionId);

                        //$("#approveBtn").removeAttr("data-notification-id").removeAttr("data-transaction-id");
                        //$("#declineBtn").removeAttr("data-notification-id").removeAttr("data-transaction-id");

                        // Set notification ID using HTML attributes instead of jQuery data
                        $("#approveBtn").attr("data-notification-id", notificationId);
                        $("#approveBtn").attr("data-transaction-id", transactionId);
                        $("#declineBtn").attr("data-notification-id", notificationId);
                        $("#declineBtn").attr("data-transaction-id", transactionId);

                        // Log for debugging
                        console.log("Set notification ID:", notification.notificationId);

                        if (notification.notificationType == 0) {
                            typeText = "In Request";
                        } else if (notification.notificationType == 1) {
                            typeText = "Out Request";
                        } else {
                            typeText = "System";
                        }

                        const dateFormatted = new Date(notification.createdAt).toLocaleString();

                        let detailsHTML = `
                    <div class="notification-detail-body">
                        <h6>Message:</h6>
                        <p>${notification.notificationMessage}</p>
                        
                        <h6>Additional Notes:</h6>
                        <p>${notification.notes || "No additional notes"}</p>
                    </div>
                `;

                        // If there's transaction data available, add transaction details
                        if (notification.transaction) {
                            const trans = notification.transaction;
                            const transTypeText = trans.transactionType == 0 ? "In" : "Out";
                            const statusBadge = getStatusBadge(trans.transactionStatus);
                            const transDate = new Date(trans.dateOfRequest).toLocaleDateString();

                            // Store transaction ID for buttons
                            $("#approveBtn").data("transaction-id", transactionId);
                            $("#declineBtn").data("transaction-id", transactionId);

                            console.log("Set transaction ID:", transactionId);

                            if (trans.transactionStatus == 0) { // Pending
                                if (<?php echo ($_SESSION['user_role'] == 1) ? 'true' : 'false'; ?>) {
                                    $("#approveBtn, #declineBtn").removeClass("d-none");
                                } else {
                                    $("#approveBtn, #declineBtn").addClass("d-none");
                                }
                            } else {
                                $("#approveBtn, #declineBtn").addClass("d-none");
                            }

                            detailsHTML += `
                        <hr>
                        <div class="transaction-details">
                            <h5 class="mb-3">Transaction Details</h5>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <strong>Requestor:</strong> ${trans.requestorName}
                                </div>
                                <div class="col-md-6">
                                    <strong>Date:</strong> ${transDate}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <strong>Product:</strong> ${trans.productName}
                                </div>
                                <div class="col-md-6">
                                    <strong>Quantity:</strong> ${trans.quantity}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <strong>Type:</strong> <span class="badge ${trans.transactionType == 0 ? 'bg-success' : 'bg-warning'}">${transTypeText}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong> ${statusBadge}
                                </div>
                            </div>
                        </div>
                    `;

                            // Store transaction ID for buttons
                            $("#viewTransactionBtn").data("transaction-id", notification.transactionKey);

                            // Show/hide approve/decline buttons based on transaction status
                            // Only show if status is pending (0)
                            if (trans.transactionStatus == 0) {
                                // Check if user role is admin (role 0) - Adjust as needed based on your session structure
                                if (isAdmin()) {
                                    $("#approveBtn, #declineBtn").removeClass("d-none");
                                } else {
                                    $("#approveBtn, #declineBtn").addClass("d-none");
                                }
                            } else {
                                $("#approveBtn, #declineBtn").addClass("d-none");
                            }
                        } else {
                            // No transaction data, hide approve/decline buttons
                            $("#approveBtn, #declineBtn").addClass("d-none");
                        }

                        $("#notification-details-content").html(detailsHTML);

                        // Show the modal
                        $("#notificationDetailModal").modal("show");

                        // Mark notification as read if it's unread
                        if (notification.notificationStatus == 0) {
                            markNotification(notificationId, 1);
                        }
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: data.message || "Could not load notification details"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Server error while loading notification details"
                    });
                }
            });
        }

        function getStatusBadge(status) {
            if (status == 0) {
                return '<span class="badge bg-warning">Pending</span>';
            } else if (status == 1) {
                return '<span class="badge bg-success">Approved</span>';
            } else {
                return '<span class="badge bg-danger">Rejected</span>';
            }
        }

        function isAdmin() {
            // This is a placeholder function - replace with your actual admin check
            // For example, if user_role is stored in a JavaScript variable or data attribute
            return <?php echo ($_SESSION['user_role'] == 0) ? 'true' : 'false'; ?>;
        }

        // function approveTransaction(transactionId) {
        //     updateTransactionStatus(transactionId, 1);
        // }

        function declineTransaction(transactionId) {
            updateTransactionStatus(transactionId, 2);
        }

        function updateTransactionStatus(transactionId, status) {
            const statusText = status === 1 ? 1 : 0;

            Swal.fire({
                title: `Confirm ${statusText}?`,
                text: `Are you sure you want to ${statusText} this transaction?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: status === 1 ? '#28a745' : '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Yes, ${statusText} it!`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'server_side/approve_decline.php',
                        type: 'POST',
                        data: {
                            transactionId: transactionId,
                            status: status
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Close modal
                                    $("#notificationDetailModal").modal("hide");
                                    // Reload notifications
                                    loadNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Server error while updating transaction',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        }

        function markNotification(notificationId, status) {
            $.ajax({
                url: "server_side/updateNotificationStatus.php",
                type: "POST",
                data: {
                    id: notificationId,
                    status: status
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        // Reload notifications to reflect the changes
                        loadNotifications();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message || "Failed to update notification status"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Server error while updating notification"
                    });
                }
            });
        }

        function markAllNotificationsAsRead() {
            $.ajax({
                url: "server_side/markAllNotificationsRead.php",
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: "All notifications marked as read",
                            showConfirmButton: false,
                            timer: 1500
                        });

                        // Reload notifications
                        loadNotifications();
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: response.message || "Failed to mark notifications as read"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Server error while updating notifications"
                    });
                }
            });
        }

        $(document).ready(function() {
            // Load notifications when page loads
            loadNotifications();
            //approve
            $("#approveBtn").click(function() {
                // Use attr() instead of data()
                const notificationId = $(this).attr("data-notification-id");
                const transactionId = $(this).attr("data-transaction-id");

                console.log("Approve clicked with:", notificationId, transactionId);

                if (!notificationId || !transactionId) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Missing notification or transaction ID',
                        icon: 'error'
                    });
                    return;
                }

                approveTransaction(notificationId, transactionId);
            });

            //decline
            $("#declineBtn").click(function() {
                const notificationId = $(this).data("notification-id");
                const transactionId = $(this).data("transaction-id");
                declineTransaction(notificationId, transactionId);
            });
            // Refresh button click
            $("#refreshNotifications").click(function() {
                loadNotifications();
            });

            // Mark all as read button click
            $("#markAllRead").click(function() {
                markAllNotificationsAsRead();
            });

            // Search and filter functionality
            $("#searchNotification, #filterType, #filterStatus").on("keyup change", function() {
                loadNotifications();
            });

            // View transaction button click
            $("#viewTransactionBtn").click(function() {
                const transactionId = $(this).data("transaction-id");
                // Redirect to transaction page with the specific transaction ID
                window.location.href = "transaction.php?id=" + transactionId;
            });
        });
    </script>
</body>

</html>