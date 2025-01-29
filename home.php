<?php 
session_start();

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'attendance_monitoring_system');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = "";
$scanResult = ""; // Variable to hold scan results

if (isset($_POST['scanid'])) {
    $scanid = $_POST['scanid'];

    // Prepare statement to fetch owner's information based on RFID
    $stmt = $conn->prepare("SELECT Idnumber, Firstname, Middlename, Lastname FROM tblinfo WHERE rfid = ?");
    $stmt->bind_param("s", $scanid);
    $stmt->execute();
    $stmt->bind_result($user_id, $firstname, $middlename, $lastname);

    if ($stmt->fetch()) {
        // Construct full name
        $name = htmlspecialchars("$lastname, $firstname $middlename");

        // Set timezone and get the current time
        date_default_timezone_set("Asia/Manila");
        $currentDateTime = date("Y-m-d H:i:s");

        $stmt->close();

        // Save the log in the `tbllogs` table
        $logStmt = $conn->prepare("INSERT INTO tbllogs (user_id, rfid, name, log_date) VALUES (?, ?, ?, ?)");
        $logStmt->bind_param("isss", $user_id, $scanid, $name, $currentDateTime);
        $logStmt->execute();
        $logStmt->close();

        $scanResult = "RFID successful!";
    } else {
        $scanResult = "RFID not found!";
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Monitoring System</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background: linear-gradient(45deg, #00C9FF, #92FE9D);">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand" href="home.php">
            <img src="q.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;">
        </a>
        <!-- Left-aligned Navigation Link -->
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link active" href="login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
            </li>
        </ul>
    </div>
</nav>


    <div class="container">
        <div class="row mt-4">
            <!-- Left side: Calendar, clock, and RFID scan input -->
            <div class="col-md-6">
                <div style="font-size: 20px; font-family: 'Times New Roman', Times, serif; padding-bottom: 20px; margin-top: 100px;">
                    <?php
                        date_default_timezone_set("Asia/Manila");
                        $date = date("F d, Y - l");
                        echo '<i class="fa-regular fa-calendar-days"></i> ' . $date . "<br>"; 
                    ?>
                    <div id="liveClock"></div>
                </div>

<!-- Add a horizontal line -->
<div class="row mt-3">
    <div class="col-md-9 text-center">
        <hr style="border: none; height: 10px; background: linear-gradient(45deg, #00C9FF, #92FE9D); margin: 10px 0;">
    </div>
</div>





                <form method="POST">
                    <div class="row mt-4">
                        <div class="col-md-9 text-center">
                            <label><i class="fa-solid fa-tower-broadcast"></i> <b>RFID</b></label>
                            <input type="text" class="form-control" name="scanid" id="scanid" required>
                        </div>
                    </div>

                    <?php if (!empty($scanResult)): ?>
                    <div class="row mt-3">
                        <div class="col-md-9">
                            <div class="alert alert-info text-center" role="alert">
                                <?php echo $scanResult; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Right side: Table of scanned names and times -->
            <div class="col-md-6">
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto; width: 110%;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th style="text-align: center; vertical-align: middle;">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        // Fetch recent logs from the database
                        $conn = mysqli_connect('localhost', 'root', '', 'attendance_monitoring_system');
                        $result = $conn->query("SELECT name, rfid, DATE_FORMAT(log_date, '%h:%i:%s %p') AS time FROM tbllogs ORDER BY log_date DESC LIMIT 20");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                      <td>
                                          <div style='display: flex; align-items: center;'>
                                              <i class='fa-solid fa-circle-user dark-icon' style='font-size: 25px;'></i>
                                              <div style='margin-left: 10px;'>
                                                  <span style='font-weight: bold;'>{$row['name']}</span><br>
                                                  <small style='color: gray;'>RFID:{$row['rfid']}</small>
                                              </div>
                                          </div>
                                      </td>
                                      <td class='text-center' style='vertical-align: middle;'>{$row['time']} <i class='fa-solid fa-circle-check' style='color: blue;'></i></td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center'>No logs available</td></tr>";
                        }

                        $conn->close();
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
    // Function to keep the RFID input field focused
    function keepFocus() {
        const scanInput = document.getElementById('scanid');
        scanInput.focus();

        // Prevent focus loss by adding an event listener
        scanInput.addEventListener('blur', () => {
            scanInput.focus();
        });
    }

    // Call the function when the page loads
    document.addEventListener('DOMContentLoaded', () => {
        keepFocus();
    });

    // Clock function for real-time updates
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const timeString = now.toLocaleTimeString('en-PH', options);
        document.getElementById('liveClock').innerHTML = `<i class="fa-regular fa-clock"></i> ` + timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

</body>
</html>
<style>
    body { font-family: Arial, sans-serif; }
    .container { margin-top: 20px; }
    .navbar .nav-link { color: #fff; }
    
    input.form-control {
    border: 1.5px solid black; /* Blue border */
    border-radius: 5px; /* Optional: rounded corners */
    text-align: center; /* Center the text inside the input */
    padding-left: 0; /* Remove default padding on the left */
}
input.form-control:focus {
    box-shadow: 0 0 3px black; /* Blue shadow on focus */
    outline: none; /* Remove default outline */
}
</style>
