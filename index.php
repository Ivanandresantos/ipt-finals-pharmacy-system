<?php
session_start();
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pharmacy Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .landing-background {
            background: linear-gradient(135deg, rgba(58, 143, 254, 0.1) 0%, rgba(0, 114, 255, 0.1) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .landing-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .welcome-section {
            background: linear-gradient(135deg, #3a8ffe 0%, #0072ff 100%);
            color: white;
            padding: 40px;
        }
        .welcome-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .auth-section {
            padding: 40px;
        }
        .auth-btn {
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="landing-background">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="landing-card">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="welcome-section h-100">
                                    <h1>Pharmacy Management System</h1>
                                    <p class="lead mb-4">A comprehensive solution for managing your pharmacy inventory, tracking medications, and streamlining operations.</p>
                                    <div class="features mt-5">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3"><i class="fas fa-pills fa-2x"></i></div>
                                            <div>Medication Inventory Management</div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3"><i class="fas fa-chart-line fa-2x"></i></div>
                                            <div>Analytics Dashboard</div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="me-3"><i class="fas fa-bell fa-2x"></i></div>
                                            <div>Low Stock Alerts</div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3"><i class="fas fa-calendar-alt fa-2x"></i></div>
                                            <div>Expiry Date Tracking</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="auth-section">
                                    <h2 class="text-center mb-4">Welcome Back</h2>
                                    <p class="text-center text-muted mb-5">Please log in to access your pharmacy dashboard</p>
                                    
                                    <div class="d-grid gap-3">
                                        <a href="login.php" class="btn btn-primary auth-btn">
                                            <i class="fas fa-sign-in-alt me-2"></i> Login to Dashboard
                                        </a>
                                        <a href="register.php" class="btn btn-outline-secondary auth-btn">
                                            <i class="fas fa-user-plus me-2"></i> Create New Account
                                        </a>
                                    </div>
                                    
                                    <div class="text-center mt-5">
                                        <p class="text-muted">© 2025 Pharmacy Management System</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
