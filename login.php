<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if ($username === "admin" && $password === "admin123") {
    $_SESSION["loggedin"] = true;
    header("Location: dashboard.html");
    exit();
  } else {
    $error = "Invalid credentials!";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Inventory - Admin Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #0d6efd20 0%, #0d6efd05 100%);
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-logo {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
        .login-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .form-floating label {
            color: #6c757d;
        }
        .form-control {
            border-radius: 10px;
            padding: 1rem 0.75rem;
            border: 2px solid #e9ecef;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: none;
        }
        .btn-login {
            border-radius: 10px;
            padding: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .alert-danger {
            background-color: #ff63472a;
            color: #dc3545;
        }
        .version-text {
            position: absolute;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            color: #6c757d;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="login-card p-4">
                        <div class="login-header">
                            <div class="login-logo">
                                <i class="fas fa-capsules"></i>
                            </div>
                            <h3 class="login-title">Medicine Inventory</h3>
                            <p class="login-subtitle">Admin Login Portal</p>
                        </div>
                        
                        <form method="POST" class="needs-validation" novalidate>
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                <label for="username"><i class="fas fa-user me-2"></i>Username</label>
                                <div class="invalid-feedback">
                                    Please enter your username.
                                </div>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
                                <div class="invalid-feedback">
                                    Please enter your password.
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                        
                        <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="version-text">Version 1.0.0 © 2025 Medicine Inventory System</div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
