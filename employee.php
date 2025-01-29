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
            align-items: center; 
            gap: 10px;
        }
        table th, table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
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
            background-color: var(--bs-primary);
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
            <li class="nav-item">
                <a class="nav-link active" href="change_password.php"><i class="fa-solid fa-key"></i> Change Password</a>
            </li>
        </ul>
        <div class="d-flex">
            <a href="logout.php" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a>
        </div>
    </div>
</nav>

<form method="POST">
    <div class="container mt-4">
        <div style="font-size: 20px;">
            <?php
                date_default_timezone_set("Asia/Manila");
                $date = date("F d, Y - l");
                echo '<i class="fa-regular fa-calendar-days"></i> ' . $date . "<br>";
            ?>
            <div id="liveClock"></div>
        </div>

        <script>
            function updateClock() {
                const now = new Date();
                const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                const timeString = now.toLocaleTimeString('en-US', options);
                document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock"></i> ` + timeString;
            }
            setInterval(updateClock, 1000);
            updateClock();
        </script>

        <!-- Date From and Date To Inputs -->
        <div class="row mt-4">
            <div class="col-md-4">
                <label><b>Date From</b></label>
                <input class="form-control" type="date" name="date_from" id="date_from" required>
            </div>
            <div class="col-md-4">
                <label><b>Date To</b></label>
                <input class="form-control" type="date" name="date_to" id="date_to" required>
            </div>
        </div>
    </div>

    <!-- Table to Display Users -->
    <table class="table table-bordered mt-3" id="table_id">
        <thead>
            <tr>
                <th>Idnumber</th>
                <th>RFID</th>
                <th>Firstname</th>
                <th>Middlename</th>
                <th>Lastname</th>
                <th>Contact Number</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                include_once 'database.php';
                $sql = "SELECT * FROM tblinfo";
                $result = mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . $row['Idnumber'] . '</td>';
                    echo '<td>' . $row['rfid'] . '</td>';
                    echo '<td>' . $row['Firstname'] . '</td>';
                    echo '<td>' . $row['Middlename'] . '</td>';
                    echo '<td>' . $row['Lastname'] . '</td>';
                    echo '<td>' . $row['contact_number'] . '</td>';
                    echo '<td class="actions">
                        
                            <input type="hidden" name="idnumber" value="' . $row['Idnumber'] . '">
                            <button value="'.$row['Idnumber'].'" type="button" onclick="printdtr(this.value)" name="btndelete" class="btn btn-info"><i class="fa-solid fa-print"></i></button>
                        
                    </td>';
                    echo '</tr>';
                }
            ?>
        </tbody>
    </table>
</form>
</body>
</html>
<script>
    function printdtr(id){
        var date_from=document.getElementById("date_from").value;
        var date_to=document.getElementById("date_to").value;
        window.open("attendance.report.php?id="+id+"&&date_from="+date_from+"&&date_to="+date_to,"_new");
    }
</script>