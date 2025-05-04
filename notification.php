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
<style>
.notification-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-bottom: 1px solid #e0e0e0;
    transition: box-shadow 0.2s, background-color 0.2s;
}

.notification-item:hover {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    background-color: #f9f9f9;
}

.notification-checkbox {
    margin-right: 15px;
    display: flex;
    align-items: center;
}

.notification-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: transparent;
    margin-right: 15px;
    color: #5f6368;
}

.notification-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.notification-header {
    display: flex;
    justify-content: space-between;
}

.notification-header .badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.35em 0.65em;
}

.notification-message {
    color: #202124;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 80%;
}

.notification-item.unread {
    background-color: #f2f6fc;
    font-weight: 500;
}

.notification-item.unread .notification-message {
    color: #202124;
    font-weight: 500;
}

.notification-actions {
    display: none;
    align-items: center;
}

.notification-item:hover .notification-actions {
    display: flex;
}

.notification-actions button {
    color: #5f6368;
    padding: 4px 8px;
    margin-left: 4px;
    border-radius: 4px;
}

.notification-actions button:hover {
    background-color: rgba(0, 0, 0, 0.05);
    color: #202124;
}

.select-all-container {
    display: flex;
    align-items: center;
    padding: 8px 15px;
    background-color: #ffffff;
    border-radius: 0;
    border-bottom: 1px solid #e0e0e0;
    position: sticky;
    top: 0;
    z-index: 10;
}

.select-all-container .form-check {
    margin: 0;
}

#bulkActionButtons {
    margin-left: 15px;
    display: inline-flex;
    align-items: center;
}

#selectedCount {
    font-weight: 500;
    color: #5f6368;
    margin-left: 10px;
}

.notifications-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.action-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.form-check-input:checked {
    background-color: #1a73e8;
    border-color: #1a73e8;
}

.btn-primary {
    background-color: #1a73e8;
    border-color: #1a73e8;
}

.btn-primary:hover {
    background-color: #1765cc;
    border-color: #1765cc;
}

.filters-section {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.archived-notification-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    border-bottom: 1px solid #e0e0e0;
    transition: background-color 0.2s;
    cursor: pointer;
}

.archived-notification-item:hover {
    background-color: #f5f5f5;
}

.archived-notification-item .notification-content {
    flex: 1;
}

.archived-notification-item .notification-actions {
    display: flex;
    gap: 5px;
}

.archived-notification-item .notification-actions button {
    opacity: 0.7;
}

.archived-notification-item .notification-actions button:hover {
    opacity: 1;
}

.archived-notifications-container {
    max-height: 60vh;
    overflow-y: auto;
}
</style>

