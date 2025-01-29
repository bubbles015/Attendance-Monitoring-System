<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Monitoring System</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.2/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('background.jpeg'); /* Ensure this is the correct path to your background image */
            background-size: cover;
            background-position: center;
            font-family: 'Arial', sans-serif;
            height: 100vh;
            margin: 0; /* Remove default margin */
        }
        .container {
            margin-top: 80px;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-container .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .form-container .btn-primary:hover {
            background-color: #0056b3;
        }
        .form-container label {
            font-weight: bold;
            color: #333;
        }
        .form-container input {
            padding: 10px;
            border-radius: 5px;
        }
        .form-container .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
        .form-container .btn {
            padding: 10px;
        }
        .password-container {
            position: relative;
        }
        .password-container input {
            padding-right: 40px;
        }
        .password-container .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px; /* Adjust the size of the eye icon */
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="text-white">
     <?php
        include_once 'database.php';
        if (isset($_POST['btnlogin'])) {
            $em = $_POST['email'];
            $pw = $_POST['password'];
            $sql = "SELECT * FROM tbluser WHERE username='$em' AND password='$pw'";
            $data = mysqli_query($conn, $sql);
            if ($row = mysqli_fetch_array($data)) {
                $_SESSION['idnum'] = $row['idnumber'];
                $_SESSION['role'] = $row['role'];
                if ($row['role'] == 'admin') {
                    header('location:main.php');
                } else if ($row['role'] == 'employee'){
                    header('location:employee.php');
                }
            } else {
                echo '
                <script>
                alert("Invalid Username or Password");
                </script>
                ';
            }
        } 
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="form-container">
                    <h3 class="mb-4 text-center text-dark">Login to Your Account</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email"><i class="fas fa-user"></i> Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-4 password-container">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                            <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                        </div>
                        <button class="btn btn-primary form-control" name="btnlogin">
                            <i class="fa-solid fa-right-to-bracket"></i> Log in
                        </button>

                        <!-- Added the "Home" button that redirects to home.php -->
                        <a href="home.php" class="btn btn-success form-control mt-3">
                            Go to Home
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            // Toggle the type attribute between password and text
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
