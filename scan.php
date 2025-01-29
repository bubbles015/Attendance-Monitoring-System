<html>
<head>
    <title>Attendance Monitoring System</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-sm navbar-dark" style="background: linear-gradient(45deg, #00C9FF, #92FE9D);">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">Attendance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="scan.php"><i class="fa-solid fa-qrcode"></i> Scan</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Date and Time -->
    <div class="container">
        <div class="row mt-2">
            <?php
                date_default_timezone_set("Asia/Manila");
                $date = date("M, d, Y - D");
                $time = date("h:i A"); 
                echo $date . "<br> " . $time;
            ?>  
        </div>
    </div>

    <!-- RFID Scan Input -->
    <form method="POST">
        <div class="container">
            <div class="row mt-3">
                <div class="col-md-4">
                    <label><i class="fa-solid fa-rss"></i> <b>Scan RFID</b></label>
                    <input type="text" class="form-control" name="scanid" id="scanid" placeholder="Scan your RFID here">
                </div>
            </div>
        </div>
    </form>

    <!-- Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        .navbar .nav-link {
            color: #fff;
        }
        /* Aligning the table */
        .table {
            margin-top: 20px;
        }
        .btn-circle {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #28a745; /* Green border */
            color: #28a745; /* Green color */
        }
        .btn-circle:hover {
            background-color: #28a745; /* Green background on hover */
            color: #fff; /* White icon on hover */
        }
    </style>
</body>
</html>