<body>
    <div class="container-fluid d-flex" style="padding-left: 0;">
        <?php include 'sidebar.php'; ?>
        <div class="main-content">
            <h1>Notifications</h1>

            <div class="action-row mb-4">
                <div class="col">
                    <button class="btn btn-primary me-2" id="refreshNotifications">
                        <i class="fa-solid fa-rotate me-2"></i>Refresh
                    </button>
                    <button class="btn btn-secondary me-2" id="viewArchivesBtn">
                        <i class="fa-solid fa-box-archive me-2"></i>View Archives
                    </button>
                </div>

                <!-- Bulk action buttons (initially hidden) -->
                <div id="bulkActionButtons" style="display: none;">
                    <button class="btn btn-danger me-2" id="deleteSelectedBtn">
                        <i class="fa-solid fa-trash me-2"></i>Delete
                    </button>
                    <button class="btn btn-secondary me-2" id="archiveSelectedBtn">
                        <i class="fa-solid fa-box-archive me-2"></i>Archive
                    </button>
                    <span class="ms-2" id="selectedCount">0 selected</span>
                </div>
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

    <div class="modal fade" id="archivedNotificationsModal" tabindex="-1" aria-labelledby="archivedNotificationsLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archivedNotificationsLabel">Archived Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="archived-notifications-container">
                        <div class="loading-spinner text-center p-5" id="archiveLoadingSpinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="archived-notifications-list">
                            <!-- Archived notifications will be loaded here -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteAllArchivesBtn">Delete All Archives</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                    // Add select all checkbox
                    notificationsHTML += `
                <div class="select-all-container mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllNotifications">
                        <label class="form-check-label" for="selectAllNotifications">
                            Select All
                        </label>
                    </div>
                </div>`;

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
                            typeText = "Message";
                        }

                        notificationsHTML += `
                    <div class="notification-item ${statusClass}" data-id="${notification.id}">
                        <div class="notification-checkbox">
                            <input class="form-check-input notification-checkbox-input" 
                                type="checkbox" 
                                value="${notification.id}" 
                                data-id="${notification.id}">
                        </div>
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
                $(".view-notification").click(function(e) {
                    e.stopPropagation();
                    const notificationId = $(this).data("id");
                    const transactionId = $(this).data("transactionId");
                    viewNotificationDetails(notificationId, transactionId);
                });

                // Add click event for marking notification as read/unread
                $(".mark-read").click(function(e) {
                    e.stopPropagation();
                    const notificationId = $(this).data("id");
                    const isUnread = $(this).closest(".notification-item").hasClass("unread");
                    markNotification(notificationId, isUnread ? 1 : 0);
                });

                // Make entire notification clickable (except checkboxes)
                $(".notification-item").click(function(e) {
                    // Don't trigger if the checkbox was clicked
                    if (!$(e.target).hasClass('notification-checkbox-input') && !$(e.target)
                        .closest('.notification-checkbox').length) {
                        const notificationId = $(this).data("id");
                        const transactionId = $(this).find(".view-notification").data(
                            "transactionId");
                        viewNotificationDetails(notificationId, transactionId);
                    }
                });

                // Add checkbox functionality
                setupCheckboxes();
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

        const requestData = {
            id: notificationId
        };

        // Only add transactionId if it exists
        if (transactionId) {
            requestData.transactionId = transactionId;
        }


        $.ajax({
            url: "server_side/getNotificationDetails.php",
            type: "GET",
            data: requestData,
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

    function declineTransaction(notificationId, transactionId) {
        Swal.fire({
            title: 'Confirm Decline',
            text: 'Are you sure you want to decline this transaction?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#C5172E',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, decline it!'
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
                    url: 'server_side/decline_transaction.php',
                    type: 'POST',
                    data: {
                        notificationId: notificationId,
                        transactionId: transactionId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Declined',
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

    function setupCheckboxes() {
        // Handle individual checkbox changes
        $(".notification-checkbox-input").change(function() {
            updateBulkActionButtons();
        });

        // Handle "Select All" checkbox
        $("#selectAllNotifications").change(function() {
            const isChecked = $(this).prop('checked');
            $(".notification-checkbox-input").prop('checked', isChecked);
            updateBulkActionButtons();
        });

        // Prevent notification click when clicking on checkbox
        $(".notification-checkbox").click(function(e) {
            e.stopPropagation();
        });
    }

    function updateBulkActionButtons() {
        const selectedCount = $(".notification-checkbox-input:checked").length;

        if (selectedCount > 0) {
            // Show bulk action buttons and update count
            $("#bulkActionButtons").show();
            $("#selectedCount").text(selectedCount + (selectedCount === 1 ? " selected" : " selected"));
        } else {
            // Hide bulk action buttons
            $("#bulkActionButtons").hide();
        }
    }

    function getSelectedNotificationIds() {
        const selectedIds = [];
        $(".notification-checkbox-input:checked").each(function() {
            selectedIds.push($(this).data('id'));
        });
        return selectedIds;
    }

    $(document).ready(function() {
        // Load notifications when page loads
        loadNotifications();

        //checkbbozxes:
        $("#deleteSelectedBtn").click(function() {
            const selectedIds = getSelectedNotificationIds();
            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'No Selection',
                    text: 'Please select at least one notification to delete',
                    icon: 'info'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Deletion',
                text: `Are you sure you want to delete ${selectedIds.length} notification(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Deleting...',
                        text: 'Please wait while we delete the selected notifications',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Call the server to delete the notifications
                    $.ajax({
                        url: 'server_side/delete_notification.php',
                        type: 'POST',
                        data: {
                            notificationIds: selectedIds
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
                                    // Reload notifications
                                    loadNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message ||
                                        'Failed to delete notifications',
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
        });

        $("#archiveSelectedBtn").click(function() {
            const selectedIds = getSelectedNotificationIds();
            if (selectedIds.length === 0) {
                Swal.fire({
                    title: 'No Selection',
                    text: 'Please select at least one notification to archive',
                    icon: 'info'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Archive',
                text: `Are you sure you want to archive ${selectedIds.length} notification(s)?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, archive them!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading indicator
                    Swal.fire({
                        title: 'Archiving...',
                        text: 'Please wait while we archive the selected notifications',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Call the server to archive the notifications
                    $.ajax({
                        url: 'server_side/archive_notification.php',
                        type: 'POST',
                        data: {
                            notificationIds: selectedIds
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
                                    // Reload notifications
                                    loadNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message ||
                                        'Failed to archive notifications',
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
        });
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

        $("#viewArchivesBtn").click(function() {
            loadArchivedNotifications();
            $("#archivedNotificationsModal").modal("show");
        });

        // Delete All Archives button click
        $("#deleteAllArchivesBtn").click(function() {
            Swal.fire({
                title: 'Delete All Archives',
                text: 'Are you sure you want to permanently delete all archived notifications?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete all!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'server_side/deleteAllArchived.php',
                        type: 'POST',
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
                                    // Reload archived notifications
                                    loadArchivedNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message ||
                                        'Failed to delete archived notifications',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);

                            Swal.fire({
                                title: 'Server Error!',
                                text: 'There was a problem connecting to the server',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Function to load archived notifications
        // Replace the loadArchivedNotifications function with this improved version

        function loadArchivedNotifications() {
            // Show loading spinner
            $("#archiveLoadingSpinner").show();
            $("#archived-notifications-list").html('');

            $.ajax({
                url: "server_side/fetchArchivedNotifications.php",
                type: "GET",
                dataType: "json",
                success: function(notifications) {
                    $("#archiveLoadingSpinner").hide();

                    // Check if response contains an error
                    if (notifications.error) {
                        $("#archived-notifications-list").html(`
                    <div class="alert alert-danger text-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error: ${notifications.error}
                    </div>
                `);
                        return;
                    }

                    if (notifications && notifications.length > 0) {
                        let notificationsHTML = "";

                        notifications.forEach(function(notification) {
                            let typeIcon, typeBadge, typeText;

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
                                typeText = "Message";
                            }

                            const dateFormatted = notification.createdAt ?
                                new Date(notification.createdAt).toLocaleString() :
                                'Unknown date';

                            notificationsHTML += `
                                <div class="archived-notification-item" data-id="${notification.id}">
                                    <div class="notification-icon">
                                        <i class="fas ${typeIcon}"></i>
                                    </div>
                                    <div class="notification-content">
                                        <div class="notification-header">
                                            <span class="${typeBadge}">${typeText}</span>
                                            <small class="text-muted">${dateFormatted}</small>
                                        </div>
                                        <div class="notification-message">${notification.notificationMessage || 'No message'}</div>
                                    </div>
                                    <div class="notification-actions">
                                        <button class="btn btn-sm btn-danger delete-archived" data-id="${notification.id}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary unarchive-notification" data-id="${notification.notificationId}" title="Unarchive">
                                            <i class="fas fa-box-open"></i>
                                        </button>
                                    </div>
                                </div>
                                `;
                        });

                        $("#archived-notifications-list").html(notificationsHTML);

                        // Add click handlers for delete and unarchive buttons
                        $(".delete-archived").click(function(e) {
                            e.stopPropagation();
                            const notificationId = $(this).data("id");
                            deleteArchivedNotification(notificationId);
                        });

                        $(".unarchive-notification").click(function(e) {
                            e.stopPropagation();
                            const notificationId = $(this).data("id");
                            unarchiveNotification(notificationId);
                        });

                        // Make the archived notification clickable to show details
                        $(".archived-notification-item").click(function(e) {
                            // Don't trigger if we clicked on a button
                            if ($(e.target).closest('button').length === 0) {
                                const notificationId = $(this).data("id");

                                // Store reference to the modal
                                const archiveModal = $("#archivedNotificationsModal");

                                // Get the modal instance and hide it properly
                                const bsModal = bootstrap.Modal.getInstance(archiveModal);
                                if (bsModal) {
                                    bsModal.hide();
                                } else {
                                    archiveModal.modal('hide');
                                }

                                // Wait for the modal to close before showing details
                                archiveModal.on('hidden.bs.modal', function() {
                                    // Unbind the event to prevent multiple calls
                                    archiveModal.off('hidden.bs.modal');
                                    // Show the notification details
                                    viewNotificationDetails(notificationId, null);
                                });
                            }
                        });

                    } else {
                        $("#archived-notifications-list").html(`
                    <div class="no-notifications text-center py-5">
                        <i class="fas fa-archive mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <p>No archived notifications found</p>
                    </div>
                `);
                    }
                },
                error: function(xhr, status, error) {
                    $("#archiveLoadingSpinner").hide();
                    console.error("Error loading archived notifications:", error);

                    let errorMessage = "Failed to load archived notifications. Please try again.";

                    // Try to get more detailed error message if available
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response && response.error) {
                            errorMessage = response.error;
                        }
                    } catch (e) {
                        console.error("Could not parse error response:", e);
                    }

                    $("#archived-notifications-list").html(`
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    ${errorMessage}
                </div>
            `);
                }
            });
        }

        // Function to delete a single archived notification
        function deleteArchivedNotification(notificationId) {
            Swal.fire({
                title: 'Delete Archived Notification',
                text: 'Are you sure you want to permanently delete this notification?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'server_side/delete_notifications.php',
                        type: 'POST',
                        data: {
                            notificationIds: [notificationId]
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload archived notifications
                                    loadArchivedNotifications();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message ||
                                        'Failed to delete notification',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
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

        // Function to unarchive a notification
        function unarchiveNotification(notificationId) {
            $.ajax({
                url: 'server_side/unarchive_notification.php',
                type: 'POST',
                data: {
                    notificationId: notificationId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Notification has been unarchived',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            // Reload archived notifications
                            loadArchivedNotifications();
                            // Also reload main notifications if they're visible
                            loadNotifications();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: response.message || 'Failed to unarchive notification',
                            icon: 'error'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Server Error!',
                        text: 'There was a problem connecting to the server',
                        icon: 'error'
                    });
                }
            });
        }
    });
    </script>
</body>

</html>