<?php session_start(); ?>
<html>
<head>
    <title>Manage Employees</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="text-dark">
    <style>
        .button-group {
            display: flex;
            align-items: center; /* Vertically center the buttons */
            gap: 10px; /* Space between buttons */
        }
        table th, table td {
            border: 1px solid #dee2e6; /* Standard Bootstrap border color */
            padding: 8px; /* Standard padding for a regular look */
            text-align: left; /* Align text to the left */
        }
        table th {
            background-color: #f8f9fa; /* Light background for header */
            font-weight: bold; /* Standard bold header */
        }
        body {
            background-color: #f8f9fa;
        }
        .table {
            margin-top: 20px;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table thead th {
    background-color: var(--bs-primary); /* Use Bootstrap primary color */
    color: white;
    text-align: center;
}

        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .custom-btn {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            border: none;
            border-radius: 25px; /* Rounded corners */
            padding: 10px 20px; /* Padding */
            font-size: 16px; /* Font size */
            transition: background-color 0.3s, transform 0.2s; /* Smooth transitions */
        }

        .custom-btn:hover {
            background-color: #0056b3; /* Darker shade on hover */
            transform: scale(1.05); /* Slightly enlarge on hover */
        }

        .custom-btn:focus {
            outline: none; /* Remove outline */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25); /* Bootstrap focus shadow */
        }
    </style>
<nav class="navbar navbar-expand-sm navbar-dark" style="background: linear-gradient(45deg, #00C9FF, #92FE9D);">
    <div class="container-fluid">
        <ul class="navbar-nav me-auto">
        <a class="navbar-brand" href="home.php">
            <img src="q.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;">
        </a>
            <li class="nav-item">
                <a class="nav-link active" href="main.php"><i class="fa-solid fa-house"></i> Home</a>
            </li>
        </ul>
        <div class="d-flex">
            <a href="logout.php" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
        </div>
    </div>
</nav>

<form method="POST">
    <?php
    include_once 'database.php';
    if (isset($_SESSION['role']) && $_SESSION['role'] != 'admin') {
        header('location:main.php');
        exit;
    }

    // Save new employee
    if (isset($_POST['btnsave'])) {
        $idnum = $_POST['idnumber'];
        $rfid = $_POST['rfid']; // New RFID field
        $fn = $_POST['firstname'];
        $mn = $_POST['middlename'];
        $ln = $_POST['lastname'];
        $a = $_POST['address'];
        $b = $_POST['birthday'];
        $contact = $_POST['contactnumber'];

        // Insert into tblinfo
        $sql = "INSERT INTO tblinfo (Idnumber, Rfid, Firstname, Middlename, Lastname, contact_number, address, birthday) 
                VALUES ('$idnum', '$rfid', '$fn', '$mn', '$ln', '$contact', '$a', '$b')";
        mysqli_query($conn, $sql);

        // Insert into tbluser
        $sql = "INSERT INTO tbluser (Idnumber, Username, Password, Role, Status) 
                VALUES ('$idnum', '$fn', '$idnum', 'employee', 'active')";
        mysqli_query($conn, $sql);

        echo '<script>alert("Added Successfully");</script>';
    }

    // Update employee
    if (isset($_POST['btnupdate'])) {
        $idnum = $_POST['idnumber'];
        $rfid = $_POST['rfid']; // New RFID field
        $fn = $_POST['firstname'];
        $mn = $_POST['middlename'];
        $ln = $_POST['lastname'];
        $a = $_POST['address'];
        $b = $_POST['birthday'];
        $contact = $_POST['contactnumber'];

        // Update query for tblinfo
        $sql = "UPDATE tblinfo SET Rfid='$rfid', Firstname='$fn', Middlename='$mn', Lastname='$ln', contact_number='$contact', address='$a', birthday='$b' 
                WHERE Idnumber='$idnum'";
        mysqli_query($conn, $sql);

        // Update query for tbluser
        $sql = "UPDATE tbluser SET Username='$fn' WHERE Idnumber='$idnum'";
        mysqli_query($conn, $sql);

        echo '<script>alert("Updated Successfully");</script>';
    }

    // Delete employee
if (isset($_POST['btndelete'])) {
    $idnum = $_POST['idnumber'];

    // Check for related logs
    $sql_check = "SELECT * FROM tbllogs WHERE user_id='$idnum'";
    $result = mysqli_query($conn, $sql_check);

    if (mysqli_num_rows($result) > 0) {
        // Delete related rows in tbllogs
        $sql = "DELETE FROM tbllogs WHERE user_id='$idnum'";
        mysqli_query($conn, $sql);
    }

    // Delete query for tblinfo
    $sql = "DELETE FROM tbllogs WHERE user_id='$idnum'";
    mysqli_query($conn, $sql);

    // Delete query for tbluser
    $sql = "DELETE FROM tbluser WHERE Idnumber='$idnum'";
    mysqli_query($conn, $sql);

    echo '<script>alert("Deleted Successfully");</script>';
}

    ?>

