<?php
// Start the session at the very beginning of the file
session_start();

include("connection.php");


if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "UPDATE users SET status='0' WHERE user_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $rid); // Use "i" for integer type
    $query->execute();
    
    if ($query->affected_rows > 0) {
        echo "<script>alert('Blocked successfully');</script>";
    } else {
        echo "<script>alert('No user found or already blocked.');</script>";
    }
    
    echo "<script>window.location.href = 'account_lists.php';</script>";
}

if (isset($_GET['unblockid'])) {
    $rid = intval($_GET['unblockid']);
    $sql = "UPDATE users SET status='1' WHERE user_id=?";
    $query = $conn->prepare($sql);
    $query->bind_param("i", $rid); // Use "i" for integer type
    $query->execute();
    
    if ($query->affected_rows > 0) {
        echo "<script>alert('Unblocked successfully');</script>";
    } else {
        echo "<script>alert('No user found or already unblocked.');</script>";
    }
    
    echo "<script>window.location.href = 'account_lists.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>homepage settings</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <style>
        @font-face {
            font-family: 'nautigal';
            src: url(font/TheNautigal-Regular.ttf);
        }

        #sidebar span {
            font-family: 'nautigal';
            font-size: 50px !important;
        }

        #sidebar {
            width: 250px;
            position: fixed;
            top: 0; 
            height: 100vh;
            overflow-y: auto; 
            background: #001A3E;
            transition: transform 0.3s ease;
        }

        #sidebar.collapsed {
            transform: translateX(-100%); /* Hide sidebar */
        }

        .navbar {
            margin-left: 250px; 
            z-index: 1; 
            width: calc(100% - 250px);
            height: 50px;
            transition: margin-left 0.3s ease; 
        }

        #main-content {
            transition: margin-left 0.3s ease;
            margin-left: 250px; 
        }

        #hamburger {
            border: none;
            background: none;
            cursor: pointer;
        }

        hr {
            background-color: #ffff;
            height: 1.5px;
        }

        #sidebar .nav-link {
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
            margin-bottom: 2px;
        }

        #sidebar .nav-link:hover, #sidebar .nav-link.active {
            background-color: #fff !important;
            color: #000 !important;
        }

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-item {
            color: #000 !important;
        }

        @media (max-width: 768px) {
            #sidebar {
                position: absolute;
                transform: translateX(-100%); /* Hide sidebar off-screen */
            }
            #sidebar.show {
                transform: translateX(0); /* Show sidebar */
            }

            .navbar {
                margin-left: 0;
                width: 100%; 
            }

            #main-content {
                margin-left: 0;
            }
        }

        .flex-container {
            display: flex;
            gap: 20px;
        }
        .settings-form-container {
            margin-bottom: 20px;
        }
        .alert {
            padding: 10px;
            margin: 10px 0;
        }
        .alert-success {
            color: green;
        }
        .alert-danger {
            color: red;
        }
        .button-container {
            display: flex;
            justify-content: end;
        }
        button {
            border-radius: 50px;
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        .flex-container {
        display: flex;
        gap: 20px;
    }

    .sidebar-settings {
        display: flex;
        flex-direction: column;
        width: 230px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 35px 15px 15px 15px;
        align-items: center;
        justify-content: center;
    }

    .settings-links {
        width: 100%
    }

    .settings-links ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .settings-links li {
        margin-bottom: 10px;
        text-align: center;
    }

    .settings-links a {
        text-decoration: none;
        color: #333;
        padding: 10px 15px;
        border-radius: 2px;
        transition: 0.3s;
    }

    .settings-links a:hover {
        background-color: #ddd;
    }

    .settings-links .links {
        margin-bottom: 30px;
    }

    .main-content {
        flex: 1;
        padding: 25px;
        background-color: #ffff;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .form-group input {
        margin-bottom: 10px;
    }

    .settings-form .form-group label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        font-size: 17px;
    }

    .settings-form .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 0px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        float: right;
        margin-left: 16%;
        margin-bottom: 15px;
    }

    .four-box-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin: 5px 0 0 0;
    }

    .links {
        border-bottom: 1px solid #ccc;
    }

    .links:last-child {
        border-bottom: none;
    }

    .links i {
        font-size: 12px;
    }

    .links li a {
        display: flex;
        align-items: center;
        gap: 20px;
        font-size: 15px;
        font-weight: 600;
        padding: 15px 20px;
        transition: all 0.3s;
        justify-content: space-between;
    }

    .links .active a {
        background-color: #1c2531;
        color: white;
        border-radius: 10px 10px 10px 10px;
    }

    .button-container {
        display: flex;
        justify-content: end;
    }

    .settings-form button, 
        .save-btn {
            border-radius: 10px !important;  
            padding: 13px 30px;
            background-color: #03045e;
            border: none;
            cursor: pointer;
            color: white;
        }

        .table-full-width {
            width: 100%;
            border-collapse: collapse; /* Optional: for better border handling */
        }

        .table-full-width th, .table-full-width td {
            padding: 8px; /* Optional: for spacing */
            text-align: left; /* Optional: for text alignment */
            border: 1px solid #ddd; /* Optional: for borders */
        }
    </style>
</head>
<body>
    <?php include 'sidebar_admin.php'; ?>

    <div id="main-content" class="p-3">
        <div class="flex-container">
            <div class="main-content">
                <h1 class="text-center mb-5 mt-4">Account List</h1>
                <div class="" style="display: flex; justify-content:flex-end;">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#blockedUsersModal">
                        See Blocked Users
                    </button>
                </div>
                <table class="table-full-width mt-4">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $query = "SELECT user_id, CONCAT(firstname, ' ', lastname) AS full_name, email, contact_number FROM users WHERE status=1";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { // Fetch each row
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                    <td>
                                        <a href="account_lists.php?delid=<?php echo ($row['user_id']); ?>"
                                            title="click for block"
                                            onclick="return confirm('sure to block ?')">Block</i></a>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                                echo "<tr><td colspan='4'>No accounts found.</td></tr>";
                            }
                            ?>
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>

    <!-- Modal HTML -->
    <div class="modal fade" id="blockedUsersModal" tabindex="-1" aria-labelledby="blockedUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockedUsersModalLabel">Blocked Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table-full-width">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT user_id, CONCAT(firstname, ' ', lastname) AS full_name, email, contact_number FROM users WHERE status=0";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) { // Fetch each row
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                    <td>
                                        <a href="account_lists.php?unblockid=<?php echo ($row['user_id']); ?>"
                                            title="click for unblock"
                                            onclick="return confirm('sure to unblock ?')">Unblock</i></a>
                                    </td>
                                </tr>
                    <?php
                            }
                        } else {
                                echo "<tr><td colspan='4'>No accounts found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
