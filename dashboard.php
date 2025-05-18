<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Pharmacy Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Pharmacy Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medications.php">Medications</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title">Medications</h5>
                                        <?php
                                        $sql = "SELECT COUNT(*) as count FROM medications";
                                        $result = $pdo->query($sql);
                                        $row = $result->fetch();
                                        ?>
                                        <p class="card-text display-4"><?php echo $row['count']; ?></p>
                                        <a href="medications.php" class="btn btn-light">Manage Medications</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-info">
                                    <div class="card-body">
                                        <h5 class="card-title">Low Stock</h5>
                                        <?php
                                        $sql = "SELECT COUNT(*) as count FROM medications WHERE quantity < 10";
                                        $result = $pdo->query($sql);
                                        $row = $result->fetch();
                                        ?>
                                        <p class="card-text display-4"><?php echo $row['count']; ?></p>
                                        <a href="medications.php?filter=low" class="btn btn-light">View Low Stock</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card text-white bg-warning">
                                    <div class="card-body">
                                        <h5 class="card-title">Expiring Soon</h5>
                                        <?php
                                        $date = date('Y-m-d', strtotime('+30 days'));
                                        $sql = "SELECT COUNT(*) as count FROM medications WHERE expiry_date <= :date";
                                        $stmt = $pdo->prepare($sql);
                                        $stmt->bindParam(':date', $date);
                                        $stmt->execute();
                                        $row = $stmt->fetch();
                                        ?>
                                        <p class="card-text display-4"><?php echo $row['count']; ?></p>
                                        <a href="medications.php?filter=expiring" class="btn btn-light">View Expiring</a>
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