<div class="container mt-4">
        <!-- Date and Live Clock -->
        <div style="font-size: 20px;">
            <?php
                date_default_timezone_set("Asia/Manila");
                $date = date("F d, Y - l");
                echo '<i class="fa-regular fa-calendar-days"></i> ' . $date . "<br>";
            ?>
            <div id="liveClock"></div> <!-- Placeholder for the live clock -->
        </div>

        <script>
            function updateClock() {
                const now = new Date();
                const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                const timeString = now.toLocaleTimeString('en-US', options);
                document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock"></i> ` + timeString;
            }
            setInterval(updateClock, 1000); // Update the clock every second
            updateClock(); // Initialize clock immediately
        </script>
    <div class="row mt-4"></div>
    <!-- Trigger Button for Modal -->
    <button type="button" class="btn btn-primary custom-btn" data-bs-toggle="modal" data-bs-target="#userModal">
    <i class="bi bi-plus-circle"></i> Add User
</button>

<!-- Modal Structure for Adding User -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Add User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for adding user -->
                <form method="POST">
                    <div class="container">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Idnumber</b></label>
                                <input class="form-control" type="text" name="idnumber" id="idnumber" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>RFID</b></label>
                                <input class="form-control" type="text" name="rfid" id="rfid" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Firstname</b></label>
                                <input class="form-control" type="text" name="firstname" id="firstname" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Middlename</b></label>
                                <input class="form-control" type="text" name="middlename" id="middlename" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Lastname</b></label>
                                <input class="form-control" type="text" name="lastname" id="lastname" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Contact Number</b></label>
                                <input class="form-control" type="text" name="contactnumber" id="contactnumber" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Address</b></label>
                                <input class="form-control" type="text" name="address" id="address" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Birthday</b></label>
                                <input class="form-control" type="date" name="birthday" id="birthday" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="btnsave" class="btn btn-primary">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<table class="table table-bordered mt-3" id="table_id">
    <thead>
        <tr>
            <th style="width: 10%;">Idnumber</th>
            <th style="width: 5%;">RFID</th> <!-- Adjusted width for RFID -->
            <th>Firstname</th>
            <th>Middlename</th>
            <th>Lastname</th>
            <th>Contact Number</th>
            <th>Address</th>
            <th>Birthday</th>
            <th>Date Encoded</th>
            <th style="width: 15%;">Actions</th> <!-- Added width for actions -->
        </tr>
    </thead>
    <tbody>
        <?php
            // Fetching data from tblinfo
            $sql = "SELECT * FROM tblinfo";
            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['Idnumber'] . '</td>';
                echo '<td>' . $row['rfid'] . '</td>'; // Display RFID
                echo '<td>' . $row['Firstname'] . '</td>';
                echo '<td>' . $row['Middlename'] . '</td>';
                echo '<td>' . $row['Lastname'] . '</td>';
                echo '<td>' . $row['contact_number'] . '</td>';
                echo '<td>' . $row['address'] . '</td>';
                echo '<td>' . $row['birthday'] . '</td>';
                echo '<td>' . $row['date_encoded'] . '</td>';
                echo '<td class="actions">
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="idnumber" value="' . $row['Idnumber'] . '">
                        <input type="hidden" name="rfid" value="' . $row['rfid'] . '">
                        <input type="hidden" name="firstname" value="' . $row['Firstname'] . '">
                        <input type="hidden" name="middlename" value="' . $row['Middlename'] . '">
                        <input type="hidden" name="lastname" value="' . $row['Lastname'] . '">
                        <input type="hidden" name="contactnumber" value="' . $row['contact_number'] . '">
                        <input type="hidden" name="address" value="' . $row['address'] . '">
                        <input type="hidden" name="birthday" value="' . $row['birthday'] . '">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal" 
                                onclick="setUpdateFields(\'' . $row['Idnumber'] . '\', \'' . $row['rfid'] . '\', \'' . $row['Firstname'] . '\', \'' . $row['Middlename'] . '\', \'' . $row['Lastname'] . '\', \'' . $row['contact_number'] . '\', \'' . $row['address'] . '\', \'' . $row['birthday'] . '\')"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="submit" name="btndelete" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>';
                echo '</tr>';
            }
        ?>
    </tbody>
</table>


<!-- Modal Structure for Updating User -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="container">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Idnumber</b></label>
                                <input class="form-control" type="text" name="idnumber" id="updateIdnumber" required readonly>
                            </div>
                            <div class="col-md-3">
                                <label><b>RFID</b></label>
                                <input class="form-control" type="text" name="rfid" id="updateRfid" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Firstname</b></label>
                                <input class="form-control" type="text" name="firstname" id="updateFirstname" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Middlename</b></label>
                                <input class="form-control" type="text" name="middlename" id="updateMiddlename" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Lastname</b></label>
                                <input class="form-control" type="text" name="lastname" id="updateLastname" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label><b>Contact Number</b></label>
                                <input class="form-control" type="text" name="contactnumber" id="updateContactnumber" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Address</b></label>
                                <input class="form-control" type="text" name="address" id="updateAddress" required>
                            </div>
                            <div class="col-md-3">
                                <label><b>Birthday</b></label>
                                <input class="form-control" type="date" name="birthday" id="updateBirthday" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="btnupdate" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setUpdateFields(idnum, rfid, fn, mn, ln, contact, address, birthday) {
        document.getElementById('updateIdnumber').value = idnum;
        document.getElementById('updateRfid').value = rfid;
        document.getElementById('updateFirstname').value = fn;
        document.getElementById('updateMiddlename').value = mn;
        document.getElementById('updateLastname').value = ln;
        document.getElementById('updateContactnumber').value = contact;
        document.getElementById('updateAddress').value = address;
        document.getElementById('updateBirthday').value = birthday;
    }
</script>
</body>
</html>
