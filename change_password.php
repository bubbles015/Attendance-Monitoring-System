<?php session_start(); ?>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="text-dark">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .password-box {
            border: 1px solid #ced4da;
            border-radius: 8px;
            padding: 20px;
            background: linear-gradient(45deg, #00C9FF, #92FE9D);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #ffffff;
        }
        .password-box label {
            color: #ffffff;
        }
        .input-group-text {
            cursor: pointer;
        }
    </style>

    <nav class="navbar navbar-expand-sm navbar-dark" style="background: linear-gradient(45deg, #00C9FF, #92FE9D);">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto">
            <a class="navbar-brand" href="home.php">
            <img src="q.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;">
        </a>
                <li class="nav-item">
                    <a class="nav-link active" href="employee.php"><i class="fa-solid fa-house"></i> Home</a>
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="password-box">
                    <h3 class="text-center mb-4 text-dark"><i class="fa-solid fa-key"></i> Change Password</h3>
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label text-dark"><b>Current Password</b></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter your current password">
                            <span class="input-group-text" onclick="togglePassword('currentPassword')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label text-dark"><b>New Password</b></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter your new password">
                            <span class="input-group-text" onclick="togglePassword('newPassword')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="retypePassword" class="form-label text-dark"><b>Retype Password</b></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="retypePassword" placeholder="Retype your new password">
                            <span class="input-group-text" onclick="togglePassword('retypePassword')"><i class="fa-solid fa-eye"></i></span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary w-100">Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = passwordField.nextElementSibling.querySelector('i');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